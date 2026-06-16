<?php

include "../conf.php";

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['unamed'])) {
  header('Location: ../index.php');
  exit();
}

/*
|--------------------------------------------------------------------------
| Request Parameters
|--------------------------------------------------------------------------
*/

$term  = $_GET['term'] ?? ($_SESSION['term'] ?? '');
$lname = $_GET['lid'] ?? ($_SESSION['lname'] ?? '');

$_SESSION['term']  = $term;
$_SESSION['lname'] = $lname;

if (empty($term) || empty($lname)) {
  die('Invalid request.');
}

/*
|--------------------------------------------------------------------------
| Student Information
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT
        fname,
        gender,
        dob,
        classid,
        picture
    FROM lhpuser
    WHERE uname = ?
    LIMIT 1
");

$stmt->bind_param("s", $lname);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
  die('Student record not found.');
}

$stname = $user['fname'];
$gender = $user['gender'];
$dob    = $user['dob'];
$cclass = $user['classid'];
$pix    = $user['picture'];

/*
|--------------------------------------------------------------------------
| Get Actual Class From Current Term Record
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT classid
    FROM lhpweekrecord
    WHERE lid = ?
    AND term = ?
    LIMIT 1
");

$stmt->bind_param("ss", $lname, $term);
$stmt->execute();

$classRecord = $stmt->get_result()->fetch_assoc();

if (!empty($classRecord['classid'])) {
  $cclass = $classRecord['classid'];
}

/*
|--------------------------------------------------------------------------
| Class Information
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT classname
    FROM lhpclass
    WHERE classid = ?
    LIMIT 1
");

$stmt->bind_param("s", $cclass);
$stmt->execute();

$classInfo = $stmt->get_result()->fetch_assoc();

$dclass = $classInfo['classname'] ?? '';

/*
|--------------------------------------------------------------------------
| Class Population
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT COUNT(DISTINCT lid) AS pop
    FROM lhpweekrecord
    WHERE classid = ?
    AND term = ?
");

$stmt->bind_param("ss", $cclass, $term);
$stmt->execute();

$populationData = $stmt->get_result()->fetch_assoc();

$pop = (int)($populationData['pop'] ?? 0);

/*
|--------------------------------------------------------------------------
| Result Configuration
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT
        resumption,
        sch_open,
        signature
    FROM lhpresultconfig
    WHERE term = ?
    LIMIT 1
");

$stmt->bind_param("s", $term);
$stmt->execute();

$config = $stmt->get_result()->fetch_assoc();

$resumedate = $config['resumption'] ?? '';
$opendays   = $config['sch_open'] ?? '';
$sign       = $config['signature'] ?? '';

/*
|--------------------------------------------------------------------------
| School Information
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['school_info'])) {

  $result = $con->query("
        SELECT
            schname,
            motto,
            founded,
            phone,
            email,
            website,
            address,
            logo,
            proprietor
        FROM lhpschool
        LIMIT 1
    ");

  $_SESSION['school_info'] = $result->fetch_assoc();
}

$school = $_SESSION['school_info'];

$schname    = $school['schname'] ?? '';
$schmotto   = $school['motto'] ?? '';
$schyear    = $school['founded'] ?? '';
$schphone   = $school['phone'] ?? '';
$schemail   = $school['email'] ?? '';
$schweb     = $school['website'] ?? '';
$schaddress = $school['address'] ?? '';
$schlogo    = $school['logo'] ?? '';
$schowner   = $school['proprietor'] ?? '';

/*
|--------------------------------------------------------------------------
| Class Teacher
|--------------------------------------------------------------------------
*/

