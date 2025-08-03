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

        Schema::create('record_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('records_type_id')->nullable()->constrained('record_types')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('restrict')->onUpdate('restrict');
            $table->string('name')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('visit_date')->nullable();
            $table->dateTime('next_visit_date')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
        Schema::dropIfExists('record_types');
    }
};
