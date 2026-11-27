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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location');
            $table->enum('registration_status', ['Buka', 'Tutup'])->default('Buka');
            $table->date('schedule')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->integer('volunteer_quota')->nullable();
            $table->string('form_url')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('document_path')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
