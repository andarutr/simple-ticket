<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('title', 128);
            $table->text('description');
            $table->integer('priority')->comment('1 = Low, 2 = Medium, 3 = High');
            $table->date('deadline');
            $table->integer('status')->comment('1 = Todo, 2 = Doing, 3 = Review, 4 = Done');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
