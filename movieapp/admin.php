<?php
session_start();

// データベース接続
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

// 映画情報が送信された場合の処理
if (isset($_GET['mode']) && $_GET['mode'] === 'register') {
    // フォームから送信された値を取得
    $title = $_GET['title'];
    $release = $_GET['release'];
    $director = $_GET['director'];
    $cast = $_GET['cast'];

    // 画面下に出力するためのHTMLを生成
    $newMovieInfo = "<h2>【追加された映画情報】</h2>";
    $newMovieInfo .= "<p>映画名: $title</p>";
    $newMovieInfo .= "<p>公開日: $release</p>";
    $newMovieInfo .= "<p>監督: $director</p>";
    $newMovieInfo .= "<p>キャスト: $cast</p>";

    // 既存の映画情報に追加
    $_SESSION['movieInfo'][] = $newMovieInfo;
}

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ユーザー情報を取得
    $stmt = $dbh->prepare("SELECT id, name, email FROM login");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userList = "";
    foreach ($users as $user) {
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
    <form method="get">
    <table class="tt">
    <tr>
    <td>映画名</td>
    <td><input type="text" name="title" value=""></td> 
    </tr>   
    <tr>
    <td>公開日</td>
    <td><input type="date" name="release" value=""></td> 
    </tr>
    <tr>
    <td>監督</td>
    <td><input type="text" name="director" value=""></td> 
    </tr>
    <tr>
    <td>キャスト</td>
    <td><input type="text" name="cast" value=""></td> 
    </tr>
    <tr>
    <td><input type="submit" value="送信"></td>
    <input type="hidden" name="mode" value="register">
    <!-- どこの送信ボタンが押されたか識別するために上記の記述がある -->
    </tr>
    </table>
    </form>
HTML;

// 映画情報の表示
if (!empty($_SESSION['movieInfo'])) {
    $content .= "<h2></h2>";
    foreach ($_SESSION['movieInfo'] as $movie) {
        $content .= $movie;
    }
}

$content .= <<<HTML
    </div>
    </div>
    </body>
    </html>
HTML;

echo $content;
?>
