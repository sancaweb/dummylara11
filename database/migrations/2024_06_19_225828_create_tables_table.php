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
        Schema::create('tables', function (Blueprint $table) {
            $table->increments(['id_table']);
            $table->string('name');

            $table->unsignedInteger('created_by');
            $table->unsignedInteger('udpated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("created_by")->references("id")->on("users")->restrictOnDelete();
            $table->foreign("updated_by")->references("id")->on("users")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
