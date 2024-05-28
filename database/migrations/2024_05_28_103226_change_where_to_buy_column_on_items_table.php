<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SQL分で->nullable()に変更
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            DB::statement('ALTER TABLE items MODIFY where_to_buy VARCHAR(255) NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            DB::statement('ALTER TABLE items MODIFY where_to_buy VARCHAR(255) NOT NULL');
        });
    }
};
