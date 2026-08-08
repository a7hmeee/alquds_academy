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
        $dbName = DB::getDatabaseName();
        $driver = DB::getDriverName();

        $isMysql = in_array($driver, ['mysql', 'mariadb'], true);

        // ────────────────────────────────────────────────────────────────────────
        // 1. Clean orphaned indexes left by prior failed runs (MySQL only).
        //    When $table->foreign() is used without a pre-existing index, MySQL
        //    auto-creates an index with the SAME name as the FK. If the migration
        //    fails afterwards, that index lingers and causes errno 121 on retry.
        // ────────────────────────────────────────────────────────────────────────
        if ($isMysql) {
            foreach (['student_submissions_surah_id_foreign', 'student_submissions_juz_id_foreign'] as $idx) {
                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$idx}`");
                } catch (\Exception $e) {
                    // Not orphaned
                }
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // 2. Add explicit-index + FK for each column (index name ≠ FK name).
        //    On SQLite/test drivers use portable Schema builder — `information_schema`
        //    queries are MySQL-specific and break the SQLite in-memory test DB.
        // ────────────────────────────────────────────────────────────────────────
        foreach ([
            ['column' => 'surah_id', 'references' => 'surahs', 'onDelete' => 'SET NULL'],
            ['column' => 'juz_id',   'references' => 'juz',    'onDelete' => 'SET NULL'],
        ] as $def) {
            $column = $def['column'];
            $fkName = "fk_{$tableName}_{$column}";
            $idxName = "idx_{$tableName}_{$column}";

            if (!Schema::hasColumn($tableName, $column)) {
                continue;
            }

            if ($isMysql) {
                // Skip if FK already exists
                $fkExists = DB::select(
                    'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
                    [$dbName, $tableName, $fkName, 'FOREIGN KEY']
                );
                if (!empty($fkExists)) {
                    continue;
                }

                // Pre-create index (safe if already exists — MySQL ignores duplicate index names
                // when they are the same columns; we check first to avoid the error)
                $idxExists = DB::select(
                    'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?',
                    [$dbName, $tableName, $idxName]
                );
                if (empty($idxExists)) {
                    DB::statement("ALTER TABLE `{$tableName}` ADD INDEX `{$idxName}` (`{$column}`)");
                }

                DB::statement(
                    "ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$fkName}` FOREIGN KEY (`{$column}`) REFERENCES `{$def['references']}`(`id`) ON DELETE {$def['onDelete']}"
                );
            } else {
                // Portable path (SQLite test DB): add index + FK via schema builder.
                if (!Schema::hasIndex($tableName, [$column])) {
                    Schema::table($tableName, function (Blueprint $table) use ($column, $idxName) {
                        $table->index([$column], $idxName);
                    });
                }
                if (!Schema::hasIndex($tableName, [$fkName])) {
                    Schema::table($tableName, function (Blueprint $table) use ($column, $def, $fkName) {
                        $table->foreign($column, $fkName)
                            ->references('id')
                            ->on($def['references'])
                            ->onDelete(strtolower($def['onDelete']) === 'set null' ? 'set null' : 'cascade');
                    });
                }
            }
        }

        // ────────────────────────────────────────────────────────────────────────
        // 3. circle_id must be NOT NULL (domain rule)
        // ────────────────────────────────────────────────────────────────────────
        Schema::table($tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('circle_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        $tableName = 'student_submissions';
        $columns = ['surah_id', 'juz_id'];
        $dbName = DB::getDatabaseName();

        foreach ($columns as $column) {
            $fkName = "fk_{$tableName}_{$column}";
            $idxName = "idx_{$tableName}_{$column}";

            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$fkName}`");
            } catch (\Exception $e) {
                // May not exist
            }
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$idxName}`");
            } catch (\Exception $e) {
                // May not exist
            }
            // Also try old auto-generated names from prior clean-run (before this fix)
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP FOREIGN KEY `{$tableName}_{$column}_foreign`");
            } catch (\Exception $e) {
            }
            try {
                DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$tableName}_{$column}_foreign`");
            } catch (\Exception $e) {
            }
        }
    }
};
