English | [中文](README-cn.md)

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

## Configuration

- **user**: The user model class you are using.
- **database**:
  - **connection**: The database connection for RBAC tables.
  - **prefix**: The common prefix for RBAC tables.
- **cache**: The cache switch.

## Usage

Your User model must be configured to `rbac.user` option. It should implement the `HuangYi\Rbac\Contracts\Authorizable` interface and use the `HuangYi\Rbac\Concerns\Authorizable` trait.

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

Permission::make('edit post');
```

Store a role to database:

```php
use HuangYi\Rbac\Role;

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

// this is similar to hasAnyPermissions
$user->can('edit post|edit post');
```

## Super Admin

You may register a callback for determining if the user is a super admin by using `Rbac::checkSuperAdminUsing()` method:

```php
namespace App\Providers;

use HuangYi\Rbac\Rbac;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Rbac::checkSuperAdminUsing(function ($user) {
            return in_array($user->email, ['admin@example.com']);
        });
    }
}
```

## Middleware

```php
// role middleware
Route::get('admin/staffs', [StaffController::class, 'index'])->middleware('role:personnel manager|vice president');

// permission middleware
Route::post('post/{post}', [PostController::class, 'update'])->middleware('permission:create post|edit post');

// this is similar to 'permission' middleware
Route::post('post/{post}', [PostController::class, 'update'])->middleware('can:create post|edit post');
```

## Blade Directives

Role directives:

```php
@role('author')
    <!-- The current user is an author -->
@elserole('personnel manager')
    <!-- The current user is a personnel manager -->
@endrole
```

Permission directives (or can directives):

```php
@permission('create post')
    <!-- The current user has permission to create post -->
@elsepermission('edit post')
    <!-- The current user has permission to edit post -->
@endpermission
```

## Tests

```shell
composer test
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
