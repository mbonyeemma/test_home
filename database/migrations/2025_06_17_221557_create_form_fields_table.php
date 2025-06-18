<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
        $table->increments('id'); // replaces $table->id()

        $table->unsignedInteger('forms_id'); // replaces foreignId()
        $table->foreign('forms_id')->references('id')->on('forms')->onDelete('cascade');

        $table->string('field_type'); // input, dropdown, checkbox
        $table->string('field_name');
        $table->string('field_value')->nullable();

        $table->enum('option', ['mandatory', 'optional'])->default('optional');
        $table->enum('status', ['enabled', 'disabled'])->default('enabled');
        $table->text('dropdown_options')->nullable(); // no json() in older MySQL engines

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
        Schema::dropIfExists('form_fields');
    }
}
