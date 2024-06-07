# movie

データベースの接続
```
```
- 検索欄に値がなければ
通常のselect文で全部呼び出す

- もし値があったら
whereで指定する

```
if(empty($_GET["keyword"])){
    $sql = "SELECT * FROM movies";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
} else{
    $sql = "SELECT * FROM movies WHERE title LIKE %?%";
    $stmt->bindParam(1,$_GET["keyword"]);
    $stmt->execute();
}
フェッチして
```
形式を整える
```
$search_elements = "";
$search_elements += "<ul>"
foreach($result as $value){
    $search_elements.="<li>";
    $search_elements.="<a href='content.php?id={$value["id"]}'>";
    $search_elements.="{$value["name"]}";
    $search_elements.="</a>";
    $search_elements.= "</li>";
}
$search_elements += "</ul>";
```
```
<a href='content.php?id={$value["id"]}>'>$value["name"]</a>
```