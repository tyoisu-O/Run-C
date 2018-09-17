<!--↓日記記録画面↓-->

<?php

session_start();


try{
    
    //PDOの準備情報
    $dsn = 'mysql:dbname="";host="";charset=utf8';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo=new PDO($dsn,$user,$password,//MySQLの接続
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//エラー投げる

    
    //ユーザーのidを持ってくる
    $My_id=$_SESSION["id"];
    
    //id持ってなかったらログインへ強制
    if(empty($My_id)){
        header('Location: RC_login.php');
        exit();
    }
    
    
    //ユーザーの情報をテーブルから持ってくる(登録テーブルから)
    $sql_cel='SELECT*FROM maint_table';
    $result_cel=$pdo->query($sql_cel);
    foreach($result_cel as $row){
        if($My_id==$row[0]){
            $My_name=$row[1];
        }
    }
    
    
    //ユーザーごとのテーブル作成の変数
    $id_table1=$My_id.'_table2';
    
    
    
    //変数定義
    $now_date=date("n/j");
    $diarybox=$_POST['diarybox'];
    $dairysend=$_POST['dairysend'];
    
    
    //ユーザー情報の更新
    $select_sql ="SELECT * FROM `{$id_table1}`";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        $new_number=$row1[0];
        $now_weight=$row1[1];
        $goal_weight=$row1[2];
        $now_colar=$row1[3];
        $datenow=$row1[4];
        $to_diary=$row1[5];
    }
    
    //日記の入力
    if(isset($dairysend)&&!empty($diarybox)){
        $sql_edit="UPDATE `{$id_table1}` set nweight='$now_weight',gweight='$goal_weight',calorF='$now_colar',date='$datenow',diary='$diarybox' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo '日記の記入 ';
    }


}catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}

?>

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC 日記記録画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_report.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
                <p class="hyperonly"><a class="ah" href="RC_Mypage.php">マイページ</a></p>
            </div>
            <div class="main">
                <h3 class="fontT">日記</h3>
                <br>
                <p><?php echo $now_date?> 日記</p>
                <p><textarea name="diarybox" class="button1" rows="4" cols="40" placeholder="本日の日記を書きましょう"></textarea>
                <input class="button button2" type="submit" name="dairysend" value="   送信   ">
                </p>
                <h4 class="h4f">過去の日記</h4>
            </div>
        </form>
    </body>
</html>


<?php

try{
    
    //データの変数化(ユーザー情報)逆バージョン
    $select_sql ="SELECT * FROM `{$id_table1}` ORDER BY id DESC";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        print ("<h5>$row1[4] : $row1[5]<br></h5>");
        //echo $row1[4].' : ';
        //echo $row1[5].'<br>';
    }
    
    
}catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}

?>