<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'super-admin'])->default('admin')->after('username');
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif')->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'role')) {
                $columns[] = 'role';
            }
            if (Schema::hasColumn('users', 'status')) {
                $columns[] = 'status';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
