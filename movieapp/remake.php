<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try{
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo $_POST["edit"];
    if(isset($_POST['edit'])){
        $id=$_POST['id'];
        $name=$_POST['name'];
        $email=$_POST['email'];
        $comment=$_POST['comment'];

    

        $sql = "UPDATE login SET name='$name',email='$email',comment='$comment' WHERE id=$id";
    
        if($dbh->query($sql) == TRUE){
            echo '<script>alert("データを更新しました");</script>';
        }else{
            echo "Error:".$sql."<br>". $dbh->$error;
        }

    }

    $sql="SELECT id,name,email,comment FROM login";
    $result= $dbh->query($sql);

    $loginList="";
    if ($result->rowCount() > 0){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $loginList .="<tr>";
            $loginList .="<td>".$row["id"]."</td>";
            $loginList .="<td>".$row["name"]."</td>";
            $loginList .="<td>".$row["email"]."</td>";
            $loginList .="<td>".$row["comment"]."</td>";
            $loginList .='<td><form method="post" action="remake.php"><input type="hidden" name="id" value="'.$row["id"].'"><input type="text" name="name" value="'.$row["name"].'"><input type="text" name="email" value="'.$row["email"].'"><input type="text" name="comment" value="'.$row["comment"].'"><input type="submit" name="edit" value="更新"></form></td>';
            $loginList .="</tr>";
        }
    }


    $sql= "SELECT id,name,email,comment FROM login WHERE id = ?";
    //プレイスホルダー設定
    
    $stmt=$dbh->prepare($sql);
    $id = $_POST['id']; // フォームから送信されたIDを取得
    $stmt->bindParam(1, $id);

    


}catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    die();
}

$content = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="mypage.css">
    <title>Document</title>
</head>
<body>
    <h1>編集ページ</h1>
    <h2>【ユーザーテーブル】</h2>
    <table>
    <tr>
    <th>ID</th>
    <th>ユーザー名</th>
    <th>メールアドレス</th>
    <th>コメント</th>
    </tr>
    <tr>
    {$loginList}
    </tr>
    </table>
</body>



HTML;
echo $content;
?>