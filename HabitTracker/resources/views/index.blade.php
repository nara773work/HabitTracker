<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>習慣の森</title>
  <link rel="stylesheet" href="{{asset('css/common.css')}}">
</head>
<body>
  <main class="page">
    <header class="page-header">
        <div>
            <p class="eyebrow">習慣の森</p>
            <h1>習慣一覧</h1>
            <p class="description">小さな積み重ねを、木の年輪に。</p>
        </div>
        <a class="button button-main" href="{{ route('create') }}">
            ＋ 新しい習慣
        </a>
    </header>

    <section class="habit-list">
        @forelse ($habits as $habit)
            @php
                $run = $habit->runs->first();
            @endphp

            <article class="habit-row">
                <div class="tree-icon" aria-hidden="true"></div>

                <div class="habit-info">
                    <h2>{{ $habit->name }}</h2>
                    <p>タイミング：{{$habit->timing}}</p>

                    @if ($run)
                        <p>
                            {{ $run->treeType->name }}・ {{ $run->ring_count }} / {{ $run->treeType->target_days }}日
                        </p>
                    @else
                        <p>木の記録はまだありません</p>
                    @endif
                </div>

                <div class="habit-actions">
                    <a class="button button-sub" href="{{ route('show', $habit) }}">
                        詳細
                    </a>
                    <a class="button button-sub" href="{{ route('edit', $habit) }}">
                        編集
                    </a>

                    <form action="{{ route('destroy', $habit) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="button button-delete" type="submit">削除</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="empty-message">習慣はまだありません。</p>
        @endforelse
    </section>

    @if ($habits->hasPages())
        <nav class="pager" aria-label="ページ送り">
            @if ($habits->onFirstPage())
                <span class="disabled">前へ</span>
            @else
                <a href="{{ $habits->previousPageUrl() }}">前へ</a>
            @endif

            <span>{{ $habits->currentPage() }} / {{ $habits->lastPage() }}</span>

            @if ($habits->hasMorePages())
                <a href="{{ $habits->nextPageUrl() }}">次へ</a>
            @else
                <span class="disabled">次へ</span>
            @endif
        </nav>
    @endif
</main>
</body>
</html>