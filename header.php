<!-- メニュー　-->
<header>
    <div class="site-width">
        <h1><a href="index.php">Shoe You</a></h1>
        <nav id="top-nav">
            <ul>

                <?php
                if (empty($_SESSION['user_id'])) {
                ?>
                    <li><a href="signup.php" class="btn-flat-border">Sign up</a></li>
                    <li><a href="login.php">Login</a></li>

                <?php
                } else {
                ?>
                    <img src="<?php echo getUser('pic'); ?>" alt="" class="top-img" style="<?php if (empty(getUser('pic'))) echo 'display:none;' ?>">
                    <li><a href="mypage.php" class="btn-flat-border">My Page</a></li>
                    <li><a href="logout.php">Logout</a></li>

                <?php
                }
                ?>

            </ul>
        </nav>
        <p id="js-show-msg" style="display:none;" class="msg-slide">
            <?php echo getSessionFlash('msg_success'); ?>
        </p>
    </div>
</header>