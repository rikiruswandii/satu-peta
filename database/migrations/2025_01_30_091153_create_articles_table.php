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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // Kolom enhancer dengan tipe unsignedBigInteger
            $table->unsignedBigInteger('category_id')->index(); // Kolom category dengan tipe unsignedBigInteger
            $table->string('title', 255); // Kolom title dengan panjang maksimum 255 karakter
            $table->text('content'); // Kolom content dengan tipe text
            $table->integer('view')->unsigned()->default(0); // Kolom view dengan tipe unsignedBigInteger dan default 0
            $table->string('slug', 255);
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft deletes

            // Menambahkan foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};