[English](README.md) | 中文

# Laravel RBAC

这个拓展包实现了RBAC（Role-Based Access Control）模型，可帮助你管理权限和角色。

## 安装

使用Composer安装到你的项目中去：

```shell
composer require huang-yi/laravel-rbac
```

接下来用`vendor:publish`命令发布迁移和配置文件，分别在`database/migrations`和`config`目录下：

```shell
php artisan vendor:publish --provider="HuangYi\Rbac\RbacServiceProvider"
```

最后，执行数据库迁移命令即可：

```shell
php artisan migrate
```

## 配置

- **user**: 用户Model类名
- **database**:
  - **connection**: RBAC数据表使用的数据库连接
  - **prefix**: RBAC数据表的公共前缀
- **cache**: 缓存开关

## 使用

你的用户模型必须配置到`rbac.user`配置项中去，并且你的用户模型必须implement `HuangYi\Rbac\Contracts\Authorizable` interface，并use `HuangYi\Rbac\Concerns\Authorizable` trait。

```php
namespace App;

use HuangYi\Rbac\Concerns\Authorizable;
use HuangYi\Rbac\Contracts\Authorizable as AuthorizableContract;

class User extends Authenticatable implement AuthorizableContract
{
    use Authorizable, Notifiable;
}
```

### 权限和角色的增删改查

权限Model是`HuangYi\Rbac\Permission`，角色Model是`HuangYi\Rbac\Role`，权限和角色的增删改查使用Model操作就行。两个Model都提供了静态方法`make`来快速创建模型并保存到数据库。

```php
use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;

Permission::make('edit post');

Role::make('author');
```

### 给角色分配权限

```php
$role->attachPermissions($permissions);

$role->detachPermissions($permissions);

$role->syncPermissions($permissions);
```

上面的方法其实就是对`belongsToMany`中`attach`、`detach`、`sync`方法的封装，所以传参也可以和这三个方法一致，但是分装的方法会操作缓存，如果启用了缓存的话，请务必使用上述方法进行操作。

### 给用户分配角色

```php
$user->attachRoles($roles);

$user->detachRoles($roles);

$user->syncRoles($roles);
```

### 给用户直接分配权限

```php
$user->attachPermissions($permissions);

$user->detachPermissions($permissions);

$user->syncPermissions($permissions);
```

### 判断用户是否拥有角色

```php
// 判断用户是否拥有角色
$user->hasRole('author');

// 判断用户是否拥有多个角色
$user->hasRoles(['author', 'personnel manager']);

// 判断用户是否拥有任意一个角色
$user->hasAnyRoles(['author', 'personnel manager']);
```

### 判断用户是否拥有权限

```php
// 判断用户是否拥有权限
$user->hasPermission('create post');

// 判断用户是否拥有多个权限
$user->hasPermissions(['create post', 'edit post']);

// 判断用户是否拥有任意一个权限
$user->hasAnyPermissions(['create post', 'edit post']);

// 你也可以使用Laravel的can方法来判断权限，多个角色可用“|”间隔，和hasAnyPermissions一样
$user->can('edit post|edit post');
```

## 超级管理员

你必须先使用`Rbac::checkSuperAdminUsing()`方法注册一个判断超级管理员的方法，在`App\Providers\AuthServiceProvider`的`boot`方法中定义即可：

```php
namespace App\Providers;

use HuangYi\Rbac\Rbac;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Rbac::checkSuperAdminUsing(function ($user) {
            // 此处需要修改为你自己的逻辑
            return in_array($user->email, ['admin@example.com']);
        });
    }
}
```

## 中间件

如果你的路由需要控制权限的话，可以使用`role`和`permission`中间件来控制：

```php
// 角色中间件：多个角色名可以使用“|”间隔，只要拥有任意一个角色即可
Route::get('admin/staffs', [StaffController::class, 'index'])->middleware('role:personnel manager|vice president');

// 权限中间件：多个权限名可以使用“|”间隔，只要拥有任意一个权限即可
Route::post('post/{post}', [PostController::class, 'update'])->middleware('permission:create post|edit post');

// 与permission中间件一样
Route::post('post/{post}', [PostController::class, 'update'])->middleware('can:create post|edit post');
```

## 视图指令

角色相关的指令：

- `@role`、`@elserole`、`@endrole`即对`hasRole`的封装
- `@roles`、`@elseroles`、`@endroles`即对`hasRoles`的封装
- `@anyroles`、`@elseanyroles`、`@endanyroles`即对`hasAnyRoles`的封装

权限相关的指令：

- `@permission`、`@elsepermission`、`@endpermission`即对`hasPermission`的封装
- `@permissions`、`@elsepermissions`、`@endpermissions`即对`hasPermissions`的封装
- `@anypermissions`、`@elseanypermissions`、`@endanypermissions`即对`hasAnyPermissions`的封装

举例：

```php
@anypermissions('create post|edit post')
    拥有"create post"或"edit post"权限
@elseanypermissions('create comment|edit comment')
    拥有"create commen"或"edit comment"权限
@endanypermissions
```

## 单元测试

```shell
composer test
```

## License

该拓展包遵循[MIT开源协议](LICENSE)。
