<?php

session_start();

try{
    
    //PDOの準備情報
    $dsn = 'mysql:dbname="";host="";charset=utf8';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo=new PDO($dsn,$user,$password,//MySQLの接続
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//エラー投げる
    
    
    
    //変数定義
    $now=time();
    $second=1800;//30分
    $url_time=$_GET['timet'];
    $Gname=$_GET['namet'];
    $Gadr=$_GET['adrt'];
    $name=$_POST['name'];
    $frist_pass='';
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    $login=$_POST['login'];
    $lowpoint=8;
    
    
    //時間確認
    //echo $now.' '.$url_time;
    
    
    //ユーザー情報の更新
    $select_sql ="SELECT * FROM maint_table";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        $number=$row1[0];
        $userN=$row1[1];
        $adr=$row1[2];
        $pass=$row1[3];
        $urlT=$row1[4];
    }
    
    
    //テーブル削除
    //$sql_dt = "DROP TABLE IF EXISTS maint_table";
    //$pdo -> exec($sql_dt);
    
    
    //テーブルの作成
    $sql_table="CREATE TABLE IF NOT EXISTS maint_table"
        ."("
        ."id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,"//書き込みデータを識別するためのid   (勝手に123って番号打ってくれる)
        ."PRIMARY KEY(id),"//主キーの設定(被らない情報を主キーにする)
        ."username char(32),"//ユーザー名を入れるカラム
        ."mailaddress char(64),"//メールアドレスを入れるカラム
        ."password varchar(255),"//パスワードを入れるカラム
        ."urltime varchar(64)"//GETした時間を入れるカラム
        .");";
    
    //テーブル作成の命令文
    $stmt=$pdo->query($sql_table);
    
    //ユーザー情報の更新
    $select_sql ="SELECT * FROM maint_table";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        $number=$row1[0];
        $userN=$row1[1];
        $adr=$row1[2];
        $pass=$row1[3];
        $urlT=$row1[4];
    }
    
    
    //GETを保存するためにテーブル入力
    if(!empty($url_time)){
        $sql_nyuuryoku=$pdo->prepare("INSERT INTO maint_table(username,mailaddress,password,urltime)VALUES(:username,:mailaddress,:password,:urltime)");
        $sql_nyuuryoku->bindParam(':username',$Gname,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':mailaddress',$Gadr,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':password',$frist_pass,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':urltime',$url_time,PDO::PARAM_STR);
        $sql_nyuuryoku->execute();
    }
    
    
    //ユーザー情報の更新
        $select_sql ="SELECT * FROM maint_table";
        $select_result = $pdo->query($select_sql);
        foreach($select_result as $row1){
            $number=$row1[0];
            $userN=$row1[1];
            $adr=$row1[2];
            $pass=$row1[3];
            $urlT=$row1[4];
        }
    
    
    //期限外urlの場合
    if($now-$urlT>$second){
        header('Location: RC_urlerr.php');
        exit();
    }
    
    //期限内urlからのユーザー情報の入力
    if($now-$urlT< $second){
        if(!empty($name)&&!empty($password)&&$password==$password2&&strlen($password)>=$lowpoint){
            
            //パスワードのハッシュ化
            $salt='salt';
            $pepper=hash('SHA256','saltandpepper');
            $hash_pass=hash('SHA256',$pepper.$password.$salt);
            
            
            //登録情報の入力
            $sql_edit="UPDATE maint_table set username='$name',mailaddress='$adr',password='$hash_pass',urltime='$urlT' where id='$number'";
            $result1=$pdo->query($sql_edit);
            
            
            //ユーザーidをセッション
            $sql_cel='SELECT*FROM maint_table';
            $result_cel=$pdo->query($sql_cel);
            foreach($result_cel as $row){
                if($number==$row[0]&&$hash_pass==$row[3]){
                    $_SESSION["id"]=$row[0];
                }
            }
            
            //飛ぶ処理
            //echo 'マイページへ';
            header('Location: RC_Mypage.php');
            exit();
            
        }
        elseif(empty($name)&&empty($address)&&empty($password)){
            //初期(何も変わらない)
        }
        else{
            echo '何か入力に誤りがあります。';
        }
    }
    
    
    //ユーザー情報の更新
    $select_sql ="SELECT * FROM maint_table";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        $number=$row1[0];
        $userN=$row1[1];
        $adr=$row1[2];
        $pass=$row1[3];
        $urlT=$row1[4];
    }
    
            
    
    //テーブル確認の表示
    /*echo '---------↓登録データ↓---------'.'<br>';

    echo 'ID : 名前 : メールアドレス : パスワード'.'<br>';
    
    //出力
    $sql='SELECT*FROM maint_table ORDER BY id';//入力したデータを見る(id順表示)
    $results=$pdo->query($sql);
    foreach($results as $row){//$rowの中にはテーブルの列名が入る
        echo $row['id'].' : ';
        echo $row['username'].' : ';
        echo $row['mailaddress'].' : ';
        echo $row['password'].' : ';
        echo $row['urltime'].'<br>';
    }
    echo "<hr>";//水平線*/
    
    
} catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}
?>



<!--↓新規登録画面↓-->

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC 新規登録画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_maint.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
            </div>
            <div class="main">
                <p class="fontT">登録</p>
                <input class="boxT" type="text" name="name" maxlength="" value="<?php echo $userN ?>" placeholder="名前(ニックネーム)">
                <br>
                <input class="boxT" type="text" name="password" maxlength="" value="" placeholder="パスワード(8文字以上)">
                <br>
                <input class="boxT" type="text" name="password2" maxlength="" value="" placeholder="確認パスワード(8文字以上)">
                <br>
                <input class="button" type="submit" name="sent" value="    送信    "><br>
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