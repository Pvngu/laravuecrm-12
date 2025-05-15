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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('individual_id')->unsigned();
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            $table->bigInteger('sale_status_id')->unsigned()->nullable()->default(1);
            $table->foreign('sale_status_id')->references('id')->on('sale_statuses')->onUpdate('cascade')->onDelete('set null');
            $table->bigInteger('assigned_to')->unsigned()->nullable()->default(null);
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
