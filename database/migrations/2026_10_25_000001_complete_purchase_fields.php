<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['reference', 'payment_status', 'attachments', 'note'] as $column) {
            if (!Schema::hasColumn('purchases', $column)) {
                Schema::table('purchases', function (Blueprint $table) use ($column) {
                    if ($column === 'note') {
                        $table->text($column)->nullable();
                    } else {
                        $table->string($column)->nullable();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        // These columns may predate this compatibility migration in imported databases.
        // Retain business data rather than dropping pre-existing columns on rollback.
    }
};
