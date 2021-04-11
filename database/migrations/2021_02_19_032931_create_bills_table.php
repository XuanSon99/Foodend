<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string("student_id");
            $table->unsignedBigInteger("document_id");
            $table->integer("amount");
            $table->string("lender");
            $table->date("borrow_time");
            $table->date("return_time");
            $table->string("status");
            $table->timestamps();

            $table->foreign("student_id")->references("student_code")->on("readers");
            $table->foreign("document_id")->references("id")->on("documents");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
