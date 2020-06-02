<?php

use Dainsys\Timy\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimyRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timy_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        if (!Schema::hasColumn('users', 'timy_role')) {
            Schema::table('users', function ($table) {
                $table->foreignId('timy_role_id')
                    ->nullable()
                    ->after('password')
                    ->references('id')
                    ->on('timy_roles');
            });
        }

        Role::insert([
            ['name' => config('timy.roles.admin')],
            ['name' => config('timy.roles.user')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'timy_role_id')) {
            Schema::table('users', function ($table) {
                $table->dropForeign('users_timy_role_id_foreign');
                $table->dropColumn('timy_role_id');
            });
        }
        Schema::dropIfExists('timy_roles');
    }
}
