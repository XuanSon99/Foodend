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
            $table->string("bill_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("product_id");
            $table->string("address");
            $table->float("price");
            $table->integer("quantity");
            $table->string("status");

            $table->timestamps();

            // $table->foreign("user_id")->references("id")->on("users");
            // $table->foreign("product_id")->references("id")->on("products");
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
