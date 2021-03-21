<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>四コマ漫画辞典 ホーム画面</title>
</head>

<body>
<?php
//セッション開始
session_start();
if(isset($_SESSION["password"])){//ログインしているとき
    $username = $_SESSION["username"];
    $link = '<a href="4koma_logout.php">ログアウト</a>';
    echo $link."<hr>";
    echo 'こんにちは ' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . ' さん<br><br>';

    echo'<a href="4koma_genre.php?parameter=ギャグ系">ギャグ系</a><br>';
    echo'<a href="4koma_genre.php?parameter=ほっこり系">ほっこり系</a><br>';
    echo'<a href="4koma_genre.php?parameter=ラブコメ系">ラブコメ系</a><br>';
    echo'<a href="4koma_genre.php?parameter=青春系">青春系</a><br>';
    echo'<a href="4koma_genre.php?parameter=サスペンス系">サスペンス系</a><br><br><br>';
?>
    <button style = "width:100px;height:50px;" onclick = "location.href = '4koma_toukou.php'">新規投稿</button>
<?php
}else{//ログインしていないとき
    $link = '<a href="4koma_login.php">ログイン</a>';
    echo $link."<hr>";
    echo 'ログインしていません';
}
?>