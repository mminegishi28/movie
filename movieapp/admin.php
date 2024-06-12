<?php
session_start();

// データベース接続
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 映画情報が送信された場合の処理
    if (isset($_POST['mode']) && $_POST['mode'] === 'register') {
        // 既存の映画情報が3つ以上ある場合は古い情報を削除
        if (count($_SESSION['movieInfo']) >= 3) {
            array_shift($_SESSION['movieInfo']);
        }

        // フォームから送信された値を取得
        if(isset($_POST['title'], $_POST['opening'], $_POST['director'], $_POST['summary'], $_FILES['image_data'])) {
            $title = $_POST['title'];
            $opening = $_POST['opening'];
            $director = $_POST['director'];
            $summary = $_POST['summary'];
            $imagePath = "images/" . basename($_FILES["image_data"]["name"]); // 画像の保存先パス
            move_uploaded_file($_FILES["image_data"]["tmp_name"], $imagePath); // 画像を保存
        }

        // 映画情報を取得
        $moviesql = "INSERT INTO `movies`(`title`, `opening`, `director`, `summary`, `image_data`) VALUES ('$title','$opening','$director','$summary','$imagePath')";
        //プレイスホルダー
        $moviesStmt = null;
        $moviesStmt = $dbh->prepare($moviesql); // prepareメソッドを使用してクエリを準備
        $moviesStmt->execute();
        
        $imagePath = '';
        if(isset($_FILES) && $_FILES['image_data']['error'] === UPLOAD_ERR_OK) {
            $tempName = $_FILES['image_data']['tmp_name'];
            $imageName = $_FILES['image_data']['name'];
            $imagePath = "images/" . basename($_FILES["image_data"]["name"]); // 画像の保存先パス
            move_uploaded_file($tempName, $imagePath); // 画像を保存
        }

        // 画面下に出力するためのHTMLを生成
        $newMovieInfo = "<h2>【追加された映画情報】</h2>";
        $newMovieInfo .= "<p>映画名: $title</p>";
        $newMovieInfo .= "<p>公開日: $opening</p>";
        $newMovieInfo .= "<p>監督: $director</p>";
        $newMovieInfo .= "<p>あらすじ: $summary</p>";
        $newMovieInfo .= "<p>フライヤー: <img src = '$imagePath' alt='$imagePath'  height='500'</p>";

        // 既存の映画情報に追加
        $_SESSION['movieInfo'][] = $newMovieInfo;
    }

    // ユーザー情報を取得
    $loginstmt = $dbh->prepare("SELECT id, name,email FROM login");//まちがえ
    $loginstmt->execute();
    $loginusers = $loginstmt->fetchAll(PDO::FETCH_ASSOC);

    $userList = "";
    foreach ($loginusers as $user) {
        $userList .= "<td>" . htmlspecialchars($user['id']) . "</td>";
        $userList .= "<td>" . htmlspecialchars($user['name']) . "</td>";
        $userList .= "<td>" . htmlspecialchars($user['email']) . "</td>";
        $userList .= "</tr>"; // emailの後に空のtdを挿入して改行を実現
    }

} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    die();
}

$content = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" href="admin.css"> -->
        <title>管理者画面</title>
    </head>
    <body style="background:antiquewhite;">
    <h1 style="text-align: center;">管理者画面</h1>
    <div style="display: flex;">
    <div style="flex: 1; margin-right: 20px;">
    <h2>【ユーザーテーブル】</h2>
    <table>
    <tr>
    <th>ID</th>
    <th>ユーザー名</th>
    <th>メールアドレス</th>
    <!-- 他に表示したいユーザー情報があればここに追加 -->
    </tr>
    <tr>
    {$userList}
    <!-- 他に表示したいユーザー情報があればここに追加 -->
    </tr>
    </table>
    </div>
    <div style="flex: 1;">
    <h2>【映画情報追加】</h2>
    <form action="./admin.php" method="post" enctype="multipart/form-data">
    <table class="tt">
    <tr>
    <td>映画名</td>
    <td><input type="text" name="title" value=""></td> 
    </tr>   
    <tr>
    <td>公開日</td>
    <td><input type="date" name="opening" value=""></td> 
    </tr>
    <tr>
    <td>監督</td>
    <td><input type="text" name="director" value=""></td> 
    </tr>
    <tr>
    <td>あらすじ</td>
    <td><input type="text" name="summary" value=""></td> 
    </tr>
    <tr>
    <td>フライヤー</td>
    <td><input type="file" name="image_data" value=""></td> 
    </tr>
    <tr>
    <td><input type="submit" value="送信"></td>
    <input type="hidden" name="mode" value="register">
    <!-- どこの送信ボタンが押されたか識別するために上記の記述がある -->
    </tr>
    </table>
    </form>

    <!-- 削除ボタン -->
    <!-- <div style="flex: 1;">
    <h2>【映画情報削除】</h2>
    <?php foreach ($movies as $movie): ?>
        <form action="" method="post"> -->
            <!-- エラー -->
            <!-- <input type="hidden" name="movie_id" value="<?php echo $moviesql['title']; ?>">
            <button type="submit" name="delete_btn">削除</button>
        </form>
    <?php endforeach; ?> -->
    </div>

    </div>
    </body>
    </html>
HTML;

echo $content;
?>
