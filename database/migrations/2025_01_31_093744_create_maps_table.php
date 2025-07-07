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
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('regional_agency_id')->index(); // Kolom regional_agency dengan tipe unsignedBigInteger
            $table->string('name', length: 80);
            $table->string('slug', length: 80);
            $table->boolean('is_active')->default(false); // Kolom untuk menunjukkan apakah layer aktif atau tidak
            $table->boolean('can_download')->default(false); // Kolom untuk izin download, hanya berlaku untuk maps
            $table->timestamps();
            $table->softDeletes(); // Kolom deleted_at untuk soft deletes

            // Menambahkan foreign key constraints
            $table->foreign('regional_agency_id')->references('id')->on('regional_agencies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
