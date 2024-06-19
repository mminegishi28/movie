<?php
// セッションを開始または既存のセッションを再開
session_start();

// セッション変数を全て削除
// $_SESSION = array();

// セッションを破棄
session_destroy();

// ログアウト後にトップページにリダイレクト
header("Location: login.html");
exit;
?>