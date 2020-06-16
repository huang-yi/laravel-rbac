# Laravel RBAC

This package helps you to manage permissions and roles.

## Installation

You may install this package via Composer:

```shell
composer require huang-yi/laravel-rbac
```

Next, you should publish configuration and migration files using the `vendor:publish` Artisan command:

```shell
php artisan vendor:publish --provider="HuangYi\Rbac\RbacServiceProvider"
```

Finally, you should run your database migrations:

```shell
php artisan migrate
```

## Usage

Your User model should implement the `HuangYi\Rbac\Contracts\Authorizable` interface and use the `HuangYi\Rbac\Concerns\Authorizable` trait.

```php
namespace App;

use HuangYi\Rbac\Concerns\Authorizable;
use HuangYi\Rbac\Contracts\Authorizable as AuthorizableContract;

class User extends Authenticatable implement AuthorizableContract
{
    use Authorizable, Notifiable;
}
```

Store a permission to database:

```php
use HuangYi\Rbac\Permission;

Permission::create(['name' => 'create post']);

Permission::make('edit post');
```

Store a role to database:

```php
use HuangYi\Rbac\Role;

Role::create(['name' => 'author']);

Permission::make('personnel manager');
```

Attach or detach permissions to role:

```php
$role->attachPermissions($permissions);

$role->detachPermissions($permissions);

$role->syncPermissions($permissions);
```

Attach or detach roles to user:

```php
$user->attachRoles($roles);

$user->detachRoles($roles);

$user->syncRoles($roles);
```

Attach or detach permissions to user:

```php
$user->attachPermissions($permissions);

$user->detachPermissions($permissions);

$user->syncPermissions($permissions);
```

Determine if the user has roles:

```php
$user->hasRole('author');

$user->hasRoles(['author', 'personnel manager']);

$user->hasAnyRoles(['author', 'personnel manager']);
```

Determine if the user has permissions:

```php
$user->hasPermission('create post');

$user->hasPermissions(['create post', 'edit post']);

$user->hasAnyPermissions(['create post', 'edit post']);

$user->can('edit post');

// This is similar to hasAnyPermissions
$user->can('edit post|edit post');
```

## Tests

```shell
composer test
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
