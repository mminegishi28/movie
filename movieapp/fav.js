// お気に入りボタンの要素を取得
var favoriteButton = document.getElementById('favoriteButton');

// お気に入りボタンがクリックされた時の処理
favoriteButton.addEventListener('click', function() {
    // お気に入りに追加する処理をここに記述

    // 例えば、ローカルストレージを使ってお気に入りの状態を保存する場合：
    var isFavorite = localStorage.getItem('isFavorite');

    // お気に入りの状態を反転させる
    isFavorite = !isFavorite;

    // 反転したお気に入りの状態を保存
    localStorage.setItem('isFavorite', isFavorite);

    // ボタンのテキストを更新する
    if (isFavorite) {
        favoriteButton.textContent = 'お気に入りから削除';
    } else {
        favoriteButton.textContent = 'お気に入りに追加';
    }
});
