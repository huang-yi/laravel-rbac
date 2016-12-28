# 关于Rbac-Laravel

Rbac-Laravel是一个基于Laravel框架的RBAC拓展包，[RBAC](https://zh.wikipedia.org/wiki/%E4%BB%A5%E8%A7%92%E8%89%B2%E7%82%BA%E5%9F%BA%E7%A4%8E%E7%9A%84%E5%AD%98%E5%8F%96%E6%8E%A7%E5%88%B6)（Role-Based Access Control）是指基于角色的访问控制。该拓展包为Laravel框架提供了RBAC模型的实现，并且提供了诸多操作RBAC的便捷方法。

## 版本信息

 Rbac  | Laravel | PHP     
:------|:--------|:--------
 1.0.x | 5.3.*   | >=5.6.4 

## 安装方法

使用composer来快速安装拓展包：

```
$ composer require huang-yi/rbac-laravel:1.0.*
```

或者编辑项目根目录的`composer.json`文件，在`require`属性里面添加一项：

```php
{
    "require": {
        "huang-yi/rbac-laravel": "1.0.*"
    }
}
```

然后执行`composer update`。

## 配置信息

首先往Laravel应用中注册ServiceProvider，打开文件`config/app.php`，在`providers`中添加一项：

```php
[
    'providers' => [
        HuangYi\Rbac\RbacServiceProvider::class,
    ]
]
```

然后发布拓展包的配置文件，使用如下命令：

```
$ php artisan vendor:publish
```

这时候`config/`目录下会出现`rbac.php`文件，该配置文件中有两个配置选项：

第一个为`connection`属性，用于配置RBAC模块使用的数据库连接名。

第二个为`user`属性，用于配置Laravel应用中使用的用户模型。默认为`App\User`。

上述配置文件中所配置的user模型类必须`use HuangYi\Rbac\RbacTrait`：

```php
namespace App;

use HuangYi\Rbac\RbacTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use RbacTrait;
    
}
```

## 数据迁移

执行以下命令创建拓展包必须依赖的数据库表：

```
$ php artisan migrate
```

## 使用方法

### Permission

1、权限有三个属性：`name`，`slug`，`description`。

2、创建一个权限：`HuangYi\Rbac\Managers\PermissionManager::create(array $attributes)`

```php
$permissionManager = new \HuangYi\Rbac\Managers\PermissionManager();

$permission = $permissionManager->create([
    'name' => 'Create product',
    'slug' => 'product.create',
    'description' => 'Create a new product.',
]);
```

3、删除一个权限：`HuangYi\Rbac\Managers\PermissionManager::delete($ID)`

```php
$permissionManager = new \HuangYi\Rbac\Managers\PermissionManager();

$deleted = $permissionManager->delete(1);
```

删除权限时会自动解绑已绑定的角色。

4、更新一个权限：`HuangYi\Rbac\Managers\PermissionManager::update($ID, array $attributes)`

```php
$permissionManager = new \HuangYi\Rbac\Managers\PermissionManager();

$updated = $permissionManager->update(1, [
    'description' => 'Blabla...',
]);
```

5、查询一个权限：`HuangYi\Rbac\Managers\PermissionManager::find($ID)`

```php
$permissionManager = new \HuangYi\Rbac\Managers\PermissionManager();

$permission = $permissionManager->find(1);
```

### Role

1、角色有三个属性：`name`，`slug`，`description`。

2、创建一个角色：`HuangYi\Rbac\Managers\RoleManager::create(array $attributes)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

$role = $roleManager->create([
    'name' => 'Administrator',
    'slug' => 'admin',
    'description' => 'Can do anything.',
]);
```

3、删除一个角色：`HuangYi\Rbac\Managers\RoleManager::delete($ID)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

$deleted = $roleManager->delete(1);
```

删除角色时会自动解绑已绑定的用户和权限。

4、更新一个角色：`HuangYi\Rbac\Managers\RoleManager::update($ID, array $attributes)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

$updated = $roleManager->update(1, [
    'description' => 'Blabla...',
]);
```

5、查询一个角色：`HuangYi\Rbac\Managers\RoleManager::find($ID)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

$role = $roleManager->find(1);
```

6、为角色绑定权限：`HuangYi\Rbac\Managers\RoleManager::attachPermissions($permissionIDs)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

// 绑定一个权限
$roleManager->attachPermissions(1);

// 同时绑定多个权限
$roleManager->attachPermissions([1, 2, 3]);
```

7、为角色解绑权限：`HuangYi\Rbac\Managers\RoleManager::detachPermissions($permissionIDs)`

```php
$roleManager = new \HuangYi\Rbac\Managers\RoleManager();

// 解绑一个权限
$roleManager->detachPermissions(1);

// 同时绑定多个权限
$roleManager->detachPermissions([1, 2, 3]);
```

### User

1、为用户绑定角色：`HuangYi\Rbac\RbacTrait::attachRoles($roleIDs)`

```php
$user = \App\User::find(1);

// 绑定一个角色
$user->attachRoles(1);

// 同时绑定多个角色
$user->attachRoles([1, 2, 3]);
```

2、为用户解绑角色：`HuangYi\Rbac\RbacTrait::detachRoles($roleIDs)`

```php
$user = \App\User::find(1);

// 解绑一个角色
$user->detachRoles(1);

// 同时解绑多个角色
$user->detachRoles([1, 2, 3]);
```

3、判断用户是否为某些角色，若需要判断多个角色请使用`|`间隔：`HuangYi\Rbac\RbacTrait::hasRole($roles)`

```php
$user = \App\User::find(1);

// 判断一个角色
$user->hasRole('admin');

// 判断多个角色
$user->hasRole('seller|operator');
```

4、判断用户是否拥有某些权限，若需要判断多个权限请使用`|`间隔：`HuangYi\Rbac\RbacTrait::hasPermission($permissions)`

```php
$user = \App\User::find(1);

// 判断一个权限
$user->hasPermission('product.create');

// 判断多个权限
$user->hasPermission('product.create|product.update');
```

### Middleware

Rbac-Laravel为开发人员提供了便利的Middleware，如果需要使用请将`HuangYi\Rbac\RbacMiddleware`注入到`app/Http/Kernel.php`中的路由中间件里：

```php
protected $routeMiddleware = [
    'rbac' => \HuangYi\Rbac\RbacMiddleware::class,
];
```

配置好后即可在路由中使用：

```php
Route::get('/do/something', [
    'uses' => 'SomeController@action',
    'middleware' => 'rbac:role,roleSlug1|roleSlug2',
]);

Route::get('/do/something', [
    'uses' => 'SomeController@action',
    'middleware' => 'rbac:permission,permissionSlug1|permissionSlug2',
]);
```

### 在视图中使用

```php
@ifHasRole('roleSlug1|roleSlug2')
<p>You can see this.</p>
@endIfHasRole

@ifHasPermission('permission1|permission2')
<p>You can see this too.</p>
@endIfHasPermission
```

## 支持

Bugs和问题可提交至[Github](https://github.com/huang-yi/rbac-laravel)，或者请联系作者黄毅（[coodeer@163.com](mailto:coodeer@163.com)）

## License

The Rbac-Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
