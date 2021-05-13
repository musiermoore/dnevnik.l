<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->unsignedBigInteger('lesson_numbers_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('weekday_id');
            $table->date('date');

            $table->foreign('subject_id')->references('id')->on('subjects')
                ->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('lesson_numbers_id')->references('id')->on('lesson_numbers')
                ->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->foreign('weekday_id')->references('id')->on('weekdays')
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
        Schema::dropIfExists('timetables');
    }
}
