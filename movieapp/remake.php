<?php
$dsn = "mysql:host=localhost;dbname=movie;charset=utf8";
$user = "testuser";
$pass = "testpass";

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        $sql = "UPDATE login SET name=?, email=? WHERE id=?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$name, $email, $id]);

        echo '<script>alert("データを更新しました");</script>';
    }

    $sql = "SELECT id, name, email FROM login";
    $stmt = $dbh->query($sql);

    $loginList = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $loginList .= "<tr>";
        $loginList .= "<td>".$row["id"]."</td>";
        $loginList .= "<td>".$row["name"]."</td>";
        $loginList .= "<td>".$row["email"]."</td>";
        $loginList .= '<td><form method="post" action="remake.php"><input type="hidden" name="id" value="'.$row["id"].'"><input type="text" name="name" value="'.$row["name"].'"><input type="text" name="email" value="'.$row["email"].'"><input type="submit" name="edit" value="更新"></form></td>';
        $loginList .= "</tr>";
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
    <title>編集ページ</title>
    <style>
        body {
            text-align: center;
            background: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%) fixed;
        }
        .user {
            text-align: center;
            line-height: 3.0;
        }
        table {
            margin: auto;
            padding: auto;
        }
        h1 {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>編集ページ</h1>
    <h2>【ユーザーテーブル】</h2>
    <table class="user">
        <tr>
            <th>ID</th>
            <th>ユーザー名</th>
            <th>メールアドレス</th>
        </tr>
        {$loginList}
    </table>
    <a href="admin.php">前のページに戻る</a>
</body>
</html>
HTML;

echo $content;
?>
