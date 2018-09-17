<!--↓仮登録確認ページ↓-->
<?php
if(isset($_POST['login'])){
    header('Location: RC_login.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <title>RC 仮登録完了</title>
        <link rel="stylesheet" href="RC.css" type="text/css">
    </head>
    <body style="margin:0px;padding:0px">
        <form action="RC_karikan.php" method="post">
            <div class="header">
                <h1 id="title">Run-C</h1>
                <h1 id="concept">理想の運動リズムを維持サポート</h1>
            </div>
            <div class="main">
                <p class="text1">仮登録ありがとうございます!!</p>
                <p class="text1">メールアドレス宛に本登録のURLがありますので、そちらから本登録をお願いします。</p>
                <input class="button" type="submit" name="login" value="ログインへ">
            </div>
        </form>
    </body>
</html>

