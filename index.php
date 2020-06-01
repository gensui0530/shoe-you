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
//パラメータに不正な値が入っているかチェック
if (!is_int((int) $currentPageNum)) {
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php"); //トップページへ
}

//表示件数
$listSpan = 20;
// 現在の表示コード先頭を算出
$currentMinNum = (($currentPageNum - 1) * $listSpan); //1ページ目なら（1−1）＊20　＝0　2ページ目なら（2−１）＊20　＝20
//DBから商品データを取得
$dbProductData = getProductList($currentMinNum);
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



    <div class="top-search">

    </div>
    <section id="searchbar">
        <form action="" method="post">
            <div class="selectbox">
                <span class="icn_select"></span>
                <select name="sort">
                    <option value="1">金額が安い順</option>
                    <option value="2">金額が高い順</option>
                </select>
            </div>

            <div class="selectbox">
                <span class="icn_select"></span>
                <select name="sort">
                    <option value="1">スニーカー</option>
                    <option value="2">サンダル</option>
                    <option value="3">パンプス</option>
                    <option value="4">ブーツ</option>
                    <option value="5">ドレスシューズ</option>


                </select>
            </div>
            <input type="submit" value="検索">
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
                        <?php echo $currentMinNum + 1; ?></span> - <span class="num">
                        <?php echo $currentMinNum + $listSpan; ?></span>件 / <span class="num">
                        <?php echo sanitize($dbProductData['total']); ?></span>件中
                </div>
            </div>
            <div class="site-width">
                <div class="panel-list">
                    <?php
                    foreach ((array) $dbProductData['data'] as $key => $val) :
                    ?>
                        <a href="productDetail.php?p_id=<?php echo $val['id'] . '&p=' . $currentPageNum; ?>" class="panel">
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
            <?php pagination($currentPageNum, $dbProductData['total_page']); ?>

        </section>
    </div>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>