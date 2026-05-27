<?php
include '../../include/php/adminnav.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <!-- Create New Form -->
        <div class="form-head d-flex flex-wrap mb-sm-4 mb-3 align-items-center">
            <div class="mr-auto  d-lg-block mb-3">
                <h2 class="text-black mb-0 font-w700">Registration Payment</h2>
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
                                                    } elseif ($view['amount_paid'] != "" && $view['payment_date'] != "" && $view['payment_verified'] == 0 ) {
                                                        echo '<a href="./formfiller.php?confirmPayment=' . $view['form_number'] . '" data-placement="top" title="Pending Verification"><span class="badge light badge-primary"> Pending Verification! Modify </span></a>';
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