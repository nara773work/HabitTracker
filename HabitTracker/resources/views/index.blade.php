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

    <section class="habit-list" id="active">
      <article class="habit">
        <div class="tree" aria-hidden="true"></div>
        <div class="habit-info">
          <h2>毎朝5時に起きる</h2>
          <p>シラカバ ・ 12 / 30日</p>
        </div>
        <div class="actions"><button aria-label="編集">✎</button><button aria-label="削除">⌫</button></div>
      </article>

      <article class="habit">
        <div class="tree" aria-hidden="true"></div>
        <div class="habit-info">
          <h2>寝る前に本を読む</h2>
          <p>ウメ ・ 24 / 100日</p>
        </div>
        <div class="actions"><button aria-label="編集">✎</button><button aria-label="削除">⌫</button></div>
      </article>

      <article class="habit">
        <div class="tree gray" aria-hidden="true"></div>
        <div class="habit-info">
          <h2>毎日ストレッチ</h2>
          <p>ケヤキ ・ 5 / 365日 ・ <span class="status">枯れた木</span></p>
        </div>
        <div class="actions"><button aria-label="編集">✎</button><button aria-label="削除">⌫</button></div>
      </article>

      <article class="habit">
        <div class="tree" aria-hidden="true"></div>
        <div class="habit-info">
          <h2>通勤後に英単語を復習</h2>
          <p>スギ ・ 38 / 90日</p>
        </div>
        <div class="actions"><button aria-label="編集">✎</button><button aria-label="削除">⌫</button></div>
      </article>
    </section>

    <footer class="footer">
      <span>全5件中 1〜4件を表示</span>
      <div><button>‹</button> 1 / 2 <button>›</button></div>
    </footer>
  </main>
</body>
</html>