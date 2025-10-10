<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\widgets\Gnb;
use app\widgets\Footer;
use app\widgets\Floating;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html xml:lang="<?= Yii::$app->language ?>" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="HEROS COMICS, 히어로즈 코믹스">
    <meta name="keywords" content="">
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="format-detection" content="telephone=no, address=no, email=no">

    <?= Html::csrfMetaTags() ?>

    <link rel="shortcut icon" href="/assets/favicon/favicon.ico">
    <link rel="icon" href="/assets/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link href="/assets/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="/assets/js/lib/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/assets/js/lib/jquery.nice-select.min.js"></script>
    <script type="text/javascript" src="/assets/js/lib/swiper.min.js"></script>
    <script type="text/javascript" src="/assets/js/lib/jquery.rating.js"></script>
    <script type="text/javascript" src="/assets/js/entry.js"></script>
    <?php $this->head() ?>
</head>
<body class="have-top">
    <?php $this->beginBody() ?>
    <!-- skip -->
    <div id="skip" class="sr-only">
        <ul>
            <li><a href="#gnb">메뉴 바로가기</a></li>
            <li><a href="#contents">본문 바로가기</a></li>
            <li><a href="#footer">하단 바로가기</a></li>
        </ul>
    </div>
    <!-- //skip -->

    <section id="wrapper">
        <?= $this->render('gnb') ?>
        <section id="contents">
            <?= $content ?>
            <?=Floating::widget()?>
        </section>
        <!--// mainWrap -->

        <?= $this->render('footer') ?>
    </section>
    <!--// wrap -->

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>