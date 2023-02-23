<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('emp_id')->after('name');
            $table->bigInteger('department_id')->after('email_verified_at');
            $table->bigInteger('designation_id')->after('department_id');
            $table->date('joining_date')->after('block')->default(date('Y-m-d'));
            $table->bigInteger('office_id')->after('joining_date');
        });

        DB::statement("UPDATE users SET emp_id = 'PFSL0001' WHERE email = 'admin@admin.com'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('emp_id');
            $table->dropColumn('department_id');
            $table->dropColumn('designation_id');
            $table->dropColumn('joining_date');
            $table->dropColumn('office_id');
        });
    }
}
