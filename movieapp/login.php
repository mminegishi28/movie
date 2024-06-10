<?php

$input_name =$_POST["name"];
$input_email =$_POST["email"];
$input_nickname =$_POST["nickname"];

// データベースに接続
$dsn="mysql:host=localhost;dbname=movie;charset=utf8";
$user="testuser";
$pass="testpass";

try{
    $dbh=new PDO($dsn,$user,$pass);
    $sql=<<<sql
    SELECT * FROM login WHERE name=?;
    sql;
    $stmt=$dbh->prepare($sql);
    
    //プレイスホルダーに値を紐づける
    $stmt->bindParam(1,$input_name);
    $result = $stmt->execute(); //sqlに入っている値をresultに入れる
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);//連想配列にしている
    
    //ちゃんと配列をどこから取るかしていする[0]
    if (!empty($result) && isset($result[0]["email"]) && $input_email == $result[0]["email"]) {
        header("Location:mypage.html");
        exit;
    } else {
        // ログインに失敗した場合の処理
        echo "ログインに失敗しました";
    }

}catch(PDOException $e){
    echo "接続失敗・・・";
    echo "エラー内容：".$e->getMessage();
}






