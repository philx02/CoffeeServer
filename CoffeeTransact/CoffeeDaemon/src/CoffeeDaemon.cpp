#include <CoffeeTransact>

#include <boost/asio.hpp>
#include <boost/lexical_cast.hpp>

#include <iostream>

inline uint8_t getNibble(char iAsciiChar)
{
  if (iAsciiChar >= '0' && iAsciiChar <= '9')
  {
    return iAsciiChar - '0';
  }
  else if (iAsciiChar >= 'A' && iAsciiChar <= 'E')
  {
    return iAsciiChar - 'A' + 0xA;
  }
  else if (iAsciiChar >= 'a' && iAsciiChar <= 'f')
  {
    return iAsciiChar - 'a' + 0xA;
  }
  return 0xF;
}

template< typename InputIterator >
inline uint8_t convertToByte(InputIterator iBegin)
{
  auto wByte = getNibble(iBegin[0]) << 4 | getNibble(iBegin[1]);
  return wByte;
}

template< typename DataPort, typename OutputIterator >
bool getUserId(DataPort &iDataPort, OutputIterator iBegin, OutputIterator iEnd)
{
  uint8_t wByte;
  do
  {
    boost::asio::read(iDataPort, boost::asio::buffer(&wByte, sizeof(wByte)));
  } while (wByte != 0x02);
  std::size_t wBytesLeft = 12;
  boost::asio::read(iDataPort, boost::asio::buffer(&iBegin[0], iEnd - iBegin), [&](const boost::system::error_code &iError, std::size_t iBytesTransferred) -> std::size_t
  {
    wBytesLeft -= iBytesTransferred;
    return wBytesLeft;
  });
  boost::asio::read(iDataPort, boost::asio::buffer(&wByte, sizeof(wByte)));
  if (wByte != 0x03)
  {
    std::cout << "Wrong termination" << std::endl;
    return false;
  }
  bool wAllZeros = true;
  std::for_each(iBegin, iEnd, [&](decltype(*iBegin) &&iByte)
  {
    wAllZeros = wAllZeros && (iByte == '0');
  });
  if (wAllZeros)
  {
    return false;
  }
  uint8_t wChecksum = convertToByte(&iBegin[0]);
  for (std::size_t wIndex = 2; wIndex < 10; wIndex += 2)
  {
    wChecksum ^= convertToByte(&iBegin[wIndex]);
  }
  if (convertToByte(&iBegin[10]) != wChecksum)
  {
    std::cout << "Wrong checksum " << std::hex << static_cast< int >(iBegin[11]) << " != " << static_cast< int >(wChecksum) << std::endl;
    return false;
  }
  iBegin[10] = '\0';
  return true;
}

int main(int argc, const char *argv[])
{
  CoffeeTransact wCoffeeTransact(argv[1]);

  boost::asio::io_service wIoService;
  boost::asio::serial_port wSerialPort(wIoService, argv[2]);

  wSerialPort.set_option(boost::asio::serial_port_base::baud_rate(boost::lexical_cast< int >(argv[3])));
  wSerialPort.set_option(boost::asio::serial_port_base::flow_control(boost::asio::serial_port_base::flow_control::none));
  wSerialPort.set_option(boost::asio::serial_port_base::character_size(8));
  wSerialPort.set_option(boost::asio::serial_port_base::parity(boost::asio::serial_port_base::parity::none));
  wSerialPort.set_option(boost::asio::serial_port_base::stop_bits(boost::asio::serial_port_base::stop_bits::one));

  while (true)
  {
    std::array< char, 12 > wBuffer;
    while (!getUserId(wSerialPort, std::begin(wBuffer), std::end(wBuffer)));
    std::cout << "Code received: " << wBuffer.data() << std::endl;
    if (wCoffeeTransact.validateAndPerform(wBuffer.data()))
    {
      std::cout << "Activating machine" << std::endl;
    }
  }

  return 0;
}
