<!--↓結果分析画面↓-->

<?php

session_start();

try{
    
    
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
    
    
    //変数
    $difference_weight=$now_weight-$goal_weight;
    $sentence1='現在'.$now_weight.'kg';
    $sentence2='目標まで'.$difference_weight.'kg';
    $sentence3='目標'.$goal_weight.'kg';
    
}catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}

?>
    
    

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC 結果分析画面</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_result.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
                <p class="hyperonly"><a class="ah" href="RC_Mypage.php">マイページ</a></p>
            </div>
            <div class="main">
                <h3 class="fontT">結果</h3>
                <div>
                    <ul class="top">
                        <li><?php echo $sentence1 ?></li>
                        <li id="made"><?php echo $sentence2 ?></li>
                        <li><?php echo $sentence3 ?></li>
                    </ul>
                </div>
                <div id="sportput">
                    <p>本日の運動消費カロリーは <?php echo $now_colar?>kcal です!</p>
                </div>
                <h4 class="h4f">分析</h4>
            </div>
        </form>
    </body>
</html>

<?php
    
    
    //データの変数化(ユーザー情報)逆バージョン
    $select_sql ="SELECT * FROM `{$id_table1}` ORDER BY id DESC";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        //テーブル用変数
        $oni=$row[0].'_oni';
        $oni2=substr($row1[3]/215,0,4);
        print ("<h5>$row1[4] : $row1[3]kcal : おにぎり $oni2 個分<br></h5>");
    }
    



?>