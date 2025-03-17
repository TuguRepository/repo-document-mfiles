<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToDownloadRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('download_requests', function (Blueprint $table) {
            // Jangan tambahkan user_id karena sudah ada
            // $table->unsignedBigInteger('user_id');

            // Tambahkan kolom-kolom yang diperlukan
            if (!Schema::hasColumn('download_requests', 'document_version')) {
                $table->string('document_version')->default('latest')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'reference_number')) {
                $table->string('reference_number')->nullable()->unique();
            }

            if (!Schema::hasColumn('download_requests', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('download_requests', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'rejection_reason')) {
                $table->string('rejection_reason')->nullable();
            }

            if (!Schema::hasColumn('download_requests', 'admin_note')) {
                $table->text('admin_note')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('download_requests', function (Blueprint $table) {
            // List kolom yang akan dihapus jika migrasi di-rollback
            $columns = [
                'document_version', 'notes', 'reference_number', 'status',
                'approved_by', 'approved_at', 'rejected_by', 'rejected_at',
                'rejection_reason', 'admin_note'
            ];

            // Hanya hapus kolom yang ada
            foreach ($columns as $column) {
                if (Schema::hasColumn('download_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
