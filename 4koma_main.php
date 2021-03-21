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

//選択された画像データの抽出
$sql = "SELECT * FROM files WHERE title=:title";
$stmt = $pdo -> prepare($sql);
$stmt -> bindParam(':title',$title,PDO::PARAM_STR);
$title = $_GET['parameter'];
$stmt -> execute();
$reason = $stmt -> fetch();
?>

<h2><?php echo $reason['penname'].":".$reason['title']; ?><h2>
<img src="upload/<?php echo $reason['name1']; ?>" width="500" height="300"><br>
<img src="upload/<?php echo $reason['name2']; ?>" width="500" height="300"><br>
<img src="upload/<?php echo $reason['name3']; ?>" width="500" height="300"><br>
<img src="upload/<?php echo $reason['name4']; ?>" width="500" height="300">

</body>
</html>