<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddBankDataToFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // bank list
        $bankLists = '';
        $bankData = DB::table('bank_lists')->select('name')->orderBy('name', 'asc')->get();
        $bankCount = $bankData->count();

        foreach ($bankData as $key => $bank) {
            if ($bankCount != ($key + 1)) {
                $bankLists .= $bank->name.', ';
            } else {
                $bankLists .= $bank->name;
            }
        }

        DB::statement("UPDATE fields SET value = '$bankLists' WHERE key_name = 'banknameofborrower'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('fields', function (Blueprint $table) {
        //     //
        // });
    }
}
