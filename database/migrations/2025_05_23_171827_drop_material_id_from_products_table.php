<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Удаляем колонку material_id */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // если раньше был внешний ключ, сначала убираем его
            if (Schema::hasColumn('products', 'material_id')) {
                $table->dropForeign(['material_id']);   // имя fk_Laravel по умолчанию
                $table->dropColumn('material_id');
            }
        });
    }

    /** Откат: возвращаем колонку material_id как unsignedBigInteger + FK */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'material_id')) {
                $table->unsignedBigInteger('material_id')->nullable()->after('type_id');

                // если таблица materials существует — восстанавливаем FK
                if (Schema::hasTable('materials')) {
                    $table->foreign('material_id')
                        ->references('id')
                        ->on('materials')
                        ->nullOnDelete();          // или cascadeOnDelete()
                }
            }
        });
    }
};
