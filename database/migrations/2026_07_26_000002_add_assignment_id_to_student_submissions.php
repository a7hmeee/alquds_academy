<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'student_submissions';
        $column = 'memorization_assignment_id';
        $referencedTable = 'memorization_assignments';
        $fkName = "fk_{$tableName}_{$column}";
        $idxName = "idx_{$tableName}_{$column}";
        $dbName = DB::getDatabaseName();
        $isMysql = in_array(DB::getDriverName(), ['mysql', 'mariadb'], true);

        // ────────────────────────────────────────────────────────────────────────
        // 1. Clean orphaned indexes from prior failed runs (prevents errno 121) — MySQL only
        // ────────────────────────────────────────────────────────────────────────
        if ($isMysql) {
            foreach ([$idxName, "{$tableName}_{$column}_index", "{$tableName}_{$column}_foreign"] as $idx) {
                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$idx}`");
                } catch (\Exception $e) {
                    // Not orphaned
                }
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // 2. Add column (if missing) + index + FK with explicit names
        // ────────────────────────────────────────────────────────────────────────
        $columnExists = Schema::hasColumn($tableName, $column);

        if (!$columnExists) {
            // Create column without auto-index (we'll add index + FK explicitly)
            Schema::table($tableName, function (Blueprint $table) use ($column) {
                $table->unsignedBigInteger($column)->nullable()->after('score');
            });
        }

        if ($isMysql) {
            // Ensure index exists
            $idxExists = DB::select(
                'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?',
                [$dbName, $tableName, $idxName]
            );
            if (empty($idxExists)) {
                DB::statement("ALTER TABLE `{$tableName}` ADD INDEX `{$idxName}` (`{$column}`)");
            }

            // Ensure FK exists
            $fkExists = DB::select(
                'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
                [$dbName, $tableName, $fkName, 'FOREIGN KEY']
            );
            if (empty($fkExists)) {
                DB::statement(
                    "ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$fkName}` FOREIGN KEY (`{$column}`) REFERENCES `{$referencedTable}`(`id`) ON DELETE SET NULL"
                );
            }
        } else {
            // Portable path (SQLite test DB): use schema builder
            if (!Schema::hasIndex($tableName, [$column])) {
                Schema::table($tableName, function (Blueprint $table) use ($column, $idxName) {
                    $table->index([$column], $idxName);
                });
            }
            if (!Schema::hasIndex($tableName, [$fkName])) {
                Schema::table($tableName, function (Blueprint $table) use ($column, $fkName, $referencedTable) {
                    $table->foreign($column, $fkName)
                        ->references('id')
                        ->on($referencedTable)
                        ->onDelete('set null');
                });
            }
        }
    }

    public function down(): void
    {
        $tableName = 'student_submissions';
        $column = 'memorization_assignment_id';
        $fkName = "fk_{$tableName}_{$column}";
        $idxName = "idx_{$tableName}_{$column}";
        $dbName = DB::getDatabaseName();

        foreach ([$fkName, "{$tableName}_{$column}_foreign"] as $fk) {
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$fk}`");
            } catch (\Exception $e) {
            }
        }
        foreach ([$idxName, "{$tableName}_{$column}_index"] as $idx) {
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$idx}`");
            } catch (\Exception $e) {
            }
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName, $column) {
            if (Schema::hasColumn($tableName, $column)) {
                $table->dropColumn($column);
            }
        });
    }
};
