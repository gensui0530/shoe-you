<?php
//共通変数・関数ファイルを読み込み
require('function.php');

debug("============================================");
debug("商品詳細ページ");
debug('============================================');
debugLogStart();

//===================================
//画面処理
//===================================

//画面表示用データ取得
//===================================
//商品IDのGETパラメータを取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
//DBデータから商品データを取得
$viewData = getProductOne($p_id);
//パラメータに不正な値が入っているかチェック
if (empty($viewData)) {
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php");
}
debug('取得したDBデータ：' . print_r($viewData, true));

//post送信されていた場合
if (!empty($_POST['submit'])) {
    debug('POST送信があります');

    require('auth.php');


    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'INSERT INTO bord (sale_user, buy_user, product_id, create_date) VALUES (:s_uid, :b_uid, p_id, :date )';
        $data = array('s_uid' => $viewData['user_id'], ':b_uid' => $_SESSION['user_id'], 'p_id' => $p_id, ':date' => ('Y-m-d H:i:s'));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        //クエリ成功の場合
        if ($stmt) {
            $_SESSION['msg_success'] = SUC05;
            debug("Location:msg.php?m_id=" . $dbh->lastInsertId());
        }
    } catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
debug('画面表示処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = '商品詳細';
require('head.php');
?>

<?php
require('header.php');
?>

<?php
require('navbar.php');
?>

<div id='contents' class="site-width">

    <section id="main">
        <div class="title">
            <span class="badge"><?php echo sanitize($viewData['category']); ?></span>
            <?php echo sanitize($viewData['name']); ?>
        </div>
        <div class="product-img-container">
            <div class="img-main">
                <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="メイン画像:<?php echo sanitize($viewData['name']); ?> " id="js-switch-img-main">
            </div>
            <div class="img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="画像1:<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="画像2:<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
            </div>
        </div>
        <div class="product-detail">
            <p><?php echo sanitize($viewData['comment']) ?></p>
        </div>
        <div class="product-buy">
            <div class="item-left">
                <a href="index.php<?php appendGetParam(array('p_id')); ?>">&lt; 商品一覧に戻る</a>
            </div>
            <form action="" method="post">
                <div class="item-right">
                    <input type="submit" name="submit" value="購入" 　class="btn btn-primary" style="margin-top:0;">
                </div>
            </form>
            <div class="item-right">
                <p class="price">￥ <?php echo sanitize(number_format($viewData['price'])); ?> </p>
            </div>
        </div>
    </section>

</div>

<?php
require('footer.php');
?>