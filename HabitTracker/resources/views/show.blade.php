<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>習慣の詳細</title>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>
<body>
    <main class="page">
        <a class="back" href="{{ route('index') }}">‹ 習慣一覧に戻る</a>

        <header class="heading">
            <div>
                <h1>{{ $habit->name }}</h1>

                @if ($run)
                    <p>
                        {{ $run->treeType->name }}
                        ・目標 {{ $run->treeType->target_days }}日
                    </p>
                @endif
            </div>
        </header>

        @if ($run)
            @php
                $count = $run->ring_count;
                $target = $run->treeType->target_days;
                $progress = min(100, round($count / $target * 100));
            @endphp

            <section class="detail">
                <div class="tree">
                    <div class="tree-count">
                        {{ $count }}<span>日達成</span>
                    </div>
                </div>

                <div class="info">
                    <p class="label">達成日数</p>
                    <p class="count">{{ $count }} <span>/ {{ $target }}日</span></p>

                    <div class="progress">
                        <div class="progress-value" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="progress-label">目標の{{ $progress }}%</p>

                    <section class="today">
                        <h2>今日の記録</h2>
                        <p>今日の達成を記録します。</p>
                        <button class="done-button" type="button">今日はできた</button>
                    </section>
                </div>
            </section>

        @else
            <p>木の成長記録がありません。</p>
        @endif
    </main>
</body>
</html>