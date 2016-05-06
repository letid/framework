# :collision: Todo
---
## [Database](queries.md)
- Available
    - [x] MySQLi
    - [ ] MySQLite

- Connection
    - [x] MySQLi
    - [ ] MySQLite
    - [ ] PDO

- Extendable
    - [x] Basic
    - [ ] Table
    - [ ] Index
    - [ ] Validate

- Query
    - [x] `query()`
    - [x] `insert()`
    - [x] `select()`
    - [x] `update()`
    - [x] `delete()`

- Callable :wavy_dash:
    - [x] `->rowsCalc()`
    - [x] `->from()`
    - [x] `->where()`
        - [x] features (OR,AND)
            - speed improved
    - [x] `->group_by()`
    - [x] `->order_by()`
    - [x] `->limit()`
    - [x] `->offset()`

## [Form](form.md)
`Form::name()->setting()->response()`
- Name :wavy_dash:
- Setting :wavy_dash:
- Response :wavy_dash:
    - [x] `->response()`
    - [x] `->insert()`
    - [ ] `->select()`
    - [ ] `->update()`
    - [ ] `->delete()`


## Language
`$this->lang()`
- Method :wavy_dash:
    - [x] `->lang()`
- Initiation
    - [ ] Manager need to be improved

## [Template](template.md)
`$this->template()`
- Method :wavy_dash:
    - [x] `->template()`

## [Html](html.md)
`$this->html()`
- Method :wavy_dash:
    - [x] `->html()`
        - available in both `Request\Html` and `$this->html()`
    - [ ] `new html(tag:string, text:string, attr:array)`

## [Menu](menu.md)
`$this->menu()`
- Method
    - [x] `->menu()`
        - Child active needs to improve..

## Authorization
`$this->authorization()`
- Method :wavy_dash:
    - [x] `->authorization()`
        - but need to improved

## :dizzy: Core!
- [ ] Application is live or deploy!
- [x] trait Initiate
    - `self::$CoreVar` -> moved to `Config::` and object
- [ ] None Class/Method exists
    - error message (disable InitiateError on live application)
---
>> emoji **icon indicate**

> Date/Time :date:

> Callable child Method :wavy_dash:

> Core :dizzy:

> Todo :collision:

> Need to be improved/fixed :hammer:

> Stop :zzz:

> New :bulb:

> Note :pushpin:

> Updated/Improved/Fixed :seedling:

> Version/Target :triangular_flag_on_post: