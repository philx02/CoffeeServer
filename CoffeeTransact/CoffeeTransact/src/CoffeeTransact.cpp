#include "CoffeeTransact.h"

#include "sqlite/sqlite3.h"

#include <thread>
#include <string>

class Statement
{
public:
  Statement(sqlite3 *iSqlite, const std::string &iStatementString)
    : mStatement(nullptr)
  {
    auto wResult = sqlite3_prepare(iSqlite, iStatementString.c_str(), iStatementString.size() + 1, &mStatement, nullptr);
    if (wResult != SQLITE_OK)
    {
      throw std::runtime_error(std::string("Cannot prepare statement: ").append(sqlite3_errstr(wResult)).c_str());
    }
  }
  ~Statement()
  {
    sqlite3_finalize(mStatement);
  }

  int runOnce()
  {
    int wResult = 0;
    while ((wResult = sqlite3_step(mStatement)) == SQLITE_BUSY)
    {
      std::this_thread::yield();
    }
    if (wResult == SQLITE_ERROR)
    {
      throw std::runtime_error(std::string("Statement runtime error: ").append(sqlite3_errstr(wResult)).c_str());
    }
    return wResult;
  }

  template< typename Lambda >
  void evaluate(const Lambda &iLambda)
  {
    iLambda(mStatement);
  }

private:
  sqlite3_stmt *mStatement;
};

class CoffeeTransact::Impl
{
public:
  Impl(const char *iDatabase)
    : mSqlite(nullptr)
  {
    auto wResult = sqlite3_open_v2(iDatabase, &mSqlite, SQLITE_OPEN_READWRITE, nullptr);
    if (wResult != SQLITE_OK)
    {
      throw DatabaseError(sqlite3_errstr(wResult));
    }
  }

  ~Impl()
  {
    while (sqlite3_close(mSqlite) == SQLITE_BUSY)
    {
      std::this_thread::yield();
    }
  }

  inline bool validate(const char *iUserId) const
  {
    Statement wStatement(mSqlite, std::string("SELECT userid FROM members WHERE userid = \"").append(iUserId).append("\" LIMIT 1;"));
    return wStatement.runOnce() == SQLITE_ROW;
  }

  inline bool validateAndPerform(const char *iUserId) const
  {
    auto wUnitCostCents = getUnitCostCents();
    Statement(mSqlite, std::string("UPDATE members SET balance_cents = balance_cents - ").append(std::to_string(wUnitCostCents)).append(" WHERE userid = \"").append(iUserId).append("\";")).runOnce();
    if (sqlite3_changes(mSqlite) > 0)
    {
      Statement(mSqlite, std::string("INSERT INTO transactions SELECT null, datetime('now', 'localtime'), id, 1, -").append(std::to_string(wUnitCostCents)).append(" FROM members WHERE userid = \"").append(iUserId).append("\";")).runOnce();
      return true;
    }
    return false;
  }

private:
  inline int getUnitCostCents() const
  {
    int wUnitCost = 0;
    Statement wStatement(mSqlite, "SELECT value FROM numeric_parameters WHERE name = \"unit_cost_cents\"");
    auto wResult = wStatement.runOnce();
    if (wResult == SQLITE_ROW)
    {
      wStatement.evaluate([&](sqlite3_stmt *iStatement)
      {
        wUnitCost = sqlite3_column_int(iStatement, 0);
      });
    }
    else if (wResult == SQLITE_DONE)
    {
      throw std::runtime_error("Parameter unit_cost_cents not found.");
    }
    return wUnitCost;
  }

  sqlite3 *mSqlite;
};

CoffeeTransact::CoffeeTransact(const char *iDatabase)
  : mImpl(new Impl(iDatabase))
{
}

CoffeeTransact::~CoffeeTransact()
{
}

bool CoffeeTransact::validate(const char *iUserId) const
{
  return mImpl->validate(iUserId);
}

bool CoffeeTransact::validateAndPerform(const char *iUserId) const
{
  return mImpl->validateAndPerform(iUserId);
}
