<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>習慣の森</title>
  <link rel="stylesheet" href="{{asset('css/index.css')}}">
</head>
<body>
  <main class="page">
    <header class="header">
      <div>
        <p class="date">2026年11月22日（日）</p>
        <h1>習慣の森</h1>
        <p class="message">今日の小さな一歩を、年輪に。</p>
      </div>
      <button class="add-button">＋ 新しい習慣</button>
    </header>

    <nav class="tabs" aria-label="習慣の状態">
      <a class="selected" href="#active">取り組み中</a>
      <a href="#completed">完成</a>
      <a href="#withered">枯れた木</a>
    </nav>

@foreach ($habits as $habit)
    @php
        $run = $habit->runs->first();
    @endphp

    <section>
        <h2>{{ $habit->name }}</h2>

        @if ($run)
            <p>
                {{ $run->treeType->name }}
                ・ {{ $run->ring_count }} / {{ $run->treeType->target_days }}日
                ・ {{ $run->status }}
            </p>
        @endif
    </section>
@endforeach

    <footer class="footer">
      {{$habits->links()}}
    </footer>
  </main>
</body>
</html>