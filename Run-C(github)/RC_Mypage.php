<!--↓マイページ画面↓-->

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
    $logout=$_POST['logout'];
    $reset=$_POST['reset'];
    $nweight=$_POST['nweight'];
    $gweight=$_POST['gweight'];
    $sweight=$_POST['sweight'];//初期表示の送信ボタン
    $measure_weight=$_POST['measure_weight'];
    $test=$_POST['test'];
    $diarybox=$_POST['diarybox'];
    $dairysend=$_POST['dairysend'];
    $now_date=date("n/j");
    $diary='未記入';
    $calor_strat=0;
    $logS=0;
    
    
    
    
    //テーブル削除
    /*if(isset($reset)){
        $sql_dt = "DROP TABLE IF EXISTS `{$id_table1}`";
        $pdo -> exec($sql_dt);
    }*/
    
    
    
    
    
    //現&目標体重&カロリーのテーブル作成
    $sql_table="CREATE TABLE IF NOT EXISTS `{$id_table1}`"
        ."("
        ."id mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,"//書き込みデータを識別するためのid   (勝手に123って番号打ってくれる)
        ."PRIMARY KEY(id),"//主キーの設定(被らない情報を主キーにする)
        ."nweight char(16),"//現状の体重を入れるカラム
        ."gweight char(16),"//目標の体重を入れるカラム
        ."calorF char(6),"//運動カロリーを入れるカラム
        ."date char(16),"//年月日を入れるカラム
        ."diary text"//日付変更の印を入れるカラム
        .");";
    
    //テーブル作成の命令文
    $stmt=$pdo->query($sql_table);
    
    
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
    
    
    //初期ユーザー情報登録の入力(初回時のみ表示)
    if(isset($sweight)&&!empty($nweight)&&!empty($gweight)){
        
        
        $sql_edit="UPDATE `{$id_table1}` set nweight='$nweight',gweight='$gweight',calorF='$now_colar',date='$datenow',diary='$to_diary' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo '初期登録の成功 ';
    }
    
    
    
    
    
    //新規の情報登録の表示と非表示
    
    //現体重と目標がテーブルにない場合(表示)
    if(empty($now_weight)||empty($goal_weight)){
        $sentence1='まず'.$My_name.'さんの情報を入力してください!';
        $type=text;
        $type2=submit;
        $sentence2='';//隠す
        $sentence3='';//隠す
        $sentence4='';//隠す
        //echo '初期表示 ';
    }
    
    
    
    //現体重の更新(計測した値をデータベースに入力)
    if(!empty($measure_weight)&&isset($test)){
        $new_nweight=$measure_weight;//
        $new_gweight=$goal_weight;
        $new_calorF=$now_colar;
        $new_date=$datenow;
            
        $sql_edit="UPDATE `{$id_table1}` set nweight='$new_nweight',gweight='$new_gweight',calorF='$new_calorF',date='$new_date',diary='$to_diary' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo '体重の更新 ';
    }
    
    
    
    //運動カロリーの入力
    
    $calor_put=$_POST['stest'];
    $sentaku=$_POST['sentaku'];
    $running_km=$_POST['running_km'];
    $calor_d=$_POST['calor_d'];
    
    if(!empty($sentaku)&&!empty($running_km)){
        $spped=$_POST['sentaku'];
        $calor=1.05*$spped*($running_km/60)*$now_weight;
        
        //同じ日の追加カロリー記入
        if($now_date==$datenow){
            $new_nweight=$now_weight;
            $new_gweight=$goal_weight;
            $new_calorF=$now_colar+$calor;//
            $new_date=$datenow;
            
            $sql_edit="UPDATE `{$id_table1}` set nweight='$new_nweight',gweight='$new_gweight',calorF='$new_calorF',date='$new_date',diary='$to_diary' where id='$new_number'";
            $result1=$pdo->query($sql_edit);
            //echo 'カロリーの追加 ';
        }
    }
    //本日分のカロリーリセット
    elseif(isset($calor_d)&&empty($running_km)&&$now_colar!=0){
        $sql_edit="UPDATE `{$id_table1}` set nweight='$now_weight',gweight='$goal_weight',calorF='$calor_strat',date='$datenow',diary='$to_diary' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo 'カロリーリセット ';
    }
    
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
    
        if($now_colar==0){
            $calor_put='記入';
            $calor_del=hidden;
        }
        else{
            $calor_put='追加';
            $calor_del=submit;
        }
    
    
    
    //日記の入力
    if(isset($dairysend)&&!empty($diarybox)){
        $sql_edit="UPDATE `{$id_table1}` set nweight='$now_weight',gweight='$goal_weight',calorF='$now_colar',date='$datenow',diary='$diarybox' where id='$new_number'";
        $result1=$pdo->query($sql_edit);
        //echo '日記の記入 ';
    }
    
    
    
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
    
    
    //現体重と目標がテーブルにある場合(非表示)
    if(!empty($now_weight)&&!empty($goal_weight)){
        //変数定義(引き算)
        $difference_weight=$now_weight-$goal_weight;
        $sentence1='';//隠す
        $type2=hidden;//隠す
        $type=hidden;//隠す
        $sentence2='現在'.$now_weight.'kg';
        $sentence3='目標まで'.$difference_weight.'kg';
        $sentence4='目標'.$goal_weight.'kg';
        //echo '初期終了 ';
    }
    
    
    //新しい日の入力
    if($now_date!=$datenow){
        $sql_nyuuryoku=$pdo->prepare("INSERT INTO `{$id_table1}`(nweight,gweight,calorF,date,diary)VALUES(:nweight,:gweight,:calorF,:date,:diary)");
        $sql_nyuuryoku->bindParam(':nweight',$now_weight,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':gweight',$goal_weight,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':calorF',$calor_strat,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':date',$now_date,PDO::PARAM_STR);
        $sql_nyuuryoku->bindParam(':diary',$diary,PDO::PARAM_STR);
        $sql_nyuuryoku->execute();
        //echo '新たな日 ';
    }
    
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
    
    
    
    //分析
    //変数定義(日付)
    $today=date("n/j");
    $before1=date("n/j",strtotime('-1 day'));
    $before2=date("n/j",strtotime('-2 day'));
    $before3=date("n/j",strtotime('-3 day'));
    $before4=date("n/j",strtotime('-4 day'));
    $before5=date("n/j",strtotime('-5 day'));
    $before6=date("n/j",strtotime('-6 day'));
    $today_colar=0;
    $before1_colar=0;
    $before2_colar=0;
    $before3_colar=0;
    $before4_colar=0;
    $before5_colar=0;
    $before6_colar=0;
    
    //ユーザー情報の更新とカロリーの抽出
    $select_sql ="SELECT * FROM `{$id_table1}`";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        if($today==$row1[4]){
            $today_colar=$row1[3];
        }
        if($before1==$row1[4]){
            $before1_colar=$row1[3];
        }
        if($before2==$row1[4]){
            $before2_colar=$row1[3];
        }
        if($before3==$row1[4]){
            $before3_colar=$row1[3];
        }
        if($before4==$row1[4]){
            $before4_colar=$row1[3];
        }
        if($before5==$row1[4]){
            $before5_colar=$row1[3];
        }
        if($before6==$row1[4]){
            $before6_colar=$row1[3];
        }
    }
    $BC6=$before6.'&emsp;&emsp;'.$before6_colar;
    $BC5=$before5.'&emsp;&emsp;'.$before5_colar;
    $BC4=$before4.'&emsp;&emsp;'.$before4_colar;
    $BC3=$before3.'&emsp;&emsp;'.$before3_colar;
    $BC2=$before2.'&emsp;&emsp;'.$before2_colar;
    $BC1=$before1.'&emsp;&emsp;'.$before1_colar;
    $BC0=$today.'&emsp;&emsp;'.$today_colar;
    
    
    //日記
    $select_sql ="SELECT * FROM `{$id_table1}`";
    $select_result = $pdo->query($select_sql);
    foreach($select_result as $row1){
        if($today==$row1[4]){
            $today_diary=$row1[5];
        }
        if($before1==$row1[4]){
            $before1_diary=$row1[5];
        }
        if($before2==$row1[4]){
            $before2_diary=$row1[5];
        }
        if($before3==$row[4]){
            $before3_diary=$row1[5];
        }
        if($before4==$row1[4]){
            $before4_diary=$row1[5];
        }
        if($before5==$row1[4]){
            $before5_diary=$row1[5];
        }
        if($before6==$row1[4]){
            $before6_diary=$row1[5];
        }
    }
    
    $BD6=$before6.'&emsp;&emsp;'.$before6_diary;
    $BD5=$before5.'&emsp;&emsp;'.$before5_diary;
    $BD4=$before4.'&emsp;&emsp;'.$before4_diary;
    $BD3=$before3.'&emsp;&emsp;'.$before3_diary;
    $BD2=$before2.'&emsp;&emsp;'.$before2_diary;
    $BD1=$before1.'&emsp;&emsp;'.$before1_diary;
    $BD0=$today.'&emsp;&emsp;'.$today_diary;
    
    
    //テーブルの表示(消す)
    /*echo '<br>'.'---------↓ユーザー情報↓---------'.'<br>';

    echo '番号 : 現体重 : 目標体重 : 運動カロリー : 日付 : 日記'.'<br>';
    //出力
    $sql="SELECT*FROM `{$id_table1}`";//入力したデータを見る
    $results=$pdo->query($sql);
    foreach($results as $row){//$rowの中にはテーブルの列名が入る
        echo $row['id'].' : ';
        echo $row['nweight'].' : ';
        echo $row['gweight'].' : ';
        echo $row['calorF'].' : ';
        echo $row['date'].' : ';
        echo $row['diary'].'<br>';
    }
    echo "<hr>";//水平線*/
    
    
    
    
}catch (PDOException $e) {
    //エラーがあったらエラー内容と行を表示
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>RC マイページ</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_Mypage.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
                <div class="hyper">
                    <p class="hyperlink"><a class="ah" href="RC_login.php">ログアウト</a></p>
                    <p class="hyperlink"><a class="ah" href="RC_report.php">日記</a></p>
                    <p class="hyperlink"><a class="ah" href="RC_result.php">結果分析</a></p>
                    <p class="hyperlink"><a class="ah" href="RC_goal.php">目標</a></p>
                </div>
            </div>
            <div class="main">
                <div id="user_info">
                    <h3 class="fontT"><?php echo $My_name ?>さんのページ</h3>
                    
                    <!--初期のみ表示系-->
                    <div id="first">
                        <p><?php echo $sentence1;?></p>
                        <input type="<?php echo $type;?>" name="nweight" maxlength="" value="" placeholder="現在の体重">
                        <input type="<?php echo $type;?>" name="gweight" maxlength="" value="" placeholder="目標体重">
                        <input type="<?php echo $type2;?>" name="sweight" maxlength="" value="決定" placeholder="">
                    </div>
                    <ul class="top">
                        <li><?php echo $sentence2 ?></li>
                        <li id="made"><?php echo $sentence3 ?></li>
                        <li><?php echo $sentence4 ?></li>
                    </ul>
                </div>
                <div id="sportput">
                    <p>本日の運動消費カロリーは <?php echo $now_colar?>kcal です!</p>
                </div>
                <div id="input">
                    <h4 class="h4f">報告</h4>
                    <p>運動</p>
                    <div class="custom">
                        <select name="sentaku">
                            <option value="" selected>走ったペースを選択してください</option>
                            <option value="6">ジョギング(6.4km/h)</option>
                            <option value="8.3">ランニング(8.0km/h)</option>
                            <option value="10.5">速いランニング(10.8km/h)</option>
                        </select>
                    </div>
                    <input class="boxT" type="text" name="running_km" maxlength="" value="" placeholder="走行時間(分)">
                    <input class="button" type="submit" name="stest" value="   <?php echo $calor_put ?>   "><input class="button" type="<?php echo $calor_del ?>" name="calor_d" value="一日分リセット">
                    <p>体重</p><input class="boxT" type="text" name="measure_weight" maxlength="" value="" placeholder="体重(kg)"><input class="button" type="submit" name="test" value="   更新   "><br>
                    <p><?php echo $now_date?> 日記</p><p><textarea name="diarybox" class="button1" rows="4" cols="40" placeholder="本日の日記を書きましょう"></textarea>
                    <input class="button button2" type="submit" name="dairysend" value="   記入   ">
                    </p>
                    <div id="data">
                        <h4>分析</h4>
                        <p><?php echo $BC6 ?>kcal</p>
                        <p><?php echo $BC5 ?>kcal</p>
                        <p><?php echo $BC4 ?>kcal</p>
                        <p><?php echo $BC3 ?>kcal</p>
                        <p><?php echo $BC2 ?>kcal</p>
                        <p><?php echo $BC1 ?>kcal</p>
                        <p><?php echo $BC0 ?>kcal</p>
                    </div>
                    <div id="diary">
                        <h4>日記</h4>
                        <p><?php echo $BD6 ?></p>
                        <p><?php echo $BD5 ?></p>
                        <p><?php echo $BD4 ?></p>
                        <p><?php echo $BD3 ?></p>
                        <p><?php echo $BD2 ?></p>
                        <p><?php echo $BD1 ?></p>
                        <p><?php echo $BD0 ?></p>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>

