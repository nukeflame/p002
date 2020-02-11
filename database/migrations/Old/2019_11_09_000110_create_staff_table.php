<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staffNo');
            $table->string('bankAcc');
            $table->string('canAcess');
            $table->string('currentAddress');
            $table->string('dateEmployed');
            $table->string('department');
            $table->string('designation');
            $table->timestamp('dob')->nullable();
            $table->string('email');
            $table->string('employmentType');
            $table->integer('gender');
            $table->integer('idNo');
            $table->integer('idType');
            $table->integer('kraPin');
            $table->integer('linkToUser');
            $table->integer('maritalStatus');
            $table->string('nationality');
            $table->string('nextOfKin');
            $table->integer('nextOfKinContacts');
            $table->integer('nhifNo');
            $table->integer('nokIdNo');
            $table->integer('nokIdType');
            $table->integer('nssfNo');
            $table->string('occupation');
            $table->string('otherNames');
            $table->integer('paymentMode');
            $table->integer('paymentModeRef');
            $table->integer('payrollNo');
            $table->string('physicalAddress');
            $table->string('placeOfWork');
            $table->string('postalAdrress');
            $table->integer('postalCode');
            $table->integer('relationship');
            $table->string('streetHouseNo');
            $table->string('surname');
            $table->string('tel1');
            $table->string('tel2');
            $table->string('townCity');
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
        Schema::dropIfExists('staff');
    }
}
