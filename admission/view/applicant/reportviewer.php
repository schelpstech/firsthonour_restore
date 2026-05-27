<?php
include '../../include/php/navbar.php';

if (isset($_GET['form_biodata'])) {
    $form_number = $_GET['form_biodata'];
    $form = 'Applicant Bio-data Form';
    //Select Available Forms
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);

    $include = "../../include/form/biodata.php";
}


if (isset($_GET['form_academic'])) {
    $form_number = $_GET['form_academic'];
    $form = 'Applicant Academic Form';
    //Select Available Forms
    $parent_ref = $_SESSION['uniqueid'];
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/form/academic.php";
}
if (isset($_GET['form_contact'])) {
    $form_number = $_GET['form_contact'];
    $form = 'Applicant Contact Information Form';
    //Select Available Forms
    $parent_ref = $_SESSION['uniqueid'];
    //Notes Counter
    $tblName = 'application_tbl';
    $conditions = array(
        'join' => 'tbl_users on application_tbl.parent_ref = tbl_users.user_phone',
        'return_type' => 'single',
        'where' => array(
            'application_tbl.form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/form/contact.php";
}

if (isset($_GET['form_health'])) {
    $form_number = $_GET['form_health'];
    $form = 'Applicant Health History Form';
    //Select Available Forms
    $parent_ref = $_SESSION['uniqueid'];
    //Notes Counter
    $tblName = 'application_tbl';
    $conditions = array(
        'join' => 'tbl_users on application_tbl.parent_ref = tbl_users.user_phone',
        'return_type' => 'single',
        'where' => array(
            'application_tbl.form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/form/health.php";
}

if (isset($_GET['form_attest'])) {
    $form_number = $_GET['form_attest'];
    $form = 'Applicant Attestation Form';
    //Select Available Forms
    $parent_ref = $_SESSION['uniqueid'];
    //Notes Counter
    $tblName = 'application_tbl';
    $conditions = array(
        'join' => 'tbl_users on application_tbl.parent_ref = tbl_users.user_phone',
        'return_type' => 'single',
        'where' => array(
            'application_tbl.form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/form/attestation.php";
}

if (isset($_GET['payment'])) {
    $form_number = $_GET['payment'];
    $report = 'Registration Form Payment Receipt';
    //Select Available Forms
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/report/payment.php";
}

if (isset($_GET['form_examschedule'])) {
    $form_number = $_GET['form_examschedule'];
    $report = 'Entrance Exam Schedule Slip';
    //Select Available Forms
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'join' => 'lhpclass on application_tbl.classid = lhpclass.classid',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/report/schedule.php";
}

if (isset($_GET['form_score'])) {
    $form_number = $_GET['form_score'];
    $report = 'Entrance Examination Result Slip';
    //Select Available Forms
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'join' => 'lhpclass on application_tbl.classid = lhpclass.classid',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/report/result.php";
}

if (isset($_GET['form_status'])) {
    $form_number = $_GET['form_status'];
    $report = 'Admission Decision';
    //Select Available Forms
    $tblName = 'application_tbl';
    $conditions = array(
        'return_type' => 'single',
        'join' => 'lhpclass on application_tbl.classid = lhpclass.classid',
        'where' => array(
            'form_number' =>  $form_number,
        )
    );
    $form_view = $model->getRows($tblName, $conditions);
    $include = "../../include/report/status.php";
}
if (isset($_GET['profile'])) {
    $profile = $_GET['profile'];
    $form = 'Parent Profile Update';
    $include = "../../include/form/profile.php";
}

?>
    <script>
        function print_functionr() {
           window.print();
        }
    </script>
<div  id="myDiv" class="content-body">
    <div class="container-fluid">
        <!-- Create New Form -->
        <div class="form-head d-flex flex-wrap mb-sm-4 mb-3 align-items-center">
            <div class="mr-auto  d-lg-block mb-3">
                <a href="myform.php">
                    <h2 class="text-black mb-0 font-w700"> Report :: <?php echo $report ?></h2>
                </a>
            </div>
        </div>
        <div class="row page-titles mx-0">
            <?php
            if (isset($_SESSION['msg'])) {
                printf('<b>%s</b>', $_SESSION['msg']);
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <!-- row -->
        <?php include $include; ?>
    </div>
</div>
<?php
include '../../include/php/portalfoot.php';
?>