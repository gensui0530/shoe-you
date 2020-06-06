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
        $sql = 'INSERT INTO bord (sale_user, buy_user, product_id, create_date) VALUES (:s_uid, :b_uid, :p_id, :date )';
        $data = array(':s_uid' => $viewData['user_id'], ':b_uid' => $_SESSION['user_id'], 'p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        //クエリ成功の場合
        if ($stmt) {
            $_SESSION['msg_success'] = SUC05;
            debug('連絡掲示板に遷移します');
            header("Location:msg.php");
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
<style>
    .badge {
        padding: 5px 10px;
        color: #020826;
        text-align: center;
        border: solid 2px #b6a489;
        margin-top: 20px;
        margin-left: 380px;
        font-size: 18px;
        vertical-align: middle;
        width: 200px;
    }

    .title {
        text-align: center;
        font-size: 24px;
        padding: 10px 0;
        color: #020826;
        margin-top: 20px;
        margin-left: 60px;
        line-height: 0;

    }

    .size_data {
        font-size: 30px;
        position: relative;
        top: 50px;
        left: 270px;
        padding: 15px;
        background-color: rgb(2, 8, 38, 0.7);
        width: 80px;
        text-align: center;
        line-height: 0;


    }

    .img-main img {
        padding: 10px;
        box-sizing: border-box;
        width: 500px;
        height: 350px;
        margin-left: 245px;
        margin-bottom: 30px;
        background: #b6a489;
        line-height: 0;

    }

    .img-sub-container {
        margin-left: 130px;
    }

    .img-sub img {
        width: 200px;
        height: 200px;
        padding: 10px;
        background: #eaddcf;
        margin: 10px;
    }



    .product-img-container .img-sub:hover {
        cursor: pointer;
    }

    .product-img-container .img-sub img {
        margin-bottom: 15px;
    }

    .product-img-container .img-sub img:last-child {
        margin-bottom: 0;
    }

    .product-detail {
        background: #eaddcf;
        padding: 15px;
        margin-top: 30px;
        margin-left: 80px;
        min-height: 150px;
        width: 800px;
    }

    .price-container {
        margin-top: 60px;
    }

    .price-container .price {
        top: 950px;
        left: 420px;
        font-size: 42px;
        color: #020826;
        background: white;

    }

    .product-buy {
        overflow: hidden;
        margin-top: 15px;
        margin-bottom: 50px;
        height: 50px;
        line-height: 50px;
        margin-left: 20px;
    }



    .product-buy .price {
        font-size: 32px;
        margin-right: 30px;
    }

    .item-center input[type="submit"] {
        font-size: 30px;
        margin-left: 200px;
        padding: 10px 40px;
        border: none;
        background: #f25042;
        color: #fffffe;
        outline: none;
        width: 600px;
        height: 60px;
    }

    .product-buy .btn:hover {
        cursor: pointer;
    }
</style>
<?php
require('header.php');
?>

<?php
require('navbar.php');
?>

<div id='contents' class="site-width" style="border:solid 2px #8c7851;">

    <section id="main">
        <p class="badge"> <?php echo sanitize($viewData['category']); ?></p>
        <div class="title">
            <?php echo sanitize($viewData['name']); ?>s
        </div>
        <div class="size_data">
            <?php echo sanitize($viewData['size_id']); ?><span>㎝</span>
        </div>
        <div class="img-main">
            <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="メイン画像:<?php echo sanitize($viewData['name']); ?> " id="js-switch-img-main">
        </div>
        <div class="img-sub-container">
            <div class="img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="画像1:<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="画像2:<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
                <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="画像3:<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
            </div>
        </div>
        <div class="price-container">
            <p class="price">￥ <?php echo sanitize(number_format($viewData['price'])); ?> <span style="font-size:16px;">税込み/送料込み</span> </p>
        </div>
        <form action="" method="post">
            <div class="item-center">
                <input type="submit" name="submit" value="購入する" 　class="btn btn-primary" style="margin-top:0;">
            </div>
        </form>
        <div class="product-detail">
            <p><?php echo sanitize($viewData['comment']) ?></p>
        </div>
        <div class="product-buy">
            <div class="item-left">
                <a href="index.php<?php appendGetParam(array('p_id')); ?>">&lt; 商品一覧に戻る</a>
            </div>
        </div>
    </section>

</div>

<?php
require('footer.php');
?>