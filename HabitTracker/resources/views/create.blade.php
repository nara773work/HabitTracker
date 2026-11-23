<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しい習慣</title>
    <link rel="stylesheet" href="{{asset('css/common.css')}}">
</head>
<body>
        <main class="page">
    <a class="back-link" href="{{ route('index') }}">‹ 習慣一覧に戻る</a>

    <header class="page-header">
        <div>
            <p class="eyebrow">習慣の森</p>
            <h1>新しい習慣をつくる</h1>
            <p class="description">続けたいことと、育てる木を選びましょう。</p>
        </div>
    </header>

    <form class="habit-form" action="{{ route('store') }}" method="POST">
        @csrf

        <div class="form-field">
            <label for="name">習慣の名前</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                maxlength="100"
                placeholder="例：毎朝5時に起きる"
                required
            >

            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
    <label for="timing">タイミング</label>
    <p class="when-sentence">※既存の行動に結び付けると習慣化しやすくなります</p>
    <textarea
        id="timing"
        name="timing"
        rows="3"
        maxlength="200"
        placeholder="例：帰宅後すぐ、歯を磨きながら"
    >{{ old('timing') }}</textarea>

    @error('timing')
        <p class="error-message">{{ $message }}</p>
    @enderror
</div>

        <fieldset class="tree-field">
            <legend>育てる木</legend>

            <div class="tree-options">
                @foreach ($treeTypes as $treeType)
                    <label class="tree-option">
                        <input
                            type="radio"
                            name="tree_type_id"
                            value="{{ $treeType->id }}"
                            @checked(old('tree_type_id') == $treeType->id)
                            required
                        >
                        <span>{{ $treeType->name }}</span>
                        <small>{{ number_format($treeType->target_days) }}日</small>
                    </label>
                @endforeach
            </div>

            @error('tree_type_id')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </fieldset>

        <div class="form-actions">
            <a class="button button-sub" href="{{ route('index') }}">キャンセル</a>
            <button class="button button-main" type="submit">習慣を作成</button>
        </div>
    </form>
</main>
</body>
</html>