<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>四コマ漫画辞典 投稿</title>
</head>


<?php
//セッションの開始
session_start();

// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS files"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."penname char(20),"
."title char(20),"
."genre char(7),"
."name1 char(255),"
."name2 char(255),"
."name3 char(255),"
."name4 char(255)"
.");";
$stmt = $pdo -> query($sql);

if(isset($_SESSION["password"])){//ログインしているとき
    $link1 = '<a href="4koma_home.php">ホーム</a>';
    $link2 = '<a href="4koma_logout.php">ログアウト</a>';
    echo $link1."    ".$link2."<hr>";
    echo "<br><span style='font-size:30px;'>四コマ漫画投稿</span><br><br>";
    if(isset($_POST['upload'])){//投稿ボタンが押されたとき
        if(!empty($_POST['penname']) and !empty($_POST['title']) and !empty($_POST['genre']) and !empty($_FILES['image1']['name']) and !empty($_FILES['image2']['name']) and !empty($_FILES['image3']['name']) and !empty($_FILES['image4']['name'])){
            $penname = $_POST['penname'];
            $title = $_POST['title'];
            $genre = $_POST['genre'];
            $image1 = $_FILES['image1']['tmp_name'];
            $image2 = $_FILES['image2']['tmp_name'];
            $image3 = $_FILES['image3']['tmp_name'];
            $image4 = $_FILES['image4']['tmp_name'];
            if(!file_exists('upload')){//uploadというファイルがなかったら
                mkdir('upload');
            }
            $newimage1 = uniqid(mt_rand(),true);//ファイル名をユニーク化する
            $newimage2 = uniqid(mt_rand(),true);
            $newimage3 = uniqid(mt_rand(),true);
            $newimage4 = uniqid(mt_rand(),true);

            function getExt($filename){//$filenameの拡張子を取得
                return pathinfo($filename,PATHINFO_EXTENSION);
            }
            //ユニーク化したファイル名に拡張子を結合する
            $newimage1 .= getExt($_FILES['image1']['name']);
            $newimage2 .= getExt($_FILES['image2']['name']);
            $newimage3 .= getExt($_FILES['image3']['name']);
            $newimage4 .= getExt($_FILES['image4']['name']);

            $sql = "INSERT INTO files(penname,title,genre,name1,name2,name3,name4) VALUES(:penname,:title,:genre,:name1,:name2,:name3,:name4)";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':penname',$penname,PDO::PARAM_STR);
            $stmt -> bindParam(':title',$title,PDO::PARAM_STR);
            $stmt -> bindParam(':genre',$genre,PDO::PARAM_STR);
            $stmt -> bindParam(':name1',$newimage1,PDO::PARAM_STR);
            $stmt -> bindParam(':name2',$newimage2,PDO::PARAM_STR);
            $stmt -> bindParam(':name3',$newimage3,PDO::PARAM_STR);
            $stmt -> bindParam(':name4',$newimage4,PDO::PARAM_STR);

            //画像かどうかを調べる
            if(exif_imagetype($image1) and exif_imagetype($image2) and exif_imagetype($image3) and exif_imagetype($image4)){
                move_uploaded_file($image1,'upload/'.$newimage1);
                move_uploaded_file($image2,'upload/'.$newimage2);
                move_uploaded_file($image3,'upload/'.$newimage3);
                move_uploaded_file($image4,'upload/'.$newimage4);
                $stmt -> execute();
                echo "投稿に成功しました。";
            }else{
                echo "画像以外のファイルが含まれています。";
            }
        }else{
            echo "投稿内容に不備があります。";
        }
    }
}else{
    $link = '<a href="4koma_login.php">ログイン</a>';
    echo $link."<hr>";
    echo 'ログインしていません。';
}

//テーブルの削除
//$sql = "DROP TABLE files";
//$stmt = $pdo -> query($sql);

//テーブルの中身表示
//$sql = "SELECT * FROM files";
//$stmt = $pdo -> query($sql);
//$result = $stmt -> fetchAll();
//foreach($result as $row){
//    echo $row["penname"].",".$row["name1"]."<br>";
//}
//$sql = 'SHOW TABLES';
//$result = $pdo -> query($sql);
//foreach($result as $row){
//    echo $row[0]."<br>";
//}
?>

<body>
投稿する画像は縦横比3：5推奨です。。<br><br>
<form action="" method="post" enctype="multipart/form-data">
  <input type="text" name="penname" placeholder="ペンネーム"><br>
  <input type="text" name="title" placeholder="タイトル"><br><br>
  <label for="name">ジャンル:</label>
  <select name="genre">
    <option value="ギャグ系">ギャグ系</option>
    <option value="ほっこり系">ほっこり系</option>
    <option value="ラブコメ系">ラブコメ</option>
    <option value="青春系">青春</option>
    <option value="サスペンス系">サスペンス系</option>
  </select><br><br>
  <label for="name">画像1:</label>
  <input type="file" name="image1" placeholder="起"><br>
  <label for="name">画像2:</label>
  <input type="file" name="image2" placeholder="承"><br>
  <label for="name">画像3:</label>
  <input type="file" name="image3" placeholder="転"><br>
  <label for="name">画像4:</label>
  <input type="file" name="image4" placeholder="結">
  <input type="submit" name="upload" value="投稿">

</body>
</html>