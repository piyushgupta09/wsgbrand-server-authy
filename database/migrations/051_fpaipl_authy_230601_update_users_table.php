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
        Schema::table('users', function (Blueprint $table) { 
            $table->string('mobile')->nullable();
            $table->string('otpcode')->nullable();
            $table->string('utype')->nullable(); // email or mobile
            $table->boolean('active')->default(true);
            $table->string('type')->default('user'); // based on this assibnable roles will be determined
            $table->string('uuid')->unique();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) { 
            $table->dropColumn('mobile');
            $table->dropColumn('otpcode');
            $table->dropColumn('utype');
            $table->dropColumn('active');
            $table->dropColumn('type');
            $table->dropColumn('uuid');
            $table->dropSoftDeletes();
        });
    }
};
