<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes on frequently queried columns to speed up WHERE/ORDER BY clauses.
     */
    public function up(): void
    {
        // expenses.user_id — used in ExpenseController WHERE
        Schema::table('expenses', function (Blueprint $table) {
            if (!$this->indexExists('expenses', 'expenses_user_id_index')) {
                $table->index('user_id');
            }
        });

        // incomes.user_id — used in IncomeController WHERE
        Schema::table('incomes', function (Blueprint $table) {
            if (!$this->indexExists('incomes', 'incomes_user_id_index')) {
                $table->index('user_id');
            }
        });

        // invoices.user_id — used in IncomeController & UserController WHERE
        Schema::table('invoices', function (Blueprint $table) {
            if (!$this->indexExists('invoices', 'invoices_user_id_index')) {
                $table->index('user_id');
            }
        });

        // products.user_id — used in ProductController WHERE
        Schema::table('products', function (Blueprint $table) {
            if (!$this->indexExists('products', 'products_user_id_index')) {
                $table->index('user_id');
            }
        });

        // invoice_products.name — used in grouped sold-quantity query
        Schema::table('invoice_products', function (Blueprint $table) {
            if (!$this->indexExists('invoice_products', 'invoice_products_name_index')) {
                $table->index('name');
            }
        });

        // supplier.user_id — used in customerController WHERE
        Schema::table('suppliers', function (Blueprint $table) {
            if (!$this->indexExists('suppliers', 'suppliers_user_id_index')) {
                $table->index('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely skip down for this migration to avoid foreign key constraint errors
        // Indexes on user_id and other frequently queried columns are beneficial
    }

    /**
     * Helper to check if an index already exists (avoids duplicate index errors).
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $dbName     = $connection->getDatabaseName();
        $count = $connection->select(
            "SELECT COUNT(*) as cnt FROM information_schema.statistics
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$dbName, $table, $indexName]
        );
        return (int) $count[0]->cnt > 0;
    }
};
