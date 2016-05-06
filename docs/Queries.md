# Queries

MySQL Queries

uniqid(); NOW(); date('Y-m-d G:i:s');
```php
<?php
namespace App\Pages;
use Letid\Request\Database;
class query_test extends \App\Page
{
    function query()
    {
        Database::query("SELECT id FROM tableName WHERE username LIKE 'Jo%'")->execute()->toArray()->rowsCount();
        Database::query("SELECT SQL_CALC_FOUND_ROWS id FROM tableName WHERE username LIKE 'K%' LIMIT 3, 1;")->execute()->toArray()->rowsTotal()->rowsCount();
        Database::query("UPDATE tableName SET username='Khen' WHERE id ='0000000003';")->execute()->rowsAffected();
        Database::query("INSERT INTO tableName SET username='David';")->execute()->rowsId();
    }
    function select()
    {
        Database::select(
        		'column'
        	)
        	->rowsCalc()
        	->from(
        		'tableName'
        	)
        	->where(
        		'id',1
                // 'id','>', '1' -> WHERE WHERE id > '1'
                // 'username','K%' -> WHERE username LIKE 'K%'
        	)
        	->group_by('username')
        	->order_by('username DESC')
        	->limit(
                12
                // 10, 17
            )
        	->offset(18)

        	->build()
        	->execute()

        	->toArray()
        	->rowsCount()
        	->rowsTotal();
    }
    function delete()
    {
        // DROP DATABASE
        Database::drop('database')
        	->from('databaseName')
            ->execute();

        // DROP TABLE
        Database::drop('table')
        	->from('tableName')
            ->execute();

        // DROP INDEX
        Database::drop('index')
        	->from('indexName')
        	->alter('tableName')
            ->execute();

        // TRUNCATE TABLE
        Database::truncate('TABLE')
        	->from('tableName')
            ->execute();

        // DELETE ROW
        Database::delete()
        	->from('tableName')
            ->where(
        		'id',1
        	)
            ->execute();
    }
    function insert()
    {
        Database::insert(
        		array('username'=>1, 'password'=>2)
        	)
        	->to(
        		'tableName'
        	)
        	->build();
        // HACK: INSERT INTO tableName SET username = '1', modified = '2';

        Database::insert(
        		array('username', 'password')
        	)
        	->value(
        		array(1,2),
        		array(3,4)
        	)
        	->to(
        		'tableName'
        	)
        	->build();
        // HACK: INSERT INTO tableName (username,password) VALUES ('1','2'), ('3','4');

        Database::insert(
        		'username', 'password'
        	)
        	->value(
        		array(1,2),
        		array(3,4)
        	)
        	->to(
        		'tableName'
        	)
        	->execute()
        	->rowsId();
        // HACK: INSERT INTO tableName (username,password) VALUES ('1','2'), ('3','4');
    }
    function update()
    {
        Database::update(
        		array('username'=>1, 'password'=>2)
        	)
        	->to(
        		'tableName'
        	)
        	->where(
        		'id',1
        	)
        	->build();
        // HACK: UPDATE tableName SET username = '1', modified = '2' WHERE id = '1';

        Database::update(
        		array('username', 'password')
        		// SAME AS ABOVE: 'username', 'password'
        	)
        	->value(
        		array(1,2),
        		array(3,4)
        	)
        	->to(
        		'tableName'
        	)
        	->execute()
        	->rowsAffected();
        // HACK: UPDATE tableName (username,password) VALUES ('1','2'), ('3','4');
    }
}
```
## rowsCalc()
```php
<?php
Database::query("SELECT SQL_CALC_FOUND_ROWS id
        FROM tableName
            WHERE username LIKE 'K%'
                LIMIT 3, 1;")->execute()->rowsTotal();
Database::select(*)
    ->rowsCalc()
    ->execute()
    ->rowsTotal();
```
> in order to get rowsTotal() -> SQL_CALC_FOUND_ROWS in query or rowsCalc() in select() needed to provide.

## where()
```php
<?php
where(
    'id','1%'
)
// SAME
where(
    array('id','1%')
)
// WHERE id LIKE '1%'
```
#### advanced features using (OR, AND)
```php
<?php
where(
    array('id',1),
    array(
        array('a1','1%'),
        array('a2','1%'),
        array('a3','>','1')
    )
)
// WHERE id = '1' AND (a1 LIKE '1%' AND a2 LIKE '1%' a3 > '1')
```
> AND is auto added;

```php
<?php
where(
    array(
        array('a','>','1'),
        'or',
        array('b','n%'),
    ),
    'or',
    array(
        array('c','1'),
        'or',
        array('d','1')
    ),
    'and',
    array('e','1')
)
// WHERE (a > '1' OR b LIKE 'n%') OR (c = '1' OR d = '1') AND e = '1';
```
### NOTE

> Note: in order to work 'rowsTotal()' -> 'SQL_CALC_FOUND_ROWS' must be add in select query. uniqid(); NOW(); date('Y-m-d G:i:s');
