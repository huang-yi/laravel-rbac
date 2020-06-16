<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRbacTable extends Migration
{
    /**
     * The database schema.
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schema = Schema::connection($this->getConnection());
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create($this->table('permissions'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        $this->schema->create($this->table('roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        $this->schema->create($this->table('permission_role'), function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->unique(['permission_id', 'role_id']);

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table('permissions'))
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($this->table('roles'))
                ->onDelete('cascade');
        });

        $this->schema->create($this->table('role_user'), function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->unique(['role_id', 'user_id']);

            $table->foreign('role_id')
                ->references('id')
                ->on($this->table('roles'))
                ->onDelete('cascade');
        });

        $this->schema->create($this->table('permission_user'), function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('user_id');

            $table->unique(['permission_id', 'user_id']);

            $table->foreign('permission_id')
                ->references('id')
                ->on($this->table('permissions'))
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists($this->table('permission_user'));
        $this->schema->dropIfExists($this->table('role_user'));
        $this->schema->dropIfExists($this->table('permission_role'));
        $this->schema->dropIfExists($this->table('roles'));
        $this->schema->dropIfExists($this->table('permissions'));
    }

    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return config('rbac.database.connection');
    }

    /**
     * Format the table name.
     *
     * @param  string  $table
     * @return string
     */
    public function table($table)
    {
        return config('rbac.database.prefix').$table;
    }
}
