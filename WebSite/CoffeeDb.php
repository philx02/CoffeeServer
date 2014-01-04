<?php
class CoffeeDb extends SQLite3
{
  function __construct()
  {
    $this->open('../database/coffee.db', SQLITE3_OPEN_READWRITE);
    $this->exec("PRAGMA foreign_keys = ON");
  }
}
?> 
