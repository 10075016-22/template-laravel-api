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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->string('description');
            $table->string('icon');
            $table->string('name');
            $table->unsignedInteger('order');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->softDeletes();
            $table->foreign('permission_id')->references('id')->on('permissions');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
