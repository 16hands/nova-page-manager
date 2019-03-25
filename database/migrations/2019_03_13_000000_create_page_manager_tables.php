<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageManagerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nova_page_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->enum('type', ['page', 'region']);
            $table->string('name');
            $table->string('slug')->default('')->unique();
            $table->string('locale');
            $table->string('template');
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_image')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->json('data')->nullable();

            $table->unique(['parent_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nova_page_manager');
    }
}
