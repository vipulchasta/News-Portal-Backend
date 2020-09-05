<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('role', 100);
            $table->string('fileName' ,300)->nullable();
            $table->string('mobileNo', 13)->unique();
            $table->string('password', 100);
            $table->string('status', 100)->default('INACTIVE');
            $table->string('token', 100)->nullable();
            $table->timestamp('time')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
