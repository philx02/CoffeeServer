#pragma once

#include "DllSwitch.h"

#include <memory>
#include <stdexcept>

class DatabaseError : public std::runtime_error
{
public:
  DatabaseError(const char *iMessage) 
    : std::runtime_error(iMessage)
  {
  }
};

class COFFEETRANSACT_API CoffeeTransact
{
public:
  CoffeeTransact(const char *iDatabase);
  ~CoffeeTransact();

  bool validateAndPerform(const char *iUserId) const;

private:
  class Impl;
#pragma warning(push)
#pragma warning(disable: 4251)
  std::unique_ptr< Impl > mImpl;
#pragma warning(pop)
};
