<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAgreementDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agreement_id');
            $table->string('name');
            $table->integer('parent_id')->nullable();
            $table->softdeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            [
                'agreement_id' => 1,
                'name' => 'Personal document',
                'parent_id' => null,
            ],
            [
                'agreement_id' => 1,
                'name' => 'PAN card front',
                'parent_id' => 1,
            ],
            [
                'agreement_id' => 1,
                'name' => 'Aadhar card front',
                'parent_id' => 1,
            ],
            [
                'agreement_id' => 1,
                'name' => 'Aadhar card back',
                'parent_id' => 1,
            ],
            [
                'agreement_id' => 1,
                'name' => 'Deed',
                'parent_id' => null,
            ],
            [
                'agreement_id' => 1,
                'name' => 'Form 17A',
                'parent_id' => 5,
            ],
        ];

        DB::table('agreement_documents')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreement_documents');
    }
}
