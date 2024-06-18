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
        if (isset($_SESSION['movieInfo']) && count($_SESSION['movieInfo']) >= 3) {
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

        $sql = "SELECT * FROM movies WHERE flag=1";
        //プレイスホルダー
        $stmt = null;
        $stmt = $dbh->prepare($sql); // prepareメソッドを使用してクエリを準備
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);




        // 画面下に出力するためのHTMLを生成
        $newMovieInfo = "<h2>【追加された映画情報】</h2>";
        $newMovieInfo .= "<p>映画名: $title</p>";
        $newMovieInfo .= "<p>公開日: $opening</p>";
        $newMovieInfo .= "<p>監督: $director</p>";
        $newMovieInfo .= "<p>あらすじ: $summary</p>";
        $newMovieInfo .= "<p>フライヤー: <img src = '$imagePath' alt='$imagePath'  height='500'</p>";
        $inputForm = "<form action=\"admin.php\" method=\"get\">";
        foreach($result as $rows){
            if($rows["flag"] == 1){
                $inputForm.="<input type=\"submit\" value=\"削除\">";
                $inputForm.="<input type=\"hidden\" name=\"mode\" value=\"delete\">";
                $inputForm.="<input type=\"hidden\" name=\"id\" value=\"{$rows['id']}\"";
            }
        }
        $inputForm .= "</form>";
        $newMovieInfo .= $inputForm;

        


        // 既存の映画情報に追加
        $_SESSION['movieInfo'][] = $newMovieInfo;
    }

    // ユーザー情報を取得
    $loginstmt = $dbh->prepare("SELECT id, name,email,comment FROM login");
    $loginstmt->execute();
    $loginusers = $loginstmt->fetchAll(PDO::FETCH_ASSOC);

    $userList = "";
    foreach ($loginusers as $user) {
        $userList .= "<td>" . htmlspecialchars($user['id']) . "</td>";
        $userList .= "<td>" . htmlspecialchars($user['name']) . "</td>";
        $userList .= "<td>" . htmlspecialchars($user['email']) . "</td>";
        $userList .= "</tr>"; // emailの後に空のtdを挿入して改行を実現
    }

   

            //ユーザを無効にするための処理
        if (isset($_GET["mode"]) && $_GET["mode"] == "delete")
        {
            //sql文を用意
            $sql=<<<sql
            UPDATE movies
            SET flag = 0;
            WHERE id = ?;
sql;

            $stmt=$dbh->prepare($sql);
            $stmt->bindParam(1,$_GET["id"]);
            //sql実行
            $stmt->execute();
        }
        // 表示のやつを書く
        /// sql文（flagが1のものをすべて持ってくる。idで逆順。上から3つ）
        $sql = "SELECT * FROM movies WHERE flag = 1 ORDER BY id DESC LIMIT 3;";
        /// prepare と execute
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
        $showTable = "";
        foreach($result as $row){
            $showTable.= "<h2>【追加された映画情報】</h2>";
            $showTable .= "<p>映画名: {$row["title"]}</p>";
            $showTable .= "<p>公開日: {$row["opening"]}</p>";
            $showTable .= "<p>監督: {$row["director"]}</p>";
            $showTable .= "<p>あらすじ: {$row["summary"]}</p>";
            $showTable .= "<p>フライヤー: <img src = '{$row["image_data"]}' alt='{$row["image_data"]}'  height='500'</p>";
            $showTable .= "<form action=\"admin.php\" method=\"get\">";
            $showTable .= "<input type=\"submit\" value=\"削除\">";
            $showTable .= "<input type=\"hidden\" name=\"mode\" value=\"delete\">";
            $showTable .= "<input type=\"hidden\" name=\"id\" value=\"{$row['id']}\"";
            $showTable .= "<br>";
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
        <title>管理者画面</title>
        <style>
            body{
                background: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%) fixed; 
            }
            h1{
                text-align: center;
            }
        </style>
    </head>
    <body>

    <h1>【管理者画面】</h1>
    <!-- <div style="text-align: center;">
    <a href="login.php">ログアウト </a>
    </div> -->
   
    <div style="display: flex;">
    <div style="flex: 1;  margin-left:90px; line-height: 2.5;">
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
    </tr>
    </table>
    <!-- 編集ボタン -->
    <button calss="bun" onclick="location.href='./remake.php'">編集ボタン</button>

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
    {$showTable}


HTML;

$content .= <<<HTML
    </div>
    </div>
    </body>
    </html>
    
HTML;

echo $content;
?>

     <!-- mmmmahh228@gmail.com -->