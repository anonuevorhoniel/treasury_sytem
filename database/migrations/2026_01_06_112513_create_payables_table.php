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
        Schema::create('tbl_payables', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('dv_number');
            $table->string('check_number');
            $table->string('obr_number');
            $table->string('office_id');
            $table->date('date');
            $table->longText('particulars');
            $table->decimal('ps')->nullable();
            $table->decimal('ps_deduction')->nullable();
            $table->decimal('mooe')->nullable();
            $table->decimal('mooe_deduction')->nullable();
            $table->decimal('co')->nullable();
            $table->decimal('co_deduction')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_payables');
    }
};
