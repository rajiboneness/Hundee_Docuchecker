<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAgreementFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agreement_id');
            $table->bigInteger('field_id');
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $totalFieldsCount = DB::table('fields')->count();
        $data = [];

        for($i = 1; $i <= $totalFieldsCount; $i++) {
            array_push($data, ['agreement_id' => 1, 'field_id' => $i]);
        }

        DB::table('agreement_fields')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreement_fields');
    }
}
