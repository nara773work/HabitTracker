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
        $run = $habit->runs()
            ->with(['treeType', 'habitlogs'])
            ->latest('id')
            ->first();

        return view('show', compact('run', 'habit'));
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
            ->with('message','木を伐採してしまいました…🪓🪓');
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

        $run = $habit->runs()->latest('id')->first();

        $log = $run->habitLogs()
            ->whereDate('completed_on', $today)
            ->first();

        if ($log) {
            // 今日の記録を取り消す
            $log->delete();

            // 年輪の数を減らす
            $run->update([
                'ring_count' => max(0, $run->ring_count - 1),
            ]);

            return back()->with('message', '本日の実行記録を取り消しました💧');
        }

        // 今日の記録を追加して、年輪数を増やす
        $run->habitLogs()->create([
            'completed_on' => $today,
        ]);

        // 指定したカラムの値を1増やしてDBに保存する
        $run->increment('ring_count');

        return back()->with('message', '木が成長しました✨✨');
    }
}
