<?php
//共通/関数ファイルを読み込み
require('function.php');

debug('==========================================================');
debug('退会ページ');
debug('==========================================================');
debugLogStart();

//ログイン認証
require('auth.php');

//=====================================
//画面処理
//=====================================
//post送信された場合
if (!empty($_POST)) {
    debug('POST送信があります');

    //例外処理

    try {
        //DBへ接続する
        $dbh = dbConnect();
        //SQL文作 成
        $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
        $sql2 = 'UPDATE shoes SET delete_flg = 1 WHERE user_id = :us_id';
        $sql3 = 'UPDATE `like` SET delete_flg = 1 WHERE user_id = :us_id';
        //データの流し込み
        $data = array(':us_id' => $_SESSION['user_id']);
        //クエリ実行
        $stmt1 = queryPost($dbh, $sql1, $data);
        $stmt2 = queryPost($dbh, $sql2, $data);
        $stmt3 = queryPost($dbh, $sql3, $data);

        //クエリ実行成功の場合
        if ($stmt1) {
            //セッション削除
            session_destroy();
            debug('セッション変数の中身:' . print_r($_SESSION, true));
            debug('トップページへ遷移します');
            header("Location:index.php");
        } else {
            debug('クエリが失敗しました.');
            $err_msg['common'] = MSG07;
        }
    } catch (\Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
        //throw $th;
    }
}
debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>
<?php
$siteTitle = 'Withdraw';
require('head.php');
?>

<body class="page-withdraw page-1colum">

    <style>
        .form .btn .btn-mid {
            float: none;

        }

        .from {
            text-align: center;
        }
    </style>


    <!-- メニュー -->
    <?php
    require('header.php');
    ?>

    <!--　ナビバー　-->
    <?php
    require('navbar.php');
    ?>
    
    <!-- メインコンテンツ　-->
    <div id="contents" class="site-width">
        <!--Main -->
        <section id=main>
            <div class="form-container">
                <form class="form" action="" method="post" style="height:300px ">
                    <h2 class="title">Leave</h2>
                    <span style="margin-left:205px;">本当に退会してよろしいですか？</span>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    <div class="btn-container">
                        <input style="margin-top:40px " type="submit" name="submit" value="退会する" class="btn btn-mid">
                    </div>

                </form>
            </div>
            <a href="mypage.php">&lt;&lt; back</a>
        </section>
    </div>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>