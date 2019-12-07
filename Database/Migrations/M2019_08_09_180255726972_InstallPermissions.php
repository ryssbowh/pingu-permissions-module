<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2019_08_09_180255726972_InstallPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('helper')->nullable();
                $table->string('guard');
                $table->string('section');
                $table->timestamps();
            }
        );

        Schema::create(
            'permission_role', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->unsigned()->index();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->integer('permission_id')->unsigned()->index();
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
}
