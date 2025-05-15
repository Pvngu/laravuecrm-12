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
        Schema::create('co_applicants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('individual_id')->unsigned();
            $table->foreign('individual_id')->references('id')->on('individuals')->onUpdate('cascade')->onDelete('cascade');
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('SSN')->nullable()->default(null);
            $table->date('date_of_birth')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->string('home_phone')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('language')->nullable()->default(null);
            $table->bigInteger('address_id')->unsigned()->nullable()->default(null);
            $table->foreign('address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('co_applicants');
    }
};
