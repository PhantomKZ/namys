<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'model_code')) {
                $table->string('model_code', 100)->nullable()->after('name');
                $table->index('model_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'model_code')) {
                $table->dropIndex(['model_code']);
                $table->dropColumn('model_code');
            }
        });
    }
};
