#include <CoffeeTransact.h>
#include <iostream>
#include <string>

void test(const CoffeeTransact &iCoffeeTransact, const std::string &iUserId)
{
  std::cout << "UserId " << iUserId << ([&]() {return iCoffeeTransact.validateAndPerform(iUserId.c_str()) ? " found, transaction completed." : " not found, transaction cancelled.";})() << std::endl;
}

int main()
{
  CoffeeTransact wCoffeeTransact("test.db");
  test(wCoffeeTransact, "Bozo the clown");
  test(wCoffeeTransact, "0123456789");
  return 0;
}
