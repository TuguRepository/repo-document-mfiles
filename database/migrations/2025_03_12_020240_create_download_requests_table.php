<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('token');
            $table->string('document_title');
            $table->string('document_number');
            $table->string('document_version')->default('latest');
            $table->string('reason')->nullable();
            $table->string('other_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('reference_number')->unique();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('approval_note')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->text('rejection_note')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('download_token')->nullable()->unique();
            $table->integer('download_count')->default(0);
            $table->timestamp('last_download_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('usage_type')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('approver_id')->references('id')->on('users')->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_requests');
    }
}
