<!--↓目標登録画面↓-->

<?php

session_start();

try{
    
    //変数
    $date=$_POST['date'];
    $sentence=$_POST['sentence'];
    $My=$_POST['My'];
    $sent=$_POST['sent'];
    
    
    //PDOの準備情報
    $dsn = 'mysql:dbname="";host="";charset=utf8';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo=new PDO($dsn,$user,$password,//MySQLの接続
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));//エラー投げる
    
    
    $My_id=$_SESSION["id"];
    
    //id持ってなかったらログインへ強制
    if(empty($My_id)){
        header('Location: RC_login.php');
        exit();
    }
    
    
    $sql_cel='SELECT*FROM maint_table';
    $result_cel=$pdo->query($sql_cel);
    foreach($result_cel as $row){
        if($My_id==$row[0]){
            $My_name=$row[1];
        }
    }
    
    //テーブル用変数
    $id_table1=$My_id.'_table2';
    
    
    
    //データの変数化(ユーザー情報)
        $select_sql ="SELECT * FROM `{$id_table1}`";
        $select_result = $pdo->query($select_sql);
        foreach($select_result as $row1){
            $new_number=$row1[0];
            $now_weight=$row1[1];
            $goal_weight=$row1[2];
            $now_colar=$row1[3];
            $datenow=$row1[4];
            $date_C=$row1[5];
        }
    
    //目標体重の変更
    if(isset($sent)&&!empty($sentence)){
            
        $sql_edit="UPDATE `{$id_table1}` set nweight='$now_weight',gweight='$sentence',calorF='$now_colar',date='$datenow',diary='$date_C' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo '体重の更新 ';
    }
    
        //データの変数化(ユーザー情報)
        $select_sql ="SELECT * FROM `{$id_table1}`";
        $select_result = $pdo->query($select_sql);
        foreach($select_result as $row1){
            $new_number=$row1[0];
            $now_weight=$row1[1];
            $goal_weight=$row1[2];
            $now_colar=$row1[3];
            $datenow=$row1[4];
            $diary=$row1[5];
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
        <title>RC 目標画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_goal.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
                <p class="hyperonly"><a class="ah" href="RC_Mypage.php">マイページ</a></p>
            </div>
            <div class="main">
                <h3 class="fontT">目標</h3>
                <br>
                <p class="size">目標体重<?php echo $goal_weight;?>kg</p>
                <br>
                <p class="text1">もし目標を変更される方はこちらから</p>
                <input class="boxT" type="text" name="sentence" maxlength="" value="" placeholder="新たな目標体重">
                <input class="button" type="submit" name="sent" value="   変更   "><br>
            </div>
        </form>
    </body>
</html>
