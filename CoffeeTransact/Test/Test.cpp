#include <CoffeeTransact>
#include <iostream>
#include <string>

void test(const CoffeeTransact &iCoffeeTransact, const std::string &iUserId)
{
  std::cout << "UserId " << iUserId << ([&]() {return iCoffeeTransact.validateAndPerform(iUserId.c_str()) ? " found, transaction completed." : " not found, transaction cancelled.";})() << std::endl;
}

int main(int argc, const char *argv[])
{
  CoffeeTransact wCoffeeTransact(argv[1]);
  test(wCoffeeTransact, "Bozo the clown");
  test(wCoffeeTransact, "123456789");
  return 0;
}
