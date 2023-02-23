<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('emp_id', 100)->nullable();
            $table->string('email');
            $table->string('mobile', 20)->nullable();
            $table->string('department_id', 20)->nullable();
            $table->string('designation_id', 20)->nullable();
            $table->string('office_id', 20)->nullable();
            $table->string('image_path')->default('admin/dist/img/generic-user-icon.png');
            $table->integer('reporting_manager')->default(0);
            $table->string('street_address', 250)->nullable();
            $table->integer('block')->default(0)->comment('0 is active, 1 is blocked');
            $table->softDeletes();
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
        Schema::dropIfExists('employees');
    }
}
