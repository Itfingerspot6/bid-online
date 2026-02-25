<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('draft','active','closed','cancelled','ended') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE auctions MODIFY COLUMN status ENUM('draft','active','closed','cancelled') NOT NULL DEFAULT 'draft'");
    }
};