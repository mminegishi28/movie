<?php


// データベースに接続
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // ボタンが押されたかどうかをチェック
    if (isset($_POST['update_fav_flag'])) {
        // ここでidに対応する行を持ってくる
        $sql="select * from movies where id = {$_POST['update_fav_flag']}";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetch();
        if($result['favflag'] == 0){
             // もしfavflag == 0
            $sql = "UPDATE movies SET favflag = 1 WHERE id = {$_POST['update_fav_flag']}"; // favflagが1のレコードを0に更新
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
        }else{
            // もしfavflag == 1
            $sql = "UPDATE movies SET favflag = 0 WHERE id = {$_POST['update_fav_flag']}"; // favflagが1のレコードを0に更新
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
        }
       

        // 更新成功メッセージを表示
        echo "favflagが更新されました。";
    } else {
        // ボタンが押されていない場合の処理
        echo "お気に入り登録ボタンが押されていません。";
    }
} catch (PDOException $e) {
    // エラーが発生した場合の処理
    echo "エラーが発生しました：" . $e->getMessage();
}
?>
