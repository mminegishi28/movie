<!DOCTYPE html>
<html>
<head>
    <title>検索結果</title>
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
                echo "<li><a href='content.php?id={$row["id"]}'>{$row["name"]}<br><img src= {$row["image_date"]} alt={$row["image_date"]} height='5'></a></li>";
            }
           
        } else {
            
            $keyword = '%' . $_GET["query"] . '%'; // プレースホルダの値を準備
            $sql = "SELECT * FROM movies WHERE name LIKE ?";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $keyword);
            $stmt->execute();

           
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<li><a href='content.php?id={$row["id"]}'>{$row["name"]}<br><img src= {$row["image_date"]} alt={$row["image_date"]} height='500'></a></li>";
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
<span class="favoritedmark fade">★</span>
    <button class="btn btn-primary addtofavorite">お気に入りに登録</button>
    <button class="btn btn-primary removefavorite hidden">お気に入りから外す</button>
<button type="button" onclick="history.back()">前のページへ戻る</button>
</body>
</html>
