# Queries

MySQL Queries
```php
<?php
use Letid\Request\Database;
Database::load("SELECT id FROM tables WHERE name LIKE 'Jo%'")->toArray()->hasCount();
Database::load("SELECT SQL_CALC_FOUND_ROWS id,name FROM tables WHERE name LIKE 'Jo%' LIMIT 3, 1;")->rowsTotal()->rowsCount()->toArray();
Database::load("UPDATE tables SET name='Kent2s' WHERE id ='0000000003';")->rowsCount();
// class Database extends Query
```

```php
<?php
Database::load("SELECT SQL_CALC_FOUND_ROWS id,name FROM tables WHERE name LIKE 'Jo%' LIMIT 3, 1;")->rowsTotal()->rowsCount()->toArray();
```
> Note: in order to work 'rowsTotal()' -> 'SQL_CALC_FOUND_ROWS' must be add in select query.
