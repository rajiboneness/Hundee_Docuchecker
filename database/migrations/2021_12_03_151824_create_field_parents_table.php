<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFieldParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_parents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('position');
            $table->softdeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        $data = [
            ['name' => 'Borrower details', 'position' => 1],
            ['name' => 'Co-borrower 1 details', 'position' => 2],
            ['name' => 'Guarantor details', 'position' => 4],
            ['name' => 'Witness 1 details', 'position' => 5],
            ['name' => 'Witness 2 details', 'position' => 6],
            ['name' => 'Others', 'position' => 7],
            ['name' => 'Co-borrower 2 details', 'position' => 3],
            ['name' => 'Post-dated cheques', 'position' => 8],
        ];

        DB::table('field_parents')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_parents');
    }
}
