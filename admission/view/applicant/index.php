<?php
include '../../include/php/header.php';
?>

<body class="vh-100" onload="load_signup_page()">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <?php
                    if (isset($_SESSION['msg'])) {
                        printf('<b>%s</b>', $_SESSION['msg']);
                        unset($_SESSION['msg']);
                    }
                    ?>
                    <div class="authincation-content" id="section">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../../include/php/footer.php';
    ?>