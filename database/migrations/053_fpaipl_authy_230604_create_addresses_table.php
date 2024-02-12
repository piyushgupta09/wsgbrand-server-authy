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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->morphs('addressable');
            $table->string('print')->nullable();
            $table->string('name');
            $table->string('lname')->nullable();
            $table->string('contacts')->nullable();
            $table->string('line1')->nullable();
            $table->string('line2')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->string('gstin')->nullable();
            $table->string('pan')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
