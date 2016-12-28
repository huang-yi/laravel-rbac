<?php
/**
 * File: TestCase.php
 * Date: 2016/12/27
 * Time: 上午10:54
 */

namespace HuangYi\Rbac\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations/'));
        $this->migrateUserTable();

        $this->withFactories(__DIR__.'/factories');
    }

    /**
     * Migration user table.
     */
    protected function migrateUserTable()
    {
        Schema::create('rbac_test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $this->beforeApplicationDestroyed(function () {
            Schema::dropIfExists('rbac_test_users');
        });
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['HuangYi\Rbac\RbacServiceProvider'];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('rbac.connection', 'testing');
        $app['config']->set('rbac.user', User::class);
    }

}
