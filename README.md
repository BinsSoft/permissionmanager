# Laravel Permission Management

A permission management system to handel the user role in a [Laravel 5](http://www.laravel.com) project.

  

## Installation

This package is only installed via [composer](http://getcomposer.org) by requiring the 

`composer require binssoft/permissionmanager`

 package in your project's `composer.json`. (The installation may take a while.)

  

```json

{
  "require": {
     "binssoft/permissionmanager": "<version-name>"
  }
}

```

  

Next, add the service provider to `config/app.php`.

```php
'providers' => [
 ...
    Binssoft\Permissionmanager\PermissionManagerServiceProvider::class
]
```
```php
'aliases' => [
...
 'Permission'=> Binssoft\Permissionmanager\PermissionManager::class
 ]
```

 

## Configuration
```
php artisan vendor:publish
```
```
php artisan migrate
```

After proper vendor publish few files are created and 4 tables will be created in the configured database after migration. 

1) in `app/` folder 4 models will generated `Roles.php`, `Navigations.php`, `UserRoles.php`, `RolePermissions.php`

2) in `database\seeds` folder `RoleSeeder.php` will be created

```
php artisan db:seed --class=RoleSeeder
```
Few demo role records will be inserted in the `roles` Table
  

#### Now your permission library is configured perfectly.
 
## How to Uses
```
use Permission;
```
### Assign user Role
```
Permission::assignUserRole(<user_id>,<role_id>);
```
### Get user Role

```
Permission::getUserRole(<user_id>);
```
### Get all navigations
```
Permission::navigations(['namespace'=>["admin"]]); 
```
namespace will be a array with value of your different namespaces like admin,site etc.

### Set role Permission

 - 1st parameter : role id
 - 2nd parameter : route name
 - 3rd parameter : want to save or delete (true/false)

```
Permission::setRolePermission(1, 'admin_user_list', true); 
```

### Check current route access permission
```
$permission = Permission::access(<role_id>);
```
`$permission` will return `true` or `flase`

#### That's it!  You're good to go.

Please click on "Star" in github if it is usefull for you.

