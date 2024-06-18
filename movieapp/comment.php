<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql= "SELECT ";

} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    die();
}

// HTML全体をヒアドキュメントで定義する
$content = <<<HTML
<<form action="comment.php" method="get">

<table class="tt">
    <tr>
        <td>ニックネーム</td>
        <td><input type="text" name="name" value=""></td> 
    </tr>   
    <tr>
        <td>コメント</td>
        <td><input type="text" name="food" value=""></td> 
    </tr>
    <tr>
        <td><input type="submit" value="送信"></td>
        <input type="hidden" name="mode" value="register">
        <!-- どこの送信ボタンが押されたか識別するために上記の記述がある -->
    </tr>
</table>

</form>

HTML;


echo $content;
?>
