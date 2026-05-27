<div class="row">
    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $report ?></h4>
            </div>

            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="#" enctype='multipart/form-data'>
                        <img src="../../storage/passport/<?php echo $form_view['passport']?>" width="200" />
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Applicant Fullname:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['surname'] . ' ' . $form_view['firstname'] . ' ' . $form_view['othername'] ?>" name="fullname">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Formnumber</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Amount Paid</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['amount_paid'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Payment Date</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['payment_date'] ?>" name="payment_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Mode of Payment</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['payment_mode'] ?>" name="payment_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Transaction Receipt</strong></label>
                            
                            <iframe <?php if (!empty($form_view['payment_mode'])) {
                                        echo 'src="../../storage/payment/' . $form_view['payment_receipt'] . '" title="Payment Receipt" height="300" width="600"';
                                    } else {
                                        echo 'src="../../storage/payment/noresult.png" title="Payment Receipt" height="300" width="300"';
                                    }
                                    ?>></iframe>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="button" name="action" onclick="PrintElem('#myDiv')" class="btn btn-primary">Print Payment Receipt</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>