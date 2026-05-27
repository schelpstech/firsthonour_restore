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
                                        <th scope="col"><strong>Biodata</strong></th>
                                        <th scope="col"><strong>Academic History</strong></th>
                                        <th scope="col"><strong>Contact Details</strong></th>
                                        <th scope="col"><strong>Health Record</strong></th>
                                        <th scope="col"><strong>Attestation</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($form_list)){
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
                                                    if($view['passport'] != "" && $view['surname'] != "" && $view['firstname'] != "" && $view['othername'] != "" && $view['gender'] != "" && $view['dateofbirth'] != "" && $view['state_of_origin'] != ""  ){
                                                        echo'<a href="./formfiller.php?form_biodata='.$view['form_number'].'" data-placement="top" title="Fill Biodata Form"><span class="badge light badge-success"> Completed </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?form_biodata='.$view['form_number'].'"  data-placement="top" title="Fill Biodata Form"><span class="badge light badge-warning"> Incomplete Record </span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($view['last_school'] != "" && $view['last_class'] != "" && $view['last_year'] != "" && $view['last_result'] != ""){
                                                        echo'<a href="./formfiller.php?form_academic='.$view['form_number'].'"  data-placement="top" title="Fill Academic History Form"><span class="badge light badge-success"> Completed </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?form_academic='.$view['form_number'].'"  data-placement="top" title="Fill Academic History Form"><span class="badge light badge-warning"> Incomplete Record </span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($view['busstop'] != "" && $view['street'] != "" && $view['area'] != "" && $view['lga'] != ""  && $view['state_of_res'] != ""){
                                                        echo'<a href="./formfiller.php?form_contact='.$view['form_number'].'"  data-placement="top" title="Fill Contact Details Form"><span class="badge light badge-success"> Completed </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?form_contact='.$view['form_number'].'"  data-placement="top" title="Fill Contact Details Form"><span class="badge light badge-warning"> Incomplete Record </span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($view['health_illness'] != "" && $view['health_vaccine'] != "" && $view['health_surgery'] != ""){
                                                        echo'<a href="./formfiller.php?form_health='.$view['form_number'].'"  data-placement="top" title="Fill Health Information Form"><span class="badge light badge-success"> Completed </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?form_health='.$view['form_number'].'"  data-placement="top" title="Fill Health Information Form"><span class="badge light badge-warning"> Incomplete Record </span></a>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if($view['attestation'] != "" ){
                                                        echo'<a href="./formfiller.php?form_attest='.$view['form_number'].'"  data-placement="top" title="Fill Attestation Form"><span class="badge light badge-success"> Completed </span></a>';
                                                    }else{
                                                        echo'<a href="./formfiller.php?form_attest='.$view['form_number'].'"  data-placement="top" title="Fill Attestation  Form"><span class="badge light badge-warning"> Incomplete Record </span></a>';
                                                    }
                                                ?>
                                            </td>

                                        </tr>
                                    <?php
                                    }
                                    } else{
                                        echo '<tr>No Form</tr>';
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