<?php

//共有変数・関数ファイルを読み込み
require('function.php');

debug('===========================================================');
debug('ログインページ');
debug('===========================================================');
debugLogStart();

//ログイン認証
require('auth.php');

//=============================
//ログイン画面処理
//=============================
//post送信された場合
if (!empty($_POST)) {
    debug('POST送信があります．');

    //変数にユーザー情報を代入
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');

    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');

    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');

    if (empty($err_msg)) {
        debug('バリデーションOKです');

        //例外処理
        try {
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'SELECT password,id FROM users WHERE email = :email';
            $data = array(':email' => $email);
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            //クエリ結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            debug('クエリ結果の中身:' . print_r($result, true));

            //パスワード照合
            if (!empty($result) && password_verify($pass, array_shift($result))) {
                debug('パスワードがマッチしました．');

                //ログイン有効期限（デフォルトを1時間とする）
                $sesLimit = 60 * 60;
                //最終ログイン日時を現在日時に
                $_SESSION['login_date'] = time();

                //ログイン保持にチェックがある場合
                if ($pass_save) {
                    debug('ログイン保持にチェックがあります．');
                    //ログイン有効期限を30日にしてセット
                    $_SESSION['login_limit'] = $sesLimit * 24 * 30;
                } else {
                    debug('ログイン保持にチェックはありません．');
                    // 次回からログイン保持しないので，ログイン有効期限を1時間後にセット
                    $_SESSION['login_limit'] = $sesLimit;
                }
                //ユーザーIDを格納
                $_SESSION['user_id'] = $result['id'];

                debug('セッション変数の中身:' . print_r($_SESSION, true));
                debug('マイページへ遷移します.');
                header("Location:mypage.html"); //マイページへ
                exit;
            } else {
                debug('パスワードがアンマッチです.');
                $err_msg['common'] = MSG09;
            }
        } catch (Exception $e) {
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login > Shoe you </title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>

<body class="page-signup page-1colum">

    <!-- メニュー　-->
    <header>
        <div class="site-width">
            <h1><a href="index.html">Shoe You</a></h1>
            <nav id="top-nav">
                <ul>
                    <li><a href="signup.php" class="btn btn-primary">Sign Up</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- メインコンテンツ　-->
    <div id="contents" class="site-width">

        <!-- Main -->
        <section id="main">

            <div class="form-container">

                <form action="" method="post" class="form">
                    <h2 class="title">Login!!</h2>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    <label class="<?php if (!empty($err_msg['email'])) echo 'err'; ?>" for="">
                        E-Mail
                        <input type="text" name="email" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['email'])) echo $err_msg['email'];
                        ?>
                    </div>
                    <label class="<?php if (!empty($err_msg['pass'])) echo 'err'; ?>" for="">
                        Password
                        <input type="password" name="pass" value="<?php if (!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['pass'])) echo $err_msg['pass'];
                        ?>
                    </div>
                    <label>
                        <input type="checkbox" name="pass_save">次回ログインを省略する
                    </label>
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="ログイン">
                    </div>
                    パスワードを忘れた方は<a href="passRemindSend.html">コチラ</a>

                </form>
            </div>
        </section>
    </div>
    <!-- footer -->
    <footer id="footer">
        Copyright <a href="">Shoe You</a>. All Rights Reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script>
        $(function() {
            var $ftr = $('#footer');
            if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
                $ftr.attr({
                    'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'
                });
            }
        });
    </script>
</body>

</html>