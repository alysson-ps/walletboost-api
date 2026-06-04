<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recurrences', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('user_id')
                ->constrained('accounts')->noActionOnDelete();
            $table->foreignId('category_id')->nullable()->after('account_id')
                ->constrained('categories')->noActionOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recurrences', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['account_id', 'category_id']);
        });
    }
};
