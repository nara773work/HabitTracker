<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しい習慣</title>
    <link rel="stylesheet" href="{{asset('css/create.css')}}">
</head>
<body>
        <main>
        <p><a href="{{ route('index') }}">習慣一覧</a> ＞ 新しい習慣</p>

        <h1>新しい習慣を追加</h1>
        <p>続けたいことを決めて、あなたの木を育てましょう。</p>

        <form action="{{ route('store') }}" method="POST">
            @csrf

            <div>
                <label for="name">習慣の名前</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    maxlength="100"
                    placeholder="例：毎朝5時に起きる"
                    required
                >

                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <fieldset>
                <legend>育てる木を選ぶ</legend>

                @foreach ($treeTypes as $treeType)
                    <label>
                        <input
                            type="radio"
                            name="tree_type_id"
                            value="{{ $treeType->id }}"
                            @checked(old('tree_type_id') == $treeType->id)
                            required
                        >

                        {{ $treeType->name }}
                        （{{ number_format($treeType->target_days) }}日）
                    </label>
                @endforeach

                @error('tree_type_id')
                    <p>{{ $message }}</p>
                @enderror
            </fieldset>

            <button type="submit">習慣を作成</button>
            <a href="{{ route('index') }}">キャンセル</a>
        </form>
    </main>
</body>
</html>