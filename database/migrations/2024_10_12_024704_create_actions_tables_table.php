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
        Schema::create('actions_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('action_id');
            $table->unsignedInteger("order");
            $table->string("message")->nullable();
            $table->timestamps();

            $table->foreign('table_id')->references('id')->on('tables');
            $table->foreign('action_id')->references('id')->on('actions_buttons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions_tables');
    }
};
