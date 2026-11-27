<?php

namespace App\Actions;

use App\Models\HabitRun;
use Carbon\Carbon;

class UpdateHabitRunStatus
{
    public function handle(HabitRun $run): int
    {
        $missedDays = 0;

        $startedOn = Carbon::parse(
            $run->restart_on ?? $run->started_on
        )->startOfDay();

        $logDates = $run->habitLogs
            ->map(fn ($log) => Carbon::parse($log->completed_on)->toDateString())
            ->all();

        $today = Carbon::today();

        for ($daysAgo = 1; $daysAgo <= 2; $daysAgo++) {
            $date = $today->copy()->subDays($daysAgo);

            if (
                $date->lt($startedOn) ||
                in_array($date->toDateString(), $logDates, true)
            ) {
                break;
            }

            $missedDays++;
        }

        $targetDays = (int) $run->treeType->target_days;
        $count = (int) $run->ring_count;

        if ($count >= $targetDays) {
            $run->status = 'completed';
        } elseif ($missedDays >= 2 && $run->status !== 'withered') {
            $run->status = 'withered';
        }

        $run->save();

        return $missedDays;
    }
}
