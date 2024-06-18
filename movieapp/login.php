<?php
session_start();

$input_name =$_POST["name"];
$input_email =$_POST["email"];
$input_nickname =$_POST["nickname"];

// データベースに接続
$dsn="mysql:host=localhost;dbname=movie;charset=utf8";
$user="testuser";
$pass="testpass";

try{
    $dbh=new PDO($dsn,$user,$pass);

     // フォームから送信されたデータを受け取る処理
     if (isset($_POST["id"])) 
     {
         $userid = $_POST["userid"];
     }

    $sql=<<<sql
    SELECT * FROM login WHERE name=?;
    sql;
    $stmt=$dbh->prepare($sql);
    
    //プレイスホルダーに値を紐づける
    $stmt->bindParam(1,$input_name);
    $result = $stmt->execute(); //sqlに入っている値をresultに入れる
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);//連想配列にしている
    
    //ちゃんと配列をどこから取るか指定する[0]
    if (!empty($result) && $input_name == "admin" &&  isset($result[0]["email"]) && $input_email == $result[0]["email"]) {
        header("Location: admin.php");
        exit; //管理者画面
    } else if(!empty($result) && isset($result[0]["email"]) && $input_email == $result[0]["email"]) {
        $_SESSION["id"] = $result[0]["id"];
        $_SESSION["name"] = $result[0]["name"];
        header("Location:watch.php");
        exit; //ユーザー画面
    }else {
        // ログインに失敗した場合の処理
        echo "ログインに失敗しました";
    }

}catch(PDOException $e){
    echo "接続失敗・・・";
    echo "エラー内容：".$e->getMessage();
}






