<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBorrowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->string('pan_card_number', 100)->nullable()->after('block');
            $table->bigInteger('uploaded_by')->nullable()->after('pan_card_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->dropColumn('pan_card_number');
            $table->dropColumn('uploaded_by');
        });
    }
}
