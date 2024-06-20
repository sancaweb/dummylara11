<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datatypes', function (Blueprint $table) {
            $table->increments('id_datatype');
            $table->string('name');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('udpated_by');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datatypes');
    }
};
