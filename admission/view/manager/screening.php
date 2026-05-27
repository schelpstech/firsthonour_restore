<?php
include '../../include/php/adminnav.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <!-- Create New Form -->
        <div class="form-head d-flex flex-wrap mb-sm-4 mb-3 align-items-center">
            <div class="mr-auto  d-lg-block mb-3">
                <h2 class="text-black mb-0 font-w700">My Application Forms</h2>
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

        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>Form Number</strong></th>
                                        <th scope="col"><strong>Applicant Fullname</strong></th>
                                        <th scope="col"><strong>Prospective Class</strong></th>
                                        <th scope="col"><strong>Exam Schedule</strong></th>
                                        <th scope="col"><strong>Exam Score</strong></th>
                                        <th scope="col"><strong>Admission Status</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($form_list as $view) {
                                        $tbl = 'lhpclass';
                                        $conditions = array(
                                            'return_type' => 'single',
                                            'where' => array(
                                                'classid' =>  $view['classid'],
                                            )
                                        );
                                        $classname = $model->getRows($tbl, $conditions);
                                    ?>
                                        <tr>
                                            <td><?php echo $view['form_number'] ?></td>
                                            <td><?php echo $view['surname'] . ' ' . $view['firstname'] . ' ' . $view['othername'] ?></td>
                                            <td><?php echo $classname['classname'] ?></td>
                                            <td>
                                                <?php
                                                    if( $view['payment_verified'] == 11 && $view['exam_date'] != ''  ){
                                                        echo'<a href="./reportviewer.php?form_examschedule='.$view['form_number'].'" data-placement="top" title="Print Exam Schedule"><span class="badge light badge-primary">'.$view['exam_date'].'<br> Click to Print </span></a>';
                                                    }elseif( $view['payment_verified'] == 11 && $view['exam_date'] == ''  ){
                                                        echo'<a href="./formfiller.php?form_examschedule='.$view['form_number'].'"  data-placement="top" title="Schedule Entrance Exam"><span class="badge light badge-success"> Click to Schedule Entrance Exam </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?payment='.$view['form_number'].'"  data-placement="top" title="Schedule Entrance Exam"><span class="badge light badge-danger"> Form Payment Pending</span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if( $view['payment_verified'] == 11 && $view['exam_score'] != "" && $view['exam_date'] != "" ){
                                                        echo'<a href="./reportviewer.php?form_score='.$view['form_number'].'"  data-placement="top" title="Print Entrance Exam Score"><span class="badge light badge-success"> Print Result Slip</span></a>';
                                                    }elseif( $view['payment_verified'] == 11 && $view['exam_score'] == "" && $view['exam_date'] != "" ){
                                                        echo'<a href="./formfiller.php?form_score='.$view['form_number'].'"  data-placement="top" title="Input Exam Score"><span class="badge light badge-info"> Input Exam Score</span></a>';
                                                    }else{
                                                        echo'<a href="#"  data-placement="top" title="Entrance Exam Score"><span class="badge light badge-warning"> Pending </span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($view['admission_status'] != ""){
                                                        echo'<a href="./reportviewer.php?form_status='.$view['form_number'].'"  data-placement="top" title="Check Admission Status"><span class="badge light badge-success"> Print Admission Decision </span></a>';
                                                    }else{
                                                        echo'<a href="#"  data-placement="top" title="Admission Status"><span class="badge light badge-warning"> Pending </span></a>';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../../include/php/portalfoot.php';
?>