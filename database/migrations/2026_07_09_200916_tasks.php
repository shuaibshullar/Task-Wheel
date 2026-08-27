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
        Schema::create('Tasks', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique()->index();
            $t->text('description')->nullable();
            $t->integer('category_id', unsigned: true)->nullable();
            $t->date('deadline');
            $t->text('assigned_personnel_id')->nullable();
        });

        Schema::create('Categories', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique()->index();
            $t->string('color');
            $t->integer('radius', unsigned: true)->nullable();
        });

//        Schema::create('Assigned_personnel_ids', function (Blueprint $t) {
//            $t->id();
//            $t->string('name')->unique()->index();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tasks');
        Schema::dropIfExists('Categorys');
        Schema::dropIfExists('Assigned_personnel_ids');
    }
};
