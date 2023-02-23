<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocuMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docu_mails', function (Blueprint $table) {
            $table->id();
            $table->string('docuchecker_id', 10)->nullable();
            $table->string('RM_id', 10)->nullable();
            $table->string('reporting_manager_id', 10)->nullable();
            $table->string('subject', 100)->nullable();
            $table->string('from');
            $table->string('to');
            $table->string('ToCC');
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
        Schema::dropIfExists('docu_mails');
    }
}
