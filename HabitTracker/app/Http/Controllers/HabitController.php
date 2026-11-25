<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabitRequest;
use App\Models\Habit;
use App\Models\TreeType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HabitController extends Controller
{
    /**
     * 習慣の一覧表示
     *
     * 習慣から成長記録(run)、木の種類(tree_type)のリレーションを取得している
     * ページネーションで習慣は4件ずつ表示される
     * タブごとに「取組中」「完成した木」「枯れ木」を表示
     * デフォルトでは「取組中」を表示する
     */
    public function index(Request $request)
    {
        $statuses = [
            'ongoing' => '取組中',
            'withered' => '枯れ木',
            'completed' => '完成した木',
        ];

        // ステータスクエリを読み取る。初期値は取組中に設定する
        $currentStatus = $request->query('status', 'ongoing');

        $habits = Habit::with('runs.treeType')
            ->whereHas('runs', function ($query) use ($currentStatus) {
                $query->where('status', $currentStatus);
            })
            ->paginate(4)
            ->withQueryString();

        return view('index', compact('habits', 'statuses', 'currentStatus'));
    }

    /**
     * 新規作成画面
     */
    public function create()
    {
        $treeTypes = TreeType::orderBy('sort_order')->get();

        return view('create', compact('treeTypes'));
    }

    /**
     * 新規作成処理
     *
     * バリデーションはフォームリクエストで作成
     */
    public function store(HabitRequest $request)
    {
        $today = Carbon::today();

        $habit = Habit::create([
            'name' => $request->name,
            'timing' => $request->timing,
        ]);

        $habit->runs()->create([
            'tree_type_id' => $request->input('tree_type'),
            'started_on' => $today,
        ]);

        return redirect()
            ->route('index')
            ->with('message', '🌲新しい木が育つのが楽しみですね🌲');
    }

    /**
     * 詳細画面表示
     *
     * 実行ボタンはtoggleで管理する
     */
    public function show(Habit $habit)
    {
        // 木の成長記録もリレーションから取得する
        $run = $habit->runs()
            ->with(['treeType', 'habitlogs'])
            ->latest('id')
            ->first();

        // 実行の取り消すを行うために「本日分の達成記録」があるか調べる
        $completedToday = $run ? $run->habitlogs()
            ->whereDate('completed_on', today())
            ->exists() : false;

        //
        $missedDays = 0;

        if ($run) {
            $startedOn = Carbon::parse(
                $run->restart_on ?? $run->started_on
            )->startOfDay();

            $logDates = $run->habitLogs
                ->map(fn ($log) => Carbon::parse($log->completed_on)->toDateString())
                ->all();

            $today = Carbon::today();

            // 昨日、さらにその前の日の順に、最大2日分確認する
            for ($daysAgo = 1; $daysAgo <= 2; $daysAgo++) {
                $date = $today->copy()->subDays($daysAgo);

                if ($date->lt($startedOn) || in_array($date->toDateString(), $logDates, true)) {
                    break;
                }

                $missedDays++;
            }
        }

        return view('show', compact('run', 'habit', 'completedToday', 'missedDays'));
    }

    /**
     * 編集画面表示
     */
    public function edit(Habit $habit)
    {
        $treeTypes = TreeType::orderBy('sort_order')->get();
        $run = $habit->runs()->latest('id')->first();

        return view('edit', compact('habit', 'treeTypes', 'run'));
    }

    /**
     * 更新処理
     */
    public function update(HabitRequest $request, Habit $habit)
    {
        // 習慣のタイミングを更新
        $habit->update([
            'timing' => $request->timing,
        ]);

        $habit->runs()->update([
            'tree_type_id' => $request->input('tree_type'),
        ]);

        return redirect()
            ->route('index')
            ->with('message', '習慣を編集しました✎✎');
    }

    /**
     * 削除処理
     */
    public function destroy(Habit $habit)
    {
        $habit->delete();

        return redirect()
            ->route('index')
            ->with('message', '木を伐採してしまいました…🪓🪓');
    }

    /**
     * 年輪の増減処理
     *
     * 本日分のログがある場合、ボタンが押されると年輪を減らしログも消す
     * 本日分のログがない場合、年輪の数を増やしログを作成する
     */
    public function toggle(Habit $habit)
    {
        $today = Carbon::today();

        $run = $habit->runs()
            ->with('treeType')
            ->latest('id')
            ->first();

        // 開始日または育てなおした日以降の達成ログを取得
        $startedOn = Carbon::parse(
            $run->restart_on ?? $run->started_on
        )->startOfDay();

        $logDates = $run->habitLogs()
            ->pluck('completed_on')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->all();

        // 昨日から連続して記録がない日数を数える（最大2日）
        $missedDays = 0;

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

        // 枯れ木なら、既存の年輪を茶色に戻して育てなおす
        if ($run->status === 'withered') {
            $nextAttempt = (int) ($run->habitLogs()->max('attempt_number') ?? 0) + 1;
    $run->update([
        'status' => 'ongoing',
        'started_on' => $today,
        'restart_on' => $today,
        'ring_count' => 0,
        'recovery_count' => 0,
    ]);

    $todayLog = $run->habitLogs()
        ->whereDate('completed_on', $today)
        ->first();

    if ($todayLog) {
        $todayLog->update([
            'attempt_number' => $nextAttempt,
        ]);
    } else {
        $run->habitLogs()->create([
            'completed_on' => $today,
            'attempt_number' => $nextAttempt,
        ]);
    }

    $run->update([
        'ring_count' => 1,
    ]);

    return back()->with('message', '新しい挑戦を始めましょう🌱');
        }

        $log = $run->habitLogs()
            ->whereDate('completed_on', $today)
            ->first();

        if ($log) {
            // 今日の記録を取り消す
            $log->delete();

            $run->update([
                'ring_count' => max(0, $run->ring_count - 1),
            ]);

            return back()->with('message', '本日の実行記録を取り消しました💧');
        }

        // 今日の記録を追加する
        $attemptNumber = $run->habitLogs()->max('attempt_number') ?? 1;

        $run->habitLogs()->create([
            'completed_on' => $today,
            'attempt_number' => $attemptNumber,
        ]);

        $run->increment('ring_count');
        $run->refresh();

        // 目標日数に達したら完成状態にする
        if ($run->ring_count >= $run->treeType->target_days) {
            $run->update([
                'status' => 'completed',
            ]);
        }

        return back()->with('message', '木が成長しました✨✨');
    }
}
