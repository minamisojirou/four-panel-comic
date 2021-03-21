<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>四コマ漫画辞典</title>
</head>

<body>
<?php
//セッションの開始
session_start();

// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if(isset($_SESSION["password"])){//ログインしているとき
   $link1 = '<a href="4koma_home.php">ホーム</a>';
   $link2 = '<a href="4koma_logout.php">ログアウト</a>';
   echo $link1."    ".$link2."<hr>";
//テーブルの中身表示
   $sql = "SELECT * FROM files WHERE genre=:genre";
   $stmt = $pdo -> prepare($sql);
   $stmt -> bindParam(':genre',$genre,PDO::PARAM_STR);
   $genre = $_GET['parameter'];
   $stmt -> execute();
   $result = $stmt -> fetchAll();
   foreach($result as $row){
?>
      <a href="4koma_main.php?parameter=<?php echo $row['title']; ?>" target="_blank"><img src="upload/<?php echo $row['name1']; ?>" alt="画像" width="200" height="120"></a><br>
<?php
      echo $row['title']."/".$row['penname'];
      echo "<br>";
   }
}else{
    echo "ログインしていません";
}
?>
</body>
</html>