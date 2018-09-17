<?php


try{
    
    //PDOの準備情報
    $dsn = 'mysql:dbname="";host="";charset=utf8';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo=new PDO($dsn,$user,$password,//MySQLの接続
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//エラー投げる

    
    
    //変数定義
    $name=$_POST['name'];
    $address=$_POST['address'];
    $password=$_POST['password'];
    $login=$_POST['login'];
    $lowpoint=8;
    $now=time();

    //登録情報を記入して送信した場合
    if(!empty($name)&&!empty($address)){
    
    
        //メール送信
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        $to=$address;
        $subject='RC 仮登録完了のお知らせ';
        $message="$name さん 仮登録ありがとうございます!\r\n
こちらのurlより登録をお願いします。\r\nhttp://tyoi59.php.xdomain.jp/public_html/Run-C/RC_maint.php?timet=$now&namet=$name&adrt=$address";
        $headers="From:rc.company825@gmail.com";

        mb_send_mail($to, $subject, $message, $headers);
    
    
        //飛ぶ処理
        header('Location: RC_karikan.php');
        exit();
    }
    elseif(empty($name)&&empty($address)&&empty($password)){

    }
    else{
        echo '何か入力に誤りがあります。';
    }

    if(isset($login)){
        header('Location: RC_login.php');
        exit();
    }
    
    
    
} catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
?>


<!--↓仮登録画面↓-->

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC 仮登録画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_first.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
            </div>
            <div class="main">
                <p class="fontT">仮登録</p>
                <input class="boxT" type="text" name="name" maxlength="" value="" placeholder="名前">
                <br>
                <input class="boxT" type="text" name="address" maxlength="" value="" placeholder="メールアドレス">
                <br>
                <input class="button" type="submit" name="sent" value="    送信    "><br>
                <p><a class="aT" class="a2" href="RC_login.php">アカウントお持ちの方はこちら</a>
            </div>
        </form>
    </body>
</html>

<?php


try{
    
    
    
    } catch (PDOException $e) {
        //エラーがあったらエラー内容と行を表示
        echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
    }

?>