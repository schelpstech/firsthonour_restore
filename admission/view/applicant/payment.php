<?php
include '../../include/php/navbar.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <!-- Create New Form -->
        <div class="form-head d-flex flex-wrap mb-sm-4 mb-3 align-items-center">
            <div class="mr-auto  d-lg-block mb-3">
                <h2 class="text-black mb-0 font-w700">Registration Payment</h2>
            </div>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#addOrderModal" class="btn btn-primary btn-rounded mb-3"><i class="fa fa-user-plus mr-3"></i>New Admission Form</a>
            <!-- Add Order -->
            <div class="modal fade" id="addOrderModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Application Form</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="../../app/formhandler.php" method="POST" enctype="">
                                <div class="form-group">
                                    <label class="text-black font-w500">Surname</label>
                                    <input type="text" name="surname" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-black font-w500">Firstname</label>
                                    <input type="text" name="firstname" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-black font-w500">Othername</label>
                                    <input type="text" name="othername" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="text-black font-w500">Prospective Class</label>
                                    <select type="text" name="pros_class" class="form-control">
                                        <option value="">Select Class</option>
                                        <?php
                                        foreach ($class_list as $class_ref) {
                                        ?>
                                            <option value="<?php echo $class_ref['classid'] ?>"><?php echo $class_ref['classname'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="action" value="application_begin" class="btn btn-primary">Create Application Form</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                        <th scope="col"><strong>Amount Due</strong></th>
                                        <th scope="col"><strong>Amount Paid</strong></th>
                                        <th scope="col"><strong>Payment Date</strong></th>
                                        <th scope="col"><strong>Action</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($form_list)) {
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
                                                    <p><strong>
                                                            <?php
                                                            if ($view['classid'] <= 11) {
                                                                echo '&#8358;3000';
                                                            } else {
                                                                echo '&#8358;3000';
                                                            }
                                                            ?></strong></p>
                                                </td>
                                                <td>
                                                    <p><strong><?php echo $view['amount_paid'] ?? 'Not Yet Paid' ?></strong></p>
                                                </td>
                                                <td>
                                                    <p><strong><?php echo $view['payment_date'] ?? 'Not Yet Paid' ?></strong></p>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($view['amount_paid'] != "" && $view['payment_date'] != "" && $view['payment_verified'] == 11 ) {
                                                        echo '<a href="./reportviewer.php?payment=' . $view['form_number'] . '" data-placement="top" title="Generate Receipt"><span class="badge light badge-success"> Generate Receipt </span></a>';
                                                    } elseif ($view['amount_paid'] != "" && $view['payment_date'] != "" && $view['payment_verified'] == 00 ) {
                                                        echo '<a href="./formfiller.php?payment=' . $view['form_number'] . '" data-placement="top" title="Pending Verification"><span class="badge light badge-primary"> Pending Verification! Modify </span></a>';
                                                    } else {
                                                        echo '<a href="./formfiller.php?payment=' . $view['form_number'] . '"  data-placement="top" title="Continue to Payment Advice"><span class="badge light badge-warning"> Payment Advice</span></a>';
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo ' <td>No Registration Form  Found</td>';
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