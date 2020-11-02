<?php

use Dainsys\Timy\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimyTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timy_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        if (!Schema::hasColumn('users', 'timy_team')) {
            Schema::table('users', function ($table) {
                $table->foreignId('timy_team_id')
                    ->nullable()
                    ->after('password')
                    ->references('id')
                    ->on('timy_teams');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'timy_team_id')) {
            Schema::table('users', function ($table) {
                $table->dropForeign('users_timy_team_id_foreign');
                $table->dropColumn('timy_team_id');
            });
        }
        Schema::dropIfExists('timy_teams');
    }
}
