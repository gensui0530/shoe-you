<?php
//共有変数・関数ファイルを読み込み
require('function.php');

debug('===========================================================');
debug('マイページ');
debug('===========================================================');
debugLogStart();

//ログイン認証
require('auth.php');

?>


<?php
$siteTitle = '';
require('head.php');
?>

<body class="page-signup page-1colum">


    <?php
    require('header.php');
    ?>

    <?php
    require('navbar.php');
    ?>

    <p id="js-show-msg" style="display: none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
    </p>


    <?php
    require('footer.php');
    ?>