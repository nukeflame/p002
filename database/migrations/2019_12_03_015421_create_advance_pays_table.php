<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_pays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('otherNames');
            $table->date('date_issued');
            $table->integer('installments');
            $table->integer('unpaid_balance');
            $table->integer('staff_id');
            $table->integer('amount_borrowed');
            $table->boolean('status');
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advance_pays');
    }
}
