<?php
include '../../controller/start.inc.php';
//Check login status
if (!isset($_SESSION['uniqueid']) || empty($_SESSION['uniqueid'])) {
    $response = '
               <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					<strong>Error!</strong> Access Denied! You are attempting login from an unsecured page!
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
              ';
    $user->redirect('../../view/applicant/index.php');
    $_SESSION['msg'] = $response;
}
//Select Available Class
$tblName = 'lhpclass';
$class_list = $model->getRows($tblName);
//Select Available Forms
$parent_ref = $_SESSION['uniqueid'];

$tblName = 'tbl_users';
$conditions = array(
    'return_type' => 'single',
    'where' => array(
        'user_phone' => $parent_ref,
    )
);
$parent = $model->getRows($tblName, $conditions);


$tblName = 'location';
$conditions = array(
    'select' => 'DISTINCT state',
    'order_by' => 'state ASC',
);
$location = $model->getRows($tblName, $conditions);


$tblName = 'application_tbl';
$conditions = array(
    'join' => 'tbl_users on application_tbl.parent_ref = tbl_users.user_phone',
    'where' => array(
        'application_tbl.parent_ref' => $parent_ref,
    )
);
$form_list = $model->getRows($tblName, $conditions);

//Count Forms
$tblName = 'application_tbl';
$conditions = array(
    'return_type' => 'count',
    'where' => array(
        'parent_ref' => $parent_ref,
        'admission_status' => 11,
    )
);
$count_success = $model->getRows($tblName, $conditions);

$tblName = 'application_tbl';
$conditions = array(
    'return_type' => 'count',
    'where' => array(
        'parent_ref' => $parent_ref,
    ),
    'wherenot' => array(
        'exam_score' => '',
    )
);
$count_exam = $model->getRows($tblName, $conditions);

$tblName = 'application_tbl';
$conditions = array(
    'return_type' => 'count',
    'where' => array(
        'parent_ref' => $parent_ref,
        'payment_verified' => 11,
    )
);
$count_payment = $model->getRows($tblName, $conditions);

$tblName = 'application_tbl';
$conditions = array(
    'return_type' => 'count',
    'where' => array(
        'parent_ref' => $parent_ref,
    )
);
$count_form = $model->getRows($tblName, $conditions);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admission Portal</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/firsthonourschlogo.png">
    <link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/form.js"></script>
    <script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js"> </script>
    <script type="text/javascript">
        function PrintElem(elem) {
            Popup($(elem).html());
        }

        function Popup(data) {
            var myWindow = window.open('', 'Payment Receipt', 'height=400,width=600');
            myWindow.document.write('<html><head><title>Payment Receipt</title>');
            myWindow.document.write('<link href="css/style.css" rel="stylesheet">');
            myWindow.document.write('</head><body >');
            myWindow.document.write(data);
            myWindow.document.write('</body></html>');
            myWindow.document.close(); // necessary for IE >= 10

            myWindow.onload = function () { // necessary if the div contain images

                myWindow.focus(); // necessary for IE >= 10
                myWindow.print();
                myWindow.close();
            };
        }
    </script>
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index-2.html" class="brand-logo">
                <img src="images/firsthonourschlogo.png" alt="">
            </a>
            <div class="nav-control">
                <div class="hamburger" id="navhide">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->


        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">

                            </div>
                        </div>
                        <ul class="navbar-nav header-right">

                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <img src="../../view/applicant/images/parent.jpg" width="20" alt=""
                                        class="rounded-circle" />
                                    <div class="header-info">
                                        <span>
                                            <?php echo $parent['user_surname'] ?>
                                        </span>
                                        <small>Parent/ Guardian</small>
                                    </div>
                                    <i class="fa fa-caret-down ml-3 mr-2 " aria-hidden="true"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="../../view/applicant/formfiller.php?profile=<?php echo $_SESSION['uniqueid'] ?>"
                                        class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ml-2">Profile </span>
                                    </a>
                                    <a href="../../app/manageuser.php?userid=<?php echo $_SESSION['uniqueid'] ?>"
                                        class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="ml-2">Logout </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">
                    <li><a class="ai-icon" href="home.php" aria-expanded="false">
                            <i class="flaticon-025-dashboard"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li><a class="ai-icon" href="./myform.php" aria-expanded="false">
                            <i class="flaticon-072-printer"></i>
                            <span class="nav-text">Application Form</span>
                        </a>
                    </li>
                    <li><a class="ai-icon" href="./payment.php" aria-expanded="false">
                            <i class="flaticon-043-menu"></i>
                            <span class="nav-text">Payment</span>
                        </a>
                    </li>
                    <li><a class="ai-icon" href="./screening.php" aria-expanded="false">
                            <i class="flaticon-022-copy"></i>
                            <span class="nav-text">Screening</span>
                        </a>
                    </li>
                    <li><a class="ai-icon"
                            href="../../view/applicant/formfiller.php?profile=<?php echo $_SESSION['uniqueid'] ?>"
                            aria-expanded="false">
                            <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="32"
                                height="32" viewBox="0 0 24 24" fill="grey" stroke="grey" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="nav-text">Parent Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>