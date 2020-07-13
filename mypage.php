<?php
//共有変数・関数ファイルを読み込み
require('function.php');

debug('===========================================================');
debug('マイページ');
debug('===========================================================');
debugLogStart();

//ログイン認証
require('auth.php');

//画面表示用データ取得
//===========================================
$u_id = $_SESSION['user_id'];
//DBから商品データを取得
$productData = getMyProducts($u_id);

$partnerUserData = getUser($u_id);
//DBから連絡掲示板データを取得
$boardData = getMyMsgsAndBoard($u_id);
//DBからお気に入りデータを取得
$likeData = getMyLike($u_id);


//DBからきちんとデータが全て取れているのかチェックは行わず，取れなければ何を表示しないこととする

debug('取得した商品データ：' . print_r($productData, true));
debug('取得した掲示板データ：' . print_r($boardData, true));
debug('取得したお気に入りデータ；' . print_r($likeData, true));

debug('画面表示処理終了　<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = '';
require('head.php');
?>
<style>
    .my-page {
        margin: 0 auto;
        padding: 30px;
        width: 650px;
        height: 100%;
        border: 2px solid #8c7851;
        background-color: #fffffe;
        color: #020826;
    }

    .page-title {
        font-size: 28px;
        margin-right: 240px;
        margin-top: 30px;
        margin-bottom: 30px;
        text-align: center;
        color: #020826;
    }

    .price {
        font-weight: bold;
        font-family: "Lobster", cursive;
        background: none;
        color: #020826;
        padding: 3px;
        position: static;


    }

    .panel-list>.panel {
        float: left;
        box-sizing: border-box;
        margin-bottom: 50px;
        padding-right: 15px;
        margin-left: 15px;
        height: 220px;
        width: 30%;
        text-decoration: none;
        line-height: 1.2em;
    }

    .panel-list>.panel img {
        width: 100%;
        height: 180px;
        vertical-align: middle;
        object-fit: cover;
    }

    .list-table>.table>tbody td {
        border-right: none;
        background: #eaddcf;
    }
</style>

<body class="page-mypage page-2colum">


    <?php
    require('header.php');
    ?>

    <?php
    require('navbar.php');
    ?>

    <p id="js-show-msg" style="display: none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
    </p>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

        <h1 class="page-title">MYPAGE</h1>

        <!-- Main -->
        <section class="my-page">
            <section class="list panel-list">
                <h2 class="title" style="margin-bottom:15px;">
                    登録商品一覧
                </h2>
                <?php
                if (!empty($productData)) :
                    foreach ($productData as $key => $val) :
                ?>
                        <a href="registProduct.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">
                            <div class="panel-head">
                                <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                            </div>
                            <div class="panel-body">
                                <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
                            </div>
                        </a>
                <?php
                    endforeach;
                endif;
                ?>
            </section>

            <style>
                .list {
                    margin-bottom: 30px;
                }
            </style>

            <section class="list list-table">
                <h2 class="title">
                    連絡掲示板一覧
                </h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>最新送信日時</th>
                            <th>取引相手</th>
                            <th>メッセージ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($boardData)) {
                            foreach ($boardData as $key => $val) {
                                if (!empty($val['msg'])) {
                                    $msg = array_shift($val['msg']);
                        ?>
                                    <tr>
                                        <td><?php echo sanitize(date('Y.m.d H:i', strtotime($msg['send_date']))); ?></td>
                                        <td><?php echo sanitize($val['id']); ?> </td>
                                        <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><?php echo mb_substr(sanitize($msg['msg']), 0, 40); ?>...</a></td>
                                    </tr>
                                <?php
                                } else {
                                ?>
                                    <tr>
                                        <td>--</td>
                                        <td>◯◯ ◯◯</td>
                                        <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>">まだメッセージはありません</a></td>
                                    </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>

            <section class="list panel-list">
                <h2 class="title" style="margin-bottom:15px;">
                    お気に入り一覧
                </h2>
                <?php
                if (!empty($likeData)) :
                    foreach ($likeData as $key => $val) :
                ?>
                        <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">
                            <div class="panel-head">
                                <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                            </div>
                            <div class="panel-body">
                                <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
                            </div>
                        </a>
                <?php
                    endforeach;
                endif;
                ?>
            </section>
        </section>
    </div>
    <?php
    require('footer.php');
    ?>