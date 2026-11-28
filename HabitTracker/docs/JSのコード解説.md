React：webページを部品分けできるライブラリ
コンポーネント：部品の名称
Blade：Laravel 側でページの土台と値を用意する
app.jsx：React を起動し、表示場所を決める
TreeGrowth.jsx：木の絵を表示する部品（自作）
props：Blade などから React の部品へ渡す値
---------------------------------------------------
app.jsx

import React from 'react';
//Reactの読み込み

import { createRoot } from 'react-dom/client';
//HTMLの中にReactを表示するための機能

import TreeGrowth from './TreeGrowth.jsx';
//自分で作った部品

document.querySelectorAll('[data-tree-growth]').forEach((element) => {
//'data-tree-growth'この属性のついたHTML要素を探す
→ 'data-tree-growth'属性を持つHTML要素を全部探す

HTML側に
<div data-tree-growth data-ring-count="3"></div>
<div data-tree-growth data-ring-count="8"></div>
<div data-tree-growth data-ring-count="12"></div>
こういうのが合ったら1回目は1つ目に、2回目は2つ目に…と表示される。

createRoot(element).render(
    <TreeGrowth ... />
);
//このHTML要素をReactの表示場所にするという意味
createRoot(element)：`element` という HTML 要素を、React の表示場所にする
.render()：その場所に表示するものを指定する

ringCount={element.dataset.ringCount}
targetDays={element.dataset.targetDays}
//HTMLの値をpropsとして渡す
---------------------------------------------------
bladeテンプレートでの書き方（表示場所の用意）
<div
    id="tree-growth"
    data-ring-count="{{ $ringCount }}"
    data-target-days="{{ $targetDays }}"
></div>

JSでの受け取り方
const element = document.getElementById('tree-growth');

createRoot(element).render(//ここに描写される
    <TreeGrowth
        ringCount={Number(element.dataset.ringCount)}
        targetDays={Number(element.dataset.targetDays)}
    />
);
---------------------------------------------------

全体の流れ
1. propsから変数を受け取る
2. 数値を整え円の大きさ表示サイズの計算をする
3. 状態に応じて年輪の色や太さを決める
4. svgで描写する
5. 木の中央に達成日を表示する

svg：円、線を使って図形を描く仕組み
<svg 
    width={size}：画面上の大きさ
    height={size}：画面上の大きさ
    viewBox={`0 0 ${} ${}`}：図を書くための作業スペース※
    role="img"
    aria-label={`年輪${}本、目標${}日`}：読み上げソフトなどに伝える説明
>
※左上を0,0として横${},縦${}の範囲で図を書く
<circle //円を書く
    cx={center}：円の中心
    cy={center}：円の中心
    r={outerRadius}：半径
    fill={grayWholeTree ? '#c8ccc8' : '#f3d2b7'}：塗りつぶしの色
    stroke="none"：線の色
/>

関数の書き方
（exportと書くことで他のファイルから呼び出せる）
→ export default function 関数名({ ... }) 


関数の中身
→{呼び出し元から値を受け取る}=props
呼び出し元の値の渡し方
<呼び出し関数 変数={値}/>

メソッド
Number()：数値に変換する
|| 0：数値にできない場合は0
Math.floor()：小数を切り捨て
Math.max(0,)：マイナスなら0

return(画面を返す);

Array.form({length: count},...)：count回処理を回す
---------------------------------------------------




