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
        if (!Schema::hasColumn('stream_support', 'minutes')) {
            Schema::table('stream_support', function (Blueprint $table) {
            
                $table->integer('minutes')->nullable();
               
            });
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stream_support', function (Blueprint $table) {

            $table->dropColumn('minutes');
        });
    }
};
