<?php

//共通変数/関数ファイルを読み込み
require('function.php');

debug("============================================");
debug("トップページ");
debug('============================================');
debugLogStart();

//==============================
// 画像処理
//==============================

//画面表示用データ取得
//==============================
//カレントページのGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

//カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

//パラメータに不正な値が入っているかチェック
if (!is_int((int) $currentPageNum)) {
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php"); //トップページへ
}

//表示件数
$listSpan = 20;
// 現在の表示コード先頭を算出
$currentMinNum = (((int) $currentPageNum - 1) * $listSpan); //1ページ目なら（1−1）＊20　＝0　2ページ目なら（2−１）＊20　＝20
//DBから商品データを取得
$dbProductData = getProductList($currentMinNum, $category, $sort);
//DBからカテゴリーデータを取得
$dbCategoryData = getCategory();
debug('現在のページ：' . $currentPageNum);






?>


<?php
$siteTitle = 'トップページ';
require('head.php');
?>

<body class="page-signup page-1colum">

    <!-- メニュー　-->
    <?php
    require('header.php');
    ?>

    <p id="js-show-msg" style="display: none;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
    </p>


    <div class="top-search">

    </div>
    <section id="searchbar">
        <form class="search-form" action="index.php#contents" method="get">
            <div class="selectbox">
                <span class="icn_select"></span>
                <select class="category_select" name="c_id">
                    <option value="0" <?php if (getFormData('c_id', true) == 0) {
                                            echo 'selected';
                                        } ?>>カテゴリー</option>
                    <?php
                    foreach ($dbCategoryData  as $key => $val) {
                    ?>

                        <option value="<?php echo $val['id'] ?>" <?php if (getFormData('c_id', true) == $val['id']) {
                                                                        echo 'selected';
                                                                    } ?>>
                            <?php echo $val['name']; ?>
                        </option>


                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="selectbox">
                <span class="icn_select"></span>
                <select class="sort_select" name="sort">
                    <option value="0" <?php if (getFormData('sort', true) == 0) {
                                            echo 'selected';
                                        } ?>>価格順
                    </option>
                    <option value="1" <?php if (getFormData('sort', true) == 1) {
                                            echo 'selected';
                                        } ?>>金額が安い順
                    </option>
                    <option value="2" <?php if (getFormData('sort', true) == 2) {
                                            echo 'selected';
                                        } ?>>金額が高い順
                    </option>


                </select>
            </div>


            <input style="margin-top: 5px;" type="submit" value="検索">
        </form>
    </section>
    <div class="banner">
        <img src="img/banner.jpg" id="top-banner">
        <h2 class="top-title">Shoe You </h2>
        <p class="top-message">素敵な靴をあなたに使って欲しい・・・</p>
    </div>
    <div id="contents" style="margin-top:0px; ">
        <section id="main">
            <div class="search-title">
                <div class="search-left">
                    <span class="total-num">
                        <?php echo sanitize($dbProductData['total']); ?></span>件の素敵な靴があります
                </div>
                <div class="search-right">
                    <span class="num">
                        <?php echo (!empty($dbProductData['data'])) ? $currentMinNum + 1 : 0; ?>
                    </span>
                    - <span class="num"><?php echo $currentMinNum + count($dbProductData['data']); ?></span>
                    件　/
                    <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>
                    件中
                </div>
            </div>
            <div class="site-width">
                <div class="panel-list">
                    <?php
                    foreach ((array) $dbProductData['data'] as $key => $val) :
                    ?>
                        <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">
                            <div class="panel-head">
                                <img src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>">
                                <p class="price">¥<?php echo sanitize(number_format($val['price'])); ?></p>
                            </div>
                            <div class="panel-body">

                                <p class="panel-title"><?php echo sanitize($val['name']); ?></p>
                                <p><?php echo sanitize(number_format($val['size_id']));  ?><span>cm</span></p>

                            </div>
                        </a>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <?php pagination($currentPageNum, $dbProductData['total_page'], '&c_id=' . $category . '&sort=' . $sort); ?>

        </section>
    </div>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>