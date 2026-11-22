<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 木の種類を管理する
     * target_daysは目標日数のこと
     */
    public function up(): void
    {
        Schema::create('tree_types',function(Blueprint $table){
            $table->id();
            $table->string('name',100);
            $table->integer('target_days');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
