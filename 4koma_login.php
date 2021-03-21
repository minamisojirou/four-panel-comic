<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>四コマ漫画辞典 ログイン</title>
</head>

<body>

<br><h1>ログイン<h1><br>

<?php
//ログイン機能
    // セッション開始
    session_start();

    //新規登録から来たとき
    if(isset($_SESSION["username"])){
        echo "新規登録が完了しました。";
    }
    
	// DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    // フォームからの情報を受け取る
    if(!empty($_POST["name"]) or !empty($_POST["password"]) or !empty($_POST["login"])){
        $username = $_POST["name"];
        $userpass = $_POST['password'];
        $login = $_POST["login"];
    }
    // ログインボタンが押されたとき
        // ユーザーの情報の入力チェック
    if(isset($login)){
        if(empty($username)){
            echo "ユーザー名が未入力です。<br>";
        }elseif(empty($userpass)){
            echo "パスワードが未入力です。";
        }

        if(!empty($username) and !empty($userpass)){
            $sql = "SELECT * FROM userData WHERE name=:name";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(":name",$name,PDO::PARAM_STR);
            $name = $username;
            $stmt -> execute();
            $result = $stmt -> fetch();
            if($result["name"]===$username and password_verify($userpass,$result["password"])){
            //    echo "ログインに成功しました。";
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $result["password"];
                header("location:4koma_home.php");
            }else{
                echo "ユーザー名かパスワードが違います。";
            }
        }
    }
?>
<form action="" method="post">
  <input type="text" name="name" placeholder="ユーザー名"><br>
  <input type="text" name="password" placeholder="パスワード">
  <input type="submit" name="login" value="ログイン">
</form>
</body>
</html>