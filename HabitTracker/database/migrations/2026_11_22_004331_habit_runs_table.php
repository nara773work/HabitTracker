<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 木の成長を管理する
     */
    public function up(): void
    {
        Schema::create('habit_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')
                ->constrained()
                ->cascadeOnDelete();
            // 紐づく習慣が削除されるとこの木の成長自体も削除される
            $table->foreignId('tree_type_id')
                ->constrained()
                ->restrictOnDelete();
            // この成長記録で使われている間は参照元の木は削除できない
            $table->date('started_on');
            $table->date('restart_on')->nullable();
            $table->integer('ring_count')->default(0);
            $table->integer('recovery_count')->default(0);
            $table->string('status')->default('ongoing');
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
