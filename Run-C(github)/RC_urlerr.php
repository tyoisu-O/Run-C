<?php

$first=$_POST['first'];

if(isset($first)){
    header('Location: RC_first.php');
    exit();
}

?>


<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC URL期限切れ</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_urlerr.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
                <p class="hyperonly"><a  class="ah" href="RC_first.php">仮登録へ</a></p>
            </div>
            <div class="main">
                <p class="text1">仮登録のURLが一定の時間を超えたため、期限切れになりました。</p>
                <p class="text1">お手数ですが、再度仮登録からやり直してください。</p>
            </div>
        </form>
    </body>
</html>