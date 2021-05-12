<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('father', 100);
            $table->integer('age');
            $table->string('mobile', 10);
            $table->date('date_of_discharge')->nullable()->default(NULL);
            $table->string('narration');
            $table->unsignedBigInteger('bed_id');
            $table->unsignedBigInteger('ward_id');
            $table->foreign('bed_id')->references('id')->on('beds');
            $table->foreign('ward_id')->references('id')->on('wards');
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
        Schema::dropIfExists('patients');
    }
}
