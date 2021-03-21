<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>四コマ漫画辞典 新規登録</title>
</head>

<body>

<br><span style='font-size:30px;'>新規登録</span><br><br>

<?php
    // セッション開始
    session_start();

    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS userData"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."password char(255),"
    ."created DATETIME"
    .");";
    $stmt = $pdo -> query($sql);

    //時間データの取得
    $DATETIME = new DateTIme();
    $DATETIME = $DATETIME -> format("Y-m-d H:i:s");

    // フォームからの情報受け取り
    if(!empty($_POST["name"]) or !empty($_POST["password"]) or !empty($_POST["repassword"])){
       $username = $_POST["name"];
       $userpass = $_POST['password'];
       $userpass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
       $reuserpass = $_POST['repassword'];
       $reg = $_POST["registration"];
    }

    // 新規登録ボタンが押されたとき
    if(isset($reg)){
        if(!empty($username) or !empty($userpass) or !empty($reuserpass)){
            if(empty($username)){
                echo "ユーザ名が未入力です。<br>";
            }elseif(empty($userpass)){
                echo "パスワードが未入力です。<br>";
            }elseif(empty($reuserpass)){
                echo "パスワード確認用が未入力です。";
            }
        }
        if(!empty($username) and !empty($userpass) and !empty($reuserpass)){
            if(preg_match("/[a-z0-9_]{8,}$/i", $userpass)) {
               $sql = "SELECT * FROM userData WHERE name=:name";
               $stmt = $pdo -> prepare($sql);
               $stmt -> bindParam(":name",$name,PDO::PARAM_STR);
               $name = $username;
               $stmt -> execute();
               $result = $stmt -> fetch();
                  if($result["name"] === $username){
                      echo "同じユーザー名が存在しています。";
                  }else{
                      if(password_verify($reuserpass,$userpass_hash)){
                          $sql = "INSERT INTO userData(name,password,created) VALUES(:name,:password,:created)";
                          $stmt = $pdo -> prepare($sql);
                          $stmt -> bindParam(":name",$name,PDO::PARAM_STR);
                          $stmt -> bindParam(":password",$password,PDO::PARAM_STR);
                          $stmt -> bindParam(":created",$DATETIME,PDO::PARAM_STR);
                          $name = $username;
                          $password = $userpass_hash;
                          $stmt -> execute();
                       
                          $_SESSION["username"] = $username;
                          header("location:4koma_login.php");
                          //echo "新規登録が完了しました。";
                      }else{
                          echo "パスワードが一致しません。";
                      }
                  }
            }else {
               echo "<p>パスワードは半角英数字アンダーバー8文字以上です。</p>";
            }
        }
    }

// テーブルの中身表示
//    $sql = "SELECT * FROM userData";
//    $stmt= $pdo -> query($sql);
//    $result = $stmt -> fetchAll();
//    foreach($result as $row){
//        echo $row["name"] . " " . $row["password"] . "<br>";
//    }

// テーブル削除
//$sql = "DROP TABLE userData";
//$stmt = $pdo -> query($sql);

// テーブル一覧表示
//    $sql = "SHOW TABLES";
//    $result = $pdo -> query($sql);
//    foreach($result as $row){
//        echo $row[0] . "<br>";
//    }

?>
<form action="" method="post">
  <input type="text" name="name" placeholder="ユーザー名"><br>
  <input type="text" name="password" placeholder="パスワード"><br>
  パスワードは英数字アンダーバー8文字以上<br>
  <input type="text" name="repassword" placeholder="パスワード確認用">
  <input type="submit" name="registration" value="登録">
</form>

</body>
</html>