$stmt = $con->prepare("
    SELECT tutorid
    FROM lhpclassalloc
    WHERE classid = ?
    AND term = ?
    LIMIT 1
");

$stmt->bind_param("ss", $cclass, $term);
$stmt->execute();

$alloc = $stmt->get_result()->fetch_assoc();

$tutor = $alloc['tutorid'] ?? '';

$tutorname = '';

if (!empty($tutor)) {

  $stmt = $con->prepare("
        SELECT staffname
        FROM lhpstaff
        WHERE sname = ?
        LIMIT 1
    ");

  $stmt->bind_param("s", $tutor);
  $stmt->execute();

  $staff = $stmt->get_result()->fetch_assoc();

  $tutorname = $staff['staffname'] ?? '';
}

?>






<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Learner's Profile - Learnable</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- favicon
		============================================ -->
  <link rel="shortcut icon" type="image/x-icon" href="https://rabbischools.com.ng/press/wp-content/uploads/2020/04/icon.jpg">
  <!-- Google Fonts
		============================================ -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
  <!-- Bootstrap CSS
		============================================ -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- font awesome CSS
		============================================ -->
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <!-- owl.carousel CSS
		============================================ -->
  <link rel="stylesheet" href="css/owl.carousel.css">
  <link rel="stylesheet" href="css/owl.theme.css">
  <link rel="stylesheet" href="css/owl.transitions.css">
  <!-- meanmenu CSS
		============================================ -->
  <link rel="stylesheet" href="css/meanmenu/meanmenu.min.css">
  <!-- animate CSS
		============================================ -->
  <link rel="stylesheet" href="css/animate.css">
  <!-- normalize CSS
		============================================ -->
  <link rel="stylesheet" href="css/normalize.css">
  <!-- wave CSS
		============================================ -->
  <link rel="stylesheet" href="css/wave/waves.min.css">
  <link rel="stylesheet" href="css/wave/button.css">
  <!-- mCustomScrollbar CSS
		============================================ -->
  <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
  <!-- Notika icon CSS
		============================================ -->
  <link rel="stylesheet" href="css/notika-custom-icon.css">
  <!-- Data Table JS
		============================================ -->
  <link rel="stylesheet" href="css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
  <!-- main CSS
		============================================ -->
  <link rel="stylesheet" href="css/main.css">
  <!-- style CSS
		============================================ -->
  <link rel="stylesheet" href="style.css">
  <!-- responsive CSS
		============================================ -->
  <link rel="stylesheet" href="css/responsive.css">
  <!-- modernizr JS
		============================================ -->
  <script src="js/html2pdf.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="./chartload.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

  <script>
    function generatePDF() {


      var divContents = $("#doc").html();
      var printWindow = window.open('', '', 'height=800,width=1600');

      printWindow.document.write('<html><head><title>Academic Reportsheets for <?php echo $stname . "   " . $dclass ?></title>');
      printWindow.document.write('</head><body >');
      printWindow.document.write(divContents);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();

    }
  </script>


  <script src="https://d3js.org/d3.v5.min.js"></script>

  <script src="js/vendor/modernizr-2.8.3.min.js"></script>

  <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.33/vfs_fonts.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.4/angular.min.js"></script>
  <script src="./script.js"></script>
</head>

<body>
  <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
  <!-- Start Header Top Area -->
  <div class="header-top-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
          <div class="logo-area">
            <a href="#"><img src="img/logo/logo.png" alt="" /></a>
          </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">

        </div>
      </div>
    </div>
  </div>
  <!-- End Header Top Area -->
  <!-- Mobile Menu start -->
  <?php include "nav.html"; ?>
  <!-- Main Menu area End-->
  <!-- Breadcomb area Start-->
  <div id="doc">


    <!-- Data Table area Start-->
    <div class="data-table-area" style="text-align: center;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="normal-table-list mg-t-30">

              <div class="bsc-tbl-bdr">
                <table class="table table-bordered" style="width:100%">
                  <thead>
                    <tr>

                    </tr>
                  </thead>


                  <tbody>





                    <tr>
                      <td>
                        <image src="../admin/images/<?php echo $schlogo; ?>" width="150" height="150" /><br>
                        <strong>Founded: <?php echo $schyear; ?></strong>
                      </td>
                      <td>

                        <h1 style="text-align: center;"> <?php echo $schname; ?> </h1>
                        <p style="text-align: center;"> <?php echo $schmotto; ?> <br>
                          <?php echo $schaddress; ?> <br>
                          <?php echo $schphone; ?> | <?php echo $schemail; ?> <br> <?php echo $schweb ?> </p>
                        <h4 style="text-align: center;"> <?php echo $term . " " ?> <br>Mid - Term Academic Reportsheets for <?php echo $dclass ?></h4>
                      </td>
                      <td>
                        <image src="../learner/images/profilepix/<?php echo $pix ?>" width="150" height="150" /><br>
                        <strong>Learners ID : <?php echo $lname; ?></strong>
                      </td>
                    </tr>

                  </tbody>

                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><br>


    <div class="data-table-area" style="text-align: center;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="normal-table-list mg-t-30">
              <div class="basic-tb-hd">
                <strong>
                  <h3 style="text-align: center;">Learners Details</h3>
                </strong>

              </div>

              <div class="bsc-tbl-bdr">
                <table class="table table-bordered" style="width:100%;" border="1">
                  <thead>
                    <tr>

                      <th style="text-align: center;">Fullname</th>
                      <th style="text-align: center;"> Gender</th>
                      <th style="text-align: center;">Date of Birth</th>
                      <th style="text-align: center;">Current Class </th>
                      <th style="text-align: center;"> Class Teacher</th>
                      <th style="text-align: center;"> Class Population</th>

                    </tr>
                  </thead>


                  <tbody>





                    <tr>

                      <td><strong>
                          <h4 style="text-align: center;"> <?php echo $stname ?></h4>
                        </strong></td>
                      <td><strong>
                          <p style="text-align: center;"><?php echo $gender ?></p>
                        </strong></td>
                      <td><strong>
                          <p style="text-align: center;"><?php echo $dob ?></p>
                        </strong></td>
                      <td><strong>
                          <p style="text-align: center;"><?php echo $dclass; ?></p>
                        </strong></td>
                      <td><strong>
                          <p style="text-align: center;"><?php echo $tutorname; ?></p>
                        </strong></td>
                      <td><strong>
                          <p style="text-align: center;"><?php echo $pop; ?></p>
                        </strong></td>
                    </tr>

                  </tbody>

                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Academic Performance Report -->

    <div id="academic-report" class="data-table-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">


            <div class="normal-table-list mg-t-30">

              <div class="basic-tb-hd">
                <h3 class="text-center">
                  Academic Performance Report
                </h3>
              </div>

              <div class="bsc-tbl-bdr">

                <table class="table table-bordered" width="100%">
                  <thead>
                    <tr>
                      <th>Subject</th>
                      <th>Week 1</th>
                      <th>Week 2</th>
                      <th>Week 3</th>
                      <th>Week 4</th>
                      <th>Week 5</th>
                      <th>Week 6</th>
                      <th>Total</th>
                      <th>Grade</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>

                  <tbody>


                    <?php

                    $stmt = $con->prepare("
SELECT
    w.subjid,
    MAX(s.sbjname) AS sbjname,

    MAX(CASE WHEN w.week='Week 1' THEN w.score ELSE 0 END) AS week1,
    MAX(CASE WHEN w.week='Week 2' THEN w.score ELSE 0 END) AS week2,
    MAX(CASE WHEN w.week='Week 3' THEN w.score ELSE 0 END) AS week3,
    MAX(CASE WHEN w.week='Week 4' THEN w.score ELSE 0 END) AS week4,
    MAX(CASE WHEN w.week='Week 5' THEN w.score ELSE 0 END) AS week5,
    MAX(CASE WHEN w.week='Week 6' THEN w.score ELSE 0 END) AS week6

FROM lhpweekrecord w
INNER JOIN lhpsubject s
    ON s.sbjid = w.subjid

WHERE w.lid = ?
AND w.term = ?

GROUP BY w.subjid
ORDER BY sbjname
");

                    $stmt->bind_param("ss", $lname, $term);
                    $stmt->execute();

                    $result = $stmt->get_result();

                    $grandTotal = 0;
                    $subjectCount = 0;

                    while ($row = $result->fetch_assoc()) {

                      $week1 = (float)$row['week1'];
                      $week2 = (float)$row['week2'];
                      $week3 = (float)$row['week3'];
                      $week4 = (float)$row['week4'];
                      $week5 = (float)$row['week5'];
                      $week6 = (float)$row['week6'];

                      $totalScore =
                        ($week1 +
                          $week2 +
                          $week3 +
                          $week4 +
                          $week5 +
                          $week6) / 2;

                      $grandTotal += $totalScore;
                      $subjectCount++;

                      if ($totalScore >= 22.5) {
                        $grade = "A";
                        $remarks = "Excellent";
                      } elseif ($totalScore >= 19.5) {
                        $grade = "B";
                        $remarks = "Very Good";
                      } elseif ($totalScore >= 15) {
                        $grade = "C";
                        $remarks = "Moderate";
                      } elseif ($totalScore >= 13.5) {
                        $grade = "D";
                        $remarks = "Fair";
                      } elseif ($totalScore >= 12) {
                        $grade = "E";
                        $remarks = "Needs Help";
                      } else {
                        $grade = "F";
                        $remarks = "Needs Help";
                      }

                    ?>

                      <tr>
                        <td><?= strtoupper(htmlspecialchars($row['sbjname'])) ?> - (<?= $row['subjid'] ?>)</td>


                        <td><?= $week1 ?></td>
                        <td><?= $week2 ?></td>
                        <td><?= $week3 ?></td>
                        <td><?= $week4 ?></td>
                        <td><?= $week5 ?></td>
                        <td><?= $week6 ?></td>

                        <td><?= number_format($totalScore, 1) ?></td>

                        <td>
                          <strong><?= $grade ?></strong>
                        </td>

                        <td><?= $remarks ?></td>


                      </tr>

                    <?php } ?>

                  </tbody>
                </table>

              </div>
            </div>

          </div>
        </div>
      </div>


    </div>

    <?php

    $averageScore =
      ($subjectCount > 0)
      ? ($grandTotal / $subjectCount)
      : 0;

    ?>

    <div class="breadcomb-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="breadcomb-list">
              <div class="row">


                <div class="breadcomb-icon">
                  <h4 style="text-align: left;"> Authorised by </h4>
                  <image src="../admin/archive/<?php echo $sign; ?>" height="100" width="100" />
                  <h3 style="text-align: left;"> <?php echo $schowner; ?> </h3>
                  <br>
                  <br>
                  <strong> <small style="text-align: center;"> Grade : Mark Obtainable - 30 **** 0 - 11.9 :: Need Help (F) **** 12 - 13.4 :: Needs Help (E) **** 13.5 - 14.9 :: Fair (D) **** 15 - 19.4 :: Moderate (C) **** 19.5 - 22.4 :: Very Good (B) **** 22.5 - 30 :: Excellent (A)</small> </strong>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>






  <button id="cmd" onclick="generatePDF()" class="btn btn-default btn-icon-notika"><i class="notika-icon notika-down-arrow"></i>
    <h3>Download Results</h3>
  </button>
  <!-- Start Footer area-->
  <?php include "foot.html"; ?>

  <!-- End Footer area-->
  <!-- jquery
		============================================ -->
  <script src="js/vendor/jquery-1.12.4.min.js"></script>
  <!-- bootstrap JS
		============================================ -->
  <script src="js/bootstrap.min.js"></script>
  <!-- wow JS
		============================================ -->
  <script src="js/wow.min.js"></script>
  <!-- price-slider JS
		============================================ -->
  <script src="js/jquery-price-slider.js"></script>
  <!-- owl.carousel JS
		============================================ -->
  <script src="js/owl.carousel.min.js"></script>
  <!-- scrollUp JS
		============================================ -->
  <script src="js/jquery.scrollUp.min.js"></script>
  <!-- meanmenu JS
		============================================ -->
  <script src="js/meanmenu/jquery.meanmenu.js"></script>
  <!-- counterup JS
		============================================ -->
  <script src="js/counterup/jquery.counterup.min.js"></script>
  <script src="js/counterup/waypoints.min.js"></script>
  <script src="js/counterup/counterup-active.js"></script>
  <!-- mCustomScrollbar JS
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		============================================ -->
  <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
  <!-- sparkline JS
		============================================ -->
  <script src="js/sparkline/jquery.sparkline.min.js"></script>
  <script src="js/sparkline/sparkline-active.js"></script>
  <!-- flot JS
		============================================ -->
  <script src="js/flot/jquery.flot.js"></script>
  <script src="js/flot/jquery.flot.resize.js"></script>
  <script src="js/flot/flot-active.js"></script>
  <!-- knob JS
		============================================ -->
  <script src="js/knob/jquery.knob.js"></script>
  <script src="js/knob/jquery.appear.js"></script>
  <script src="js/knob/knob-active.js"></script>
  <!--  Chat JS
		============================================ -->
  <script src="js/chat/jquery.chat.js"></script>
  <!--  todo JS
		============================================ -->
  <script src="js/todo/jquery.todo.js"></script>
  <!--  wave JS
		============================================ -->
  <script src="js/wave/waves.min.js"></script>
  <script src="js/wave/wave-active.js"></script>
  <!-- plugins JS
		============================================ -->
  <script src="js/plugins.js"></script>
  <!-- Data Table JS
		============================================ -->
  <script src="js/data-table/jquery.dataTables.min.js"></script>
  <script src="js/data-table/data-table-act.js"></script>
  <!-- main JS
		============================================ -->
  <script src="js/charts/Chart.js"></script>
  <script src="js/charts/bar-chart.js"></script>
  <script src="js/main.js"></script>


</body>

</html>