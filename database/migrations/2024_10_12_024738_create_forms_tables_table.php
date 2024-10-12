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
        Schema::create('forms_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id');
            $table->string("field_name");
            $table->string("label");
            $table->unsignedInteger("size")->comment("Indicamos el tamaño del campo en el formulario teniendo en cuenta 1-12 según la grid");
            $table->unsignedTinyInteger("required")->default(0)->comment("Campo obligatorio");
            $table->unsignedInteger("order")->comment("Orden de los campos");
            $table->unsignedTinyInteger("visible")->default(1);
            $table->unsignedTinyInteger("editable")->default(1);
            $table->unsignedTinyInteger("filter_field")->nullable()->comment("Indica el valor con el que va a filtrar otro componente");
            $table->unsignedTinyInteger("modify_to")->nullable()->comment("Indica el field_name que va a modificar");
            $table->string("info")->nullable()->comment("Corresponde a la información que se le quiera añadir al campo, con un tooltip");

            $table->timestamps();
            $table->foreign('table_id')->references('id')->on('tables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms_tables');
    }
};
