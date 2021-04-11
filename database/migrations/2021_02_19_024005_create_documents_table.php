<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('documents');
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("author_id");
            $table->unsignedBigInteger("publisher_id");
            $table->unsignedBigInteger("language_id");
            $table->unsignedBigInteger("field_id");
            $table->date("publishing_year");
            $table->float("price");
            $table->integer("page_number");
            $table->string("category");
            $table->integer("amount");
            $table->timestamps();

            $table->foreign("author_id")->references("id")->on("authors");
            $table->foreign("publisher_id")->references("id")->on("publishers");
            $table->foreign("language_id")->references("id")->on("languages");
            $table->foreign("field_id")->references("id")->on("fields");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
