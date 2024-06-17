<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を修正して特定の条件を満たすレコードを取得する
    $sql = "SELECT title, image_data FROM movies WHERE favflag = 0";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 結果をテーブルの行として格納するための変数
    $result = "<div class='movie-container'>"; // 映画を4つずつ並べるためのコンテナ開始
    $count = 0; // カウンタ初期化
    foreach ($results as $row) {
        if ($count % 4 == 0) {
            // 新しい行を開始
            $result .= "<div class='movie-row'>";
        }
        $title = htmlspecialchars($row['title']); // タイトルをHTMLエスケープ
        $image = htmlspecialchars($row['image_data']); // 画像データをHTMLエスケープ
        $result .= "<div class='movie-item'>";
        $result .= "<p>{$title}</p>";
        $result .= "<img src='{$image}' alt='{$title}' height='250'>";
        $result .= "</div>";
        $count++;
        if ($count % 4 == 0) {
            // 行を閉じる
            $result .= "</div>";
        }
    }
    if ($count % 4 != 0) {
        // 最後の行が4で割り切れない場合、閉じる
        $result .= "</div>";
    }
    $result .= "</div>"; // 映画コンテナを閉じる

} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    die();
}

// HTML全体をヒアドキュメントで定義する
$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="watch.css">
    
    <title>Watch List</title>
</head>
<body>
    <h1>Watch List</h1>
    <h2>【お気に入りボタン】</h2>
    <h4>---------  お気に入りに追加しました。--------</h4>
    {$result} 

    <button type="button" onclick="history.back()">前のページへ戻る</button>
    <br>
    <a href="mypage.php" class="back-button">検索ページに戻る</a>

</body>
</html>
HTML;


echo $content;
?>
