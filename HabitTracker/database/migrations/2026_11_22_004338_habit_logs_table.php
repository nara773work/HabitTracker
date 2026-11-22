<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 達成日を管理する
     */
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_run_id')
                ->constrained()
                ->cascadeOnDelete();
                //木の成長記録が削除されるとログも削除される
            $table->date('completed_on');
            $table->integer('attempt_number')->default(1);
            $table->timestamps();
            $table->unique(['habit_run_id', 'completed_on']);
            //同じ木について同じ日の達成は一回のみ
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};
