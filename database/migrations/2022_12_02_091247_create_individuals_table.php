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
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id')->unsigned()->nullable()->default(null);
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reference_number')->nullable()->default(null);
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('SSN')->nullable()->default(null);
            $table->date('date_of_birth')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->string('home_phone')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('language')->nullable()->default(null);
            $table->string('original_profile_id')->nullable()->default(null);
            $table->longText('lead_data')->nullable()->default(null);
            $table->bigInteger('address_id')->unsigned()->nullable()->default(null);
            $table->foreign('address_id')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->unsignedInteger('time_taken')->default(0);
            $table->bigInteger('individual_follow_up_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('salesman_booking_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('first_action_by')->unsigned()->nullable();
            $table->foreign('first_action_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->bigInteger('last_action_by')->unsigned()->nullable();
            $table->foreign('last_action_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individuals');
    }
};
