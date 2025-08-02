<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('restrict')->onUpdate('restrict');
            $table->string('name');
            $table->string('color');
            $table->timestamps();
        });

        Schema::create('user_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });

        Schema::create('record_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('records')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('user_tags');
        Schema::dropIfExists('record_tags');
    }
};
