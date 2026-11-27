<?php

namespace Tests\Feature;

use App\Models\Habit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HabitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->travelTo(Carbon::parse('2026-11-26 12:00:00'));
    }

    public function test_today_toggle_creates_and_removes_completion_log(): void
    {
        [$habit, $runId] = $this->createRun();

        $this->from(route('show', $habit))
            ->patch(route('toggle', $habit))
            ->assertRedirect(route('show', $habit));

        $this->assertDatabaseHas('habit_logs', [
            'habit_run_id' => $runId,
            'completed_on' => Carbon::today()->toDateString(),
        ]);

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'ring_count' => 1,
        ]);

        // 同じ日にもう一度押すと、今日の記録を取り消す
        $this->from(route('show', $habit))
            ->patch(route('toggle', $habit))
            ->assertRedirect(route('show', $habit));

        $this->assertDatabaseMissing('habit_logs', [
            'habit_run_id' => $runId,
            'completed_on' => Carbon::today()->toDateString(),
        ]);

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'ring_count' => 0,
        ]);
    }

    public function test_completion_can_be_recorded_again_after_midnight(): void
    {
        [$habit, $runId] = $this->createRun();

        $this->travelTo(Carbon::parse('2026-11-26 23:58:00'));

        $this->from(route('show', $habit))
            ->patch(route('toggle', $habit));

        $this->travelTo(Carbon::parse('2026-11-27 00:02:00'));

        $this->from(route('show', $habit))
            ->patch(route('toggle', $habit));

        $this->assertDatabaseHas('habit_logs', [
            'habit_run_id' => $runId,
            'completed_on' => '2026-11-26',
        ]);

        $this->assertDatabaseHas('habit_logs', [
            'habit_run_id' => $runId,
            'completed_on' => '2026-11-27',
        ]);

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'ring_count' => 2,
        ]);
    }

    public function test_one_missed_day_does_not_wither_the_run(): void
    {
        $today = Carbon::today();
        [$habit, $runId] = $this->createRun([
            'started_on' => $today->copy()->subDays(2)->toDateString(),
        ]);

        // 一昨日は達成。昨日はスキップ。
        $this->createLog($runId, $today->copy()->subDays(2)->toDateString());

        $this->get(route('show', $habit))
            ->assertOk()
            ->assertViewHas('missedDays', 1);

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'status' => 'ongoing',
        ]);
    }

    public function test_two_consecutive_missed_days_wither_the_run_when_shown(): void
    {
        $today = Carbon::today();
        [$habit, $runId] = $this->createRun([
            'started_on' => $today->copy()->subDays(3)->toDateString(),
        ]);

        // 昨日と一昨日のログは作らない
        $this->get(route('show', $habit))
            ->assertOk()
            ->assertViewHas('missedDays', 2);

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'status' => 'withered',
        ]);
    }

    public function test_completed_run_is_not_changed_to_withered(): void
    {
        $today = Carbon::today();
        [$habit, $runId] = $this->createRun([
            'started_on' => $today->copy()->subDays(3)->toDateString(),
            'status' => 'completed',
            'ring_count' => 100,
        ]);

        $this->get(route('show', $habit))->assertOk();

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'status' => 'completed',
        ]);
    }

    public function test_replanting_starts_a_new_attempt_and_records_today(): void
    {
        $today = Carbon::today();

        [$habit, $runId] = $this->createRun([
            'started_on' => $today->copy()->subDays(5)->toDateString(),
            'restart_on' => null,
            'ring_count' => 12,
            'recovery_count' => 8,
            'status' => 'withered',
        ]);

        // 以前の挑戦が1回目だった記録
        $this->createLog(
            $runId,
            $today->copy()->subDays(3)->toDateString(),
            1
        );

        $this->from(route('show', $habit))
            ->patch(route('toggle', $habit))
            ->assertRedirect(route('show', $habit));

        $this->assertDatabaseHas('habit_runs', [
            'id' => $runId,
            'status' => 'ongoing',
            'started_on' => $today->toDateString(),
            'restart_on' => $today->toDateString(),
            'ring_count' => 1,
            'recovery_count' => 0,
        ]);

        $this->assertDatabaseHas('habit_logs', [
            'habit_run_id' => $runId,
            'completed_on' => $today->toDateString(),
            'attempt_number' => 2,
        ]);
    }

    private function createRun(array $runAttributes = [], int $targetDays = 100): array
    {
        $now = now();

        $treeTypeId = DB::table('tree_types')->insertGetId([
            'name' => 'テストの木',
            'sort_order' => 1,
            'target_days' => $targetDays,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $habitId = DB::table('habits')->insertGetId([
            'name' => 'テスト習慣',
            'timing' => '朝',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $runId = DB::table('habit_runs')->insertGetId(array_merge([
            'habit_id' => $habitId,
            'tree_type_id' => $treeTypeId,
            'ring_count' => 0,
            'started_on' => Carbon::today()->toDateString(),
            'restart_on' => null,
            'recovery_count' => 0,
            'status' => 'ongoing',
            'created_at' => $now,
            'updated_at' => $now,
        ], $runAttributes));

        return [Habit::findOrFail($habitId), $runId];
    }

    private function createLog(int $runId, string $date, int $attemptNumber = 1): void
    {
        DB::table('habit_logs')->insert([
            'habit_run_id' => $runId,
            'completed_on' => $date,
            'attempt_number' => $attemptNumber,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
