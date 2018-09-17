<?php

session_start();

try{
    
    //PDOの準備情報
    $dsn = 'mysql:dbname="";host="";charset=utf8';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo=new PDO($dsn,$user,$password,//MySQLの接続
    
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//エラー投げる

    
    //テーブル作成の命令文
    //$stmt=$pdo->query($sql_table);
    
    
    /*echo '---------↓登録データ↓---------'.'<br>';
    
    echo 'ID : 名前 : メールアドレス : パスワード'.'<br>';
    
    //出力
    $sql='SELECT*FROM maint_table ORDER BY id';//入力したデータを見る(id順表示)
    $results=$pdo->query($sql);
    foreach($results as $row){//$rowの中にはテーブルの列名が入る
        echo $row['id'].' : ';
        echo $row['username'].' : ';
        echo $row['mailaddress'].' : ';
        echo $row['password'].'<br>';
    }
    echo "<hr>";//水平線*/
    
} catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
?>

<!--↓ログイン画面↓-->

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC ログイン画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_login.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
            </div>
            <div class="main">
                <p class="fontT">ログイン</p>
                <input class="boxT" type="text" name="name" maxlength="" value="" placeholder="名前">
                <br>
                <input class="boxT" type="text" name="password" maxlength="" value="" placeholder="パスワード(8文字以上)">
                <br>
                <input class="button" type="submit" name="sent" value=" ログイン "><br>
                <p><a class="aT" class="a2" href="RC_first.php">新規の方はこちら</a>
                <br>
            </div>
        </form>
    </body>
</html>

<?php

try{

$name=$_POST['name'];
$address=$_POST['address'];
$password=$_POST['password'];
$first=$_POST['first'];



//sessionのunset(ログアウトした人用)
if(!empty($_SESSION["id"])){
    unset($_SESSION["id"]);
    //echo 'unset済み';
}


if(!empty($name)&&!empty($password)){
    
    //パスワードのハッシュ化
    $salt='salt';
    $pepper=hash('SHA256','saltandpepper');
    $hash_pass=hash('SHA256',$pepper.$password.$salt);
    //echo $hash_pass;
    
    $sql_cel='SELECT*FROM maint_table';
    $result_cel=$pdo->query($sql_cel);
    foreach($result_cel as $row){
        if($name==$row[1]&&$hash_pass==$row[3]){
            $_SESSION["id"]=$row[0];
            $er=0;
            
            //飛ぶ処理
            header('Location: RC_Mypage.php');
            exit();
            
        }
        else{
            $er=1;
        }
    }
    if($er==1){
        echo '何か入力に誤りがあります。';
    }
}


}catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}

?>