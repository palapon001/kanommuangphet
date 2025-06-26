<?php
function renderHead($config, $pageType = 'main') // default เป็น main
{
    // เซ็ตค่า class และไฟล์ CSS ตามประเภท
    $htmlClass = '';
    $extraCss = '';

    switch ($pageType) {
        case 'auth':
            $htmlClass = 'light-style customizer-hide';
            $extraCss = '<link rel="stylesheet" href="' . $config['url'] . 'assets/vendor/css/pages/page-auth.css" />';
            break;
        default:
            $htmlClass = 'light-style layout-menu-fixed';
            $extraCss = '<link rel="stylesheet" href="' . $config['url'] . 'assets/vendor/libs/apex-charts/apex-charts.css" />';
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
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/css/core.css"
            class="template-customizer-core-css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/css/theme-default.css"
            class="template-customizer-theme-css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/css/demo.css" />
        <link rel="stylesheet" href="<?= $config['url'] ?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
        <?= $extraCss ?>
        <script src="<?= $config['url'] ?>assets/vendor/js/helpers.js"></script>
        <script src="<?= $config['url'] ?>assets/js/config.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- DataTables CSS -->
        <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- DataTables Buttons CSS -->
        <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet" />

        <!-- DataTables Buttons JS และ dependencies -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    </head>

    <body>  
        <!-- sweetalert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .my-swal-container {
                z-index: 1100 !important;
            }
        </style>
        <script>
            function alertConfirm(message, path) {
                Swal.fire({
                    title: 'ยืนยัน',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก', customClass: {
                        container: 'my-swal-container'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = path;
                    }
                });
            }
        </script>
        <?php if (!empty($_GET['alert']) && !empty($_GET['text'])): ?>
            <script>
                Swal.fire({
                    icon: <?= json_encode($_GET['alert']) ?>,
                    title: <?= json_encode($_GET['text'] ?? '') ?>,
                    confirmButtonText: 'ตกลง',
                    customClass: {
                        container: 'my-swal-container'
                    }
                }).then(() => {
                    // ลบ query string ของ alert/text ออก
                    const url = new URL(window.location.href);
                    url.searchParams.delete('alert');
                    url.searchParams.delete('text');
                    window.history.replaceState(null, '', url);
                });
            </script>
        <?php endif; ?>
        <!-- / sweetalert2 -->
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
