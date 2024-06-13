document.getElementById("favoriteButton").addEventListener("click", function() {
    // ボタンがクリックされたときの処理を記述
    var button = document.getElementById("favoriteButton");
    if (button.classList.contains("active")) {
        // すでにアクティブな場合は非アクティブにする
        button.classList.remove("active");
        // お気に入り状態をサーバーに送信するなどの処理を追加
        // ここにサーバーへのリクエストなどを追加
    } else {
        // アクティブでない場合はアクティブにする
        button.classList.add("active");
        // お気に入り状態をサーバーに送信するなどの処理を追加
        // ここにサーバーへのリクエストなどを追加
    }
});
