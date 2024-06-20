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
        Schema::create('columns', function (Blueprint $table) {
            $table->bigIncrements('id_column');
            $table->unsignedInteger('table_id');
            $table->string('name');
            $table->unsignedInteger('datatype_id');
            $table->unsignedInteger('long');

            $table->unsignedInteger('created_by');
            $table->unsignedInteger('udpated_by');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign("created_by")->references("id")->on("users")->restrictOnDelete();
            $table->foreign("updated_by")->references("id")->on("users")->restrictOnDelete();
            $table->foreign("table_id")->references("id_table")->on("tables")->restrictOnDelete();
            $table->foreign("datatype_id")->references("id_datatype")->on("datatypes")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
