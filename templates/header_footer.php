<?php 
function renderHead($config, $pageType = 'main') // default เป็น main
{
    // เซ็ตค่า class และไฟล์ CSS ตามประเภท
      $htmlClass = '';
    $extraCss = '';

    switch ($pageType) {
        case 'auth':
            $htmlClass = 'light-style customizer-hide';
            $extraCss = '<link rel="stylesheet" href="'.$config['url'].'assets/vendor/css/pages/page-auth.css" />';
            break;
        default:
            $htmlClass = 'light-style layout-menu-fixed';
            $extraCss = '<link rel="stylesheet" href="'.$config['url'].'assets/vendor/libs/apex-charts/apex-charts.css" />';
            break;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en" class="<?= $htmlClass ?>" dir="ltr" data-theme="theme-default"
        data-assets-path="<?= $config['url'] ?>assets/" data-template="vertical-menu-template-free">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <title><?= $config['title'] ?></title>
        <meta name="description" content="<?= $config['description'] ?>" />
        <link rel="icon" type="image/x-icon" href="<?= $config['url'] ?>assets/img/favicon/favicon.ico" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/fonts/boxicons.css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/css/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/css/demo.css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <?= $extraCss ?>
        <script src="<?= $config['url'] ?>assets/vendor/js/helpers.js"></script>
        <script src="<?= $config['url'] ?>assets/js/config.js"></script>
    </head>

    <body>
    <?php
} 

function renderFooter($config)
{
    ?>
        <!-- Core JS -->
        <script src="<?= $config['url'] ?>assets/vendor/libs/jquery/jquery.js"></script>
        <script src="<?= $config['url'] ?>assets/vendor/libs/popper/popper.js"></script>
        <script src="<?= $config['url'] ?>assets/vendor/js/bootstrap.js"></script>
        <script src="<?= $config['url'] ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="<?= $config['url'] ?>assets/vendor/js/menu.js"></script>
        <script src="<?= $config['url'] ?>assets/vendor/libs/apex-charts/apexcharts.js"></script>
        <script src="<?= $config['url'] ?>assets/js/main.js"></script>
        <script src="<?= $config['url'] ?>assets/js/dashboards-analytics.js"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>
    <?php
}
