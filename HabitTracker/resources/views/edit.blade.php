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
    <a class="back-link" href="{{ route('index', $habit) }}">
        ‹ 一覧画面に戻る
    </a>

    <header class="page-header">
        <div>
            <p class="eyebrow">習慣の森</p>
            <h1>習慣を編集</h1>
            <p class="description">習慣名や育てる木を変更できます。</p>
        </div>
    </header>

    <form
        class="habit-form"
        action="{{ route('update', $habit) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div class="form-field">
    <span class="field-label">習慣の名前(変更できません)</span>
    <p class="readonly-value">{{ $habit->name }}</p>
</div>

<div class="form-field">
    <label for="timing">タイミング</label>
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
                            @checked(
                                old('tree_type_id', $run ? $run->tree_type_id : null)
                                == $treeType->id
                            )
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
            <a class="button button-sub" href="{{ route('index', $habit) }}">
                キャンセル
            </a>
            <button class="button button-main" type="submit">変更を保存</button>
        </div>
    </form>
</main> 
</body>