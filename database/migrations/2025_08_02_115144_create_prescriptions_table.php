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
        Schema::create('prescription_intake_times', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('prescription_intake_time_id')->constrained('prescription_intake_times')->onDelete('restrict')->onUpdate('restrict');
            $table->text('note')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('prescriptions_intake_times');
    }
};
