<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('password');
            $table->enum('choices', ['Admin', 'Sub Admin'])->nullable()->default(['Admin']);
            $table->boolean('categories_view_access')->nullable();
            $table->boolean('categories_full_access')->nullable();
            $table->boolean('categories_edit_access')->nullable();
            $table->boolean('products_access')->nullable();
            $table->boolean('orders_access')->nullable();
            $table->boolean('users_access')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
