<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fav.css">
    <title>検索結果</title>
</head>
<body>
<h1>My Page</h1>

<form method="GET" action="mypage.php">
    <input type="text" name="query" placeholder="検索キーワードを入力してください">
    <input type="submit" value="検索">
</form>

<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

$id = null;

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // クエリ文字列から検索キーワードを取得
    if(isset($_GET['query'])) {
        $query = $_GET['query'];

        // ここで検索処理を行い、結果を表示する
        echo "<p>「" . $query . "」の検索結果:</p>";

        if (empty($_GET["query"])) {
            $sql = "SELECT * FROM movies";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row["id"];
                echo "<div class='movie-item'>";
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='500'></div>";
                echo "<div class='movie-info'>";
                echo "<p>{$row["title"]}</p>";
                echo "<p>【公開日】：{$row["opening"]}</p>";
                echo "<p>【監督】：{$row["director"]}</p>";
                echo "<p>【あらすじ】：{$row["summary"]}</p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            $keyword = '%' . $_GET["query"] . '%';
            $sql = "SELECT * FROM movies WHERE title LIKE ?";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(1, $keyword);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row["id"]; // お気に入りにボタン用
                echo "<div class='movie-item'>";
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='500'></div>";
                echo "<div class='movie-info'>";
                echo "<div class='title'><p>【{$row["title"]}】</p></div>";
                echo "<p>公開日：{$row["opening"]}</p>";
                echo "<p>監督：{$row["director"]}</p>";
                echo "<p>あらすじ：{$row["summary"]}</p>";
                
                // コメント送信フォームの追加
                echo "<form method='POST'>";
                echo "<div class='tt'><textarea name='comment' style='width:300px; height:130px'></textarea></div>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<div class='text'><input type='submit' value='送信'></div>";
                echo "</form>";
                
                echo "</div>";
                echo "</div>";

                echo "<form action='up_fav.php' method='post'><button type='submit' name='update_fav_flag' value='$id'>お気に入りに登録</button></form>";
                echo "<button type='button' onclick='history.back()'>前のページへ戻る</button>";
            }
        }
    }

    // POSTリクエストで送信されたコメントとIDの取得
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['id'])) {
        $comment = $_POST['comment'];
        $id = $_POST['id'];

        try {
            // データベースにコメントを挿入（重複していても無視して挿 入を試みる）
            $sql_insert = "UPDATE  login SET comment=:comment WHERE id=:id";
            $stmt_insert = $dbh->prepare($sql_insert);
            $stmt_insert->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt_insert->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt_insert->execute()) {
                echo "<p>コメントを登録しました。</p>";
            } else {
                echo "<p>コメントの登録に失敗しました。</p>";
            }
        } catch (PDOException $e) {
            echo "エラー内容：" . $e->getMessage();
        }
    }

} catch (PDOException $e) {
    echo "接続失敗・・・";
    echo "エラー内容：" . $e->getMessage();
}
?>

</body>
</html>
