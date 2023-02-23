<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAgreementDocumentUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_document_uploads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agreement_document_id');
            $table->bigInteger('borrower_id');
            $table->string('file_path');
            $table->string('file_type');
            $table->bigInteger('uploaded_by');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('verify')->default(0);
            $table->bigInteger('verified_by')->nullable();
            $table->softdeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreement_document_uploads');
    }
}
