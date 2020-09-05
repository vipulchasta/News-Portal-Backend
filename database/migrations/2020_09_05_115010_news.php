<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class News extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publisherId');
            $table->string('title', 100);
            $table->string('content', 9999);
            $table->string('fileName' ,300);
            $table->boolean('adminApproval')->default('0');
            $table->boolean('publisherApproval')->default('0');
            $table->integer('countView')->default('0');
            $table->timestamp('time')->default(DB::raw('CURRENT_TIMESTAMP'));

            
            $table->foreign('publisherId')->references('id')->on('users');
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
