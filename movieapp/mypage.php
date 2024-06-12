<!DOCTYPE html>
<html>
<head>
    <meta charset="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>検索結果</title>
    <style>
        .movie-item {
            display: flex;
            align-items: center;
        }

        .movie-image {
            flex: 1;
        }

        .movie-info {
            flex: 1;
            padding-left: 20px; /* 画像と情報の間のスペースを設定 */
        }

        .movie-info p {
            margin: 0; /* パラグラフの余白をリセット */
        }
        .title{
            font-size: 30px;
        }
    </style>
</head>
<body>

<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // クエリ文字列から検索キーワードを取得
    if(isset($_GET['query'])) {
        $query = $_GET['query'];

        // ここで検索処理を行い、結果を表示する
        // 例：データベースから情報を検索して表示する
        echo "<p>「" . $query . "」の検索結果:</p>";

        if (empty($_GET["query"])) {
            $sql = "SELECT * FROM movies";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='movie-item'>";
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='500'></div>";
                echo "<div class='movie-info'>";
                echo "<p>{$row["title"]}</p>";
                echo "<p>公開日：{$row["opening"]}</p>";
                echo "<p>監督：{$row["director"]}</p>";
                echo "<p>あらすじ：{$row["summary"]}</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            $keyword = '%' . $_GET["query"] . '%'; // プレースホルダの値を準備
            $sql = "SELECT * FROM movies WHERE title LIKE ?";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $keyword);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='movie-item'>";
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='500'></div>";
                echo "<div class='movie-info'>";
                echo "<div class='title'><p>【{$row["title"]}】</p></div>";
                echo "<p>公開日：{$row["opening"]}</p>";
                echo "<p>監督：{$row["director"]}</p>";
                echo "<p>あらすじ：{$row["summary"]}</p>";
                echo "</div>";
                echo "</div>";
            }
        }
    } else {
        echo "<p>検索キーワードが指定されていません。</p>";
    }

} catch (PDOException $e) {
    echo "接続失敗・・・";
    echo "エラー内容：" . $e->getMessage();
}
?>

<button id="favoriteButton" class="favorite-button">
    <span class="star">&#9733;</span> 
</button>

<button type="button" onclick="history.back()">前のページへ戻る</button>
</body>
</html>
