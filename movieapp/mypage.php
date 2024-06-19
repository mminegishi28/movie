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

    session_start();

    try {
        $dbh = new PDO($dsn, $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ログインしているかどうかを確認する
        if (!isset($_SESSION['id'])) {
            // ログインしていない場合、ログインページにリダイレクトするなどの処理を行う
            exit;
        }
    
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
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='600'></div>";
                echo "<div class='movie-info'>";
                echo "<p>{$row["title"]}</p>";
                echo "<p>【公開日】：{$row["opening"]}</p>";
                echo "<p>【監督】：{$row["director"]}</p>";
                echo "<p>【あらすじ】：{$row["summary"]}</p>";
                
                // コメントフォームの追加
                echo "<form method='POST'>";
                echo "<textarea name='comment' style='width:300px; height:130px'></textarea><br>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<input type='submit' value='コメントを送信'>";
                echo "</form>";

                // コメント一覧の表示
                $sql_comments = "SELECT * FROM movies_comment WHERE movie_id = :movie_id";
                $stmt_comments = $dbh->prepare($sql_comments);
                $stmt_comments->bindParam(':movie_id', $id, PDO::PARAM_INT);
                $stmt_comments->execute();

                echo "<div class='comments-section'>";
                echo "<h3>コメント一覧</h3>";
                while ($comment_row = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='comment'>";
                    echo "<p>【名前】: {$comment_row['login_id']}</p>";
                    echo "<p>【コメント】: {$comment_row['comment']}</p>";
                    echo "</div>";
                }
                echo "</div>";

                echo "</div>"; // movie-info
                echo "</div>"; // movie-item
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
                echo "<div class='movie-image'><img src='{$row["image_data"]}' alt='{$row["title"]}' height='700'></div>";
                echo "<div class='movie-info'>";
                echo "<div class='title'><p>【{$row["title"]}】</p></div>";
                echo "<p>公開日：{$row["opening"]}</p>";
                echo "<p>監督：{$row["director"]}</p>";
                echo "<p>あらすじ：{$row["summary"]}</p>";
                
                // コメントフォームの追加
                echo "<form method='POST'>";
                echo "<textarea name='comment' style='width:300px; height:130px'></textarea><br>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<input type='submit' value='コメントを送信'>";
                echo "</form>";

                $sql_comments = "SELECT movies_comment.*, login.nickname FROM login INNER JOIN movies_comment ON login.id = movies_comment.login_id WHERE movie_id = :movie_id";
                
                $stmt_comments = $dbh->prepare($sql_comments);
                $stmt_comments->bindParam(':movie_id', $id, PDO::PARAM_INT);
                $stmt_comments->execute();

                echo "<div class='comments-section'>";
                echo "<h3>【コメント一覧】</h3>";
                while ($comment_row = $stmt_comments->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='comment'>";
                    echo "<p>【名前】: {$comment_row['nickname']}</p>";
                    echo "<p>【コメント】: {$comment_row['comment']}</p>";
                    echo "</div>";
                }
                echo "</div>";

                echo "</div>"; // movie-info
                echo "</div>"; // movie-item


                //お気に入りボタン
                echo "<form action='up_fav.php' method='post'><button type='submit' name='update_fav_flag' value='$id'>お気に入りに登録</button></form>";
                echo "<form action='watch.php'><button type='submit'>前のページに戻る</button></form>";

            }
        }
    }
      

    // POSTリクエストで送信されたコメントとIDの取得
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['id'])) {
        $comment = $_POST['comment'];
        $id = $_POST['id'];
       
        try {
            // データベースにコメントを挿入
            $sql_insert = "INSERT INTO movies_comment(login_id, movie_id, comment) VALUES(:userid, :id, :comment)";
            $stmt_insert = $dbh->prepare($sql_insert);
            $stmt_insert->bindParam(':userid', $_SESSION["id"], PDO::PARAM_INT);
            $stmt_insert->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_insert->bindParam(':comment', $comment, PDO::PARAM_STR);

            // リダイレクト前に検索語を取得
            $search_query = isset($_GET['query']) ? $_GET['query'] : '';
            
            if ($stmt_insert->execute()) {
                // コメントを登録した後のリダイレクト
                echo "<p>コメントを登録しました。</p>";
                echo "<script>window.location.href = 'mypage.php?query=" . urlencode($search_query) . "';</script>";
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
