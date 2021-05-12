<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->unsignedBigInteger('designation_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('department_id')->nullable()->default(NULL);
            $table->boolean('is_admin')->default(FALSE);

            $table->foreign('designation_id')->references('id')->on('designations');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->string('mobile', 10)->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
