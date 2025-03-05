<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name')->default("EGYTIGERS");
            $table->string('app_name')->default("TIGOUNTERS");
            $table->integer('tax_num')->nullable();
            $table->longText('commercial_register')->nullable();
            $table->string('logo')->default("default.png");
            $table->string('email')->default("farestarikhassan7@gmail.com");
            $table->string('phone')->default("+201018730620");
            $table->string('address')->default("EGYTIGERS");
            $table->string('developer')->default("Fares Tarik");
            $table->longText('max_document_size')->nullable();
            $table->integer("tenant_id")->default(0);
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
        Schema::dropIfExists('settings');
    }
}