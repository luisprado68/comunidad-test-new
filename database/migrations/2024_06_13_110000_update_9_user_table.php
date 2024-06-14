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
            $table->dropUnique(['twich_id']);
            $table->renameColumn('twich_id', 'stream_id');
            $table->unique(['stream_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropUnique(['stream_id', 'platform_id']);
            $table->renameColumn('stream_id', 'twich_id');
            $table->unique('twich_id');
        });
    }
};
