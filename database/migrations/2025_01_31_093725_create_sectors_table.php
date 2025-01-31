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
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('parent_id')->nullable()->index(); // Kolom sector dengan tipe unsignedBigInteger

            // Menambahkan foreign key constraints
            $table->foreign('parent_id')->references('id')->on('sectors')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};