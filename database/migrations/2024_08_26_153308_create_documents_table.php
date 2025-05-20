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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('individual_id')->unsigned()->nullable()->default(null);
            $table->foreign('individual_id')->references('id')->on('individuals')->onUpdate('cascade')->onDelete('cascade'); 
            $table->string('name');
            $table->string('file');
            $table->string('file_type')->nullable()->default(null);
            $table->bigInteger('file_size')->unsigned()->nullable()->default(null);
            $table->bigInteger('created_by_id')->unsigned()->nullable()->default(null);
            $table->foreign('created_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('updated_by_id')->unsigned()->nullable()->default(null);
            $table->foreign('updated_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('company_id')->unsigned()->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
