<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('siren', 13);
            $table->string('company_name', 50);
            $table->string('address');
            $table->string('city');
            $table->string('zip');
            $table->enum('status', ['En cours', 'Accepté']);
            $table->enum('label_template', ['Gaz', 'Electricité']);
            $table->string('PDL');
            $table->string('PCE');
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('supplier', false);
            $table->string('email', 50);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
};
