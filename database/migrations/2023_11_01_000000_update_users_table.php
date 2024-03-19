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
        Schema::table('users', function (Blueprint $table) {
           
            $table->unsignedBigInteger('role_id')->index()->nullable();
            $table->unsignedBigInteger('country_id')->index()->nullable();
            $table->unsignedBigInteger('range_id')->index()->nullable();
            $table->string('twich_id')->unique()->nullable();
            $table->boolean('status')->default(false);
            $table->string('channel')->nullable();
            $table->string('area')->nullable();
            $table->string('phone')->nullable();
            $table->string('time_zone')->nullable();
            $table->integer('hours_buyed')->nullable();
            $table->string('img_profile')->nullable();
            $table->integer('points_support')->nullable();
            $table->string('token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->boolean('deleted')->default(false);
            $table->string('user_action')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('range_id')->references('id')->on('ranges')->onDelete('cascade');
            
            // $table->string('name');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('password');
            // $table->rememberToken();
            // $table->foreignId('current_team_id')->nullable();
            // $table->string('profile_photo_path', 2048)->nullable();
            // $table->timestamps();        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
