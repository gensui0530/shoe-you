<?php

//共通変数/関数ファイルを読み込み
require('function.php');

debug("============================================");
debug("プロフィール編集ページ");
debug('============================================');
debugLogStart();

//ログイン認証
require('auth.php');

//=========================
//画面処理
//=========================
//DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);

debug('所得したユーザー情報；' . print_r($dbFormData, true));

//post送信されていた場合
if (!empty($_POST)) {
    debug('POST送信があります');
    debug('POST情報；' . print_r($_POST, true));


    //変数にユーザ情報を代入
    $username = $_POST['username'];
    $tel = $_POST['tel'];
    $zip = (!empty($_POST['zip'])) ? $_POST['zip'] : 0;
    $addr = $_POST['addr'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $brand = $_POST['brand'];
    $size = $_POST['size'];

    //画像をアップロードし，パスを格納
    $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'], 'pic') : '';
    // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $pic = (empty($pic) && !empty($dbFormData['pic'])) ? $dbFormData['pic'] : $pic;

    //DB情報と入力情報が異なる場合にバリデーションを行う
    if ($dbFormData['username'] !== $username) {
        //名前の最大文字数チェック
        validMaxLen($username, 'username');
    }

    if ($dbFormData['tel'] !== $tel) {
        //TEL形式チェック
        validTel($tel, 'tel');
    }

    if ($dbFormData['addr'] !== $addr) {
        //住所の最大文字数チェック
        validMaxLen($addr, 'addr');
    }

    if ((int) $dbFormData['zip'] !== $zip) {
        //郵便番号形式チェック
        validZip($zip, 'zip');
    }

    if ($dbFormData['age'] !== $age) {
        //年齢の最大文字数チェック
        validMaxLen($age, 'age');
        //年齢の半角数字チェック
        validNumber($age, 'age');
    }

    if ($dbFormData['email'] !== $email) {
        //emailの最大文字数チェック
        validMaxLen($email, 'email');
        if (empty($err_msg['email'])) {
            //emailの重複チェック
            validEmailDup($email);
        }
        //emailの形式チェック
        validEmail($email, 'email');
        //emailの未入力チェック
        validRequired($email, 'email');
    }


    if ($dbFormData['brand'] !== $brand) {
        //ブランドの最大文字数チェック
        validMaxLen($brand, 'brand');
    }

    if ($dbFormData['size'] !== $size) {
        //サイズの最大文字数チェック
        validMaxLen($size, 'size');
    }

    if (empty($err_msg)) {
        debug('バリデーションOKです．');


        //例外処理
        try {
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'UPDATE users SET username = :u_name, tel = :tel, zip = :zip, addr = :addr, age= :age, email = :email, size = :size, brand = :brand, pic = :pic WHERE id = :u_id';
            $data = array(':u_name' => $username, ':tel' => $tel, ':zip' => $zip, ':addr' => $addr, ':age' => $age, ':email' => $email, ':size' => $size, ':brand' => $brand, ':pic' => $pic, ':u_id' => $dbFormData['id']);
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            // クエリ成功の場合
            if ($stmt) {
                $_SESSION['msg-success'] = SUC02;
                debug('マイページへ遷移します');
                header("Location:mypage.php"); //マイページへ
            }
        } catch (\Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了　＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜');
?>
<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>

<body class="page-profEdit page-2colum ">


    <!-- メニュー -->
    <?php
    require('header.php');
    ?>


    <!-- ナビバー　-->
    <?php
    require('navbar.php');
    ?>

    <!-- メインコンテンツ　-->
    <div id="contents" class="site-width">

        <!-- Main -->
        <section id="main">
            <div class="form-container">
                <h1 class="page-title">
                    プロフィール編集
                </h1>

                <form class="form" action="" method="post" enctype="multipart/form-data" style=" margin-left:140px; height:1050px">

                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    <div class="imgDrop-container" style="margin-left:120px; margin-bottom:15px; width:100%;">
                        <label style="width: 150px; height: 150px; border-radius: 50%; -moz-border-radius: 50%; -webkit-border-radius: 50%;" class="area-drop" <?php if (!empty($err_msg['pic'])) echo 'err'; ?>>
                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                            <input style="" type="file" name="pic" class="input-file">
                            <img style="display:none; width: 150px; height: 150px; border-radius: 50%; -moz-border-radius: 50%; -webkit-border-radius: 50%;" src="<?php echo getFormData('pic'); ?>" class="prev-img " style="<?php if (empty(getFormData('pic'))) echo 'display:none;' ?>">
                            photo
                        </label>
                        <div class="area-msg">
                            <?php
                            if (!empty($err_msg['pic'])) echo $err_msg['pic'];
                            ?>
                        </div>
                    </div>
                    <label class="<?php if (!empty($err_msg['username'])) echo 'err'; ?>">
                        Name
                        <input class="user_name" type="text" name="username" value="<?php echo getFormData('username'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['username'])) echo $err_msg['username']; ?>
                    </div>

                    <label class="<?php if (!empty($err_msg['tel'])) echo 'err'; ?>">
                        TEL <span style="font-size:12px; margin-left:5px;"> ※ハイフン無しで入力下さい</span>
                        <input type="text" name="tel" value="<?php echo getFormData('tel'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
                    </div>


                    <label class="<?php if (!empty($err_msg['zip'])) echo 'err'; ?>">
                        <span style="font-size:12px; margin-left:5px;"> ※ハイフン無しで入力下さい</span>
                        <input type="text" name="zip" value="<?php echo getFormData('zip'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['zip'])) echo $err_msg['zip']; ?>
                    </div>



                    <label class="<?php if (!empty($err_msg['addr'])) echo 'err'; ?>">
                        Address
                        <input class="address" type="text" name="addr" value="<?php echo getFormData('addr'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['addr'])) echo $err_msg['addr']; ?>
                    </div>


                    <label class="<?php if (!empty($err_msg['email'])) echo 'err'; ?>">
                        Email
                        <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['email'])) echo $err_msg['email']; ?>
                    </div>

                    <label class="<?php if (!empty($err_msg['age'])) echo 'err'; ?>">
                        Age
                        <input type="number" name="age" value="<?php echo getFormData('age'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['age'])) echo $err_msg['age']; ?>
                    </div>

                    <label class="<?php if (!empty($err_msg['size'])) echo 'err'; ?>" style="width:20%;">
                        My Size
                        <div style="display: inline-flex">
                            <input class="size" type="text" name="size" 　placeholder="25.0" value="<?php echo getFormData('size'); ?>">
                            <span style="height:10%;  margin:20px 0px 0px 10px;">㎝</span>
                        </div>
                    </label>
                    <div class=" area-msg">
                        <?php
                        if (!empty($err_msg['size'])) echo $err_msg['size']; ?>
                    </div>

                    <label class="<?php if (!empty($err_msg['brand'])) echo 'err'; ?>">
                        Favorite Brand
                        <input type="text" name="brand" value="<?php echo getFormData('brand'); ?>">
                    </label>
                    <div class="area-msg">
                        <?php
                        if (!empty($err_msg['brand'])) echo $err_msg['brand']; ?>
                    </div>

                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="変更する">
                    </div>

                </form>
            </div>

        </section>
    </div>
    <!-- footer -->
    <?php
    require('footer.php')
    ?>
</body>