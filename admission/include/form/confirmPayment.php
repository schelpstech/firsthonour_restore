<div class="row">
    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <?php echo $form ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <h6><strong>Payment Advice : to make payment for admission registration form</strong></h6>
                    <li>1. You can pay cash at the school or make a transfer of the same amount to the school bank
                        account</li>
                    <li><strong>School Account Details </strong><br>
                        <strong>Bank : Wema Bank<br>
                            Account Name: First Honour Acacdemy<br>
                            Account Number : 0122521070</strong>
                    </li>
                    <li>2. If you are paying cash in school to collect a receipt or make a screenshot of the transaction
                        receipt if you transfered</li>
                    <li>3. Fill the form below with the payment details. </li>
                </div>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="../../app/formhandler.php" enctype='multipart/form-data'>
                        <h3>Form Registration Fee :
                            <?php
                            if ($form_view['classid'] <= 11) {
                                echo '&#8358;2000';
                            } else {
                                echo '&#8358;3000';
                            }
                            ?>
                        </h3>
                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Applicant Fullname:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled
                                    value="<?php echo $form_view['surname'] . ' ' . $form_view['firstname'] . ' ' . $form_view['othername'] ?>"
                                    name="fullname">
                            </div>
                        </div>
                        <div class="form-group row" style="display:none;">
                            <label class="col-sm-4 col-form-label">Formnumber</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes"
                                    value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Amount Paid</strong></label>
                            <div class="col-sm-8">
                                <input type="number" disabled class="form-control" required="yes"
                                    value="<?php echo $form_view['amount_paid'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Payment Date</strong></label>
                            <div class="col-sm-8">
                                <input type="date" disabled class="form-control" required="yes"
                                    value="<?php echo $form_view['payment_date'] ?>" name="payment_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">
                                <P><strong>Mode of Payment </strong></P>
                            </label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" required="yes" name="payment_mode">
                                    <option value="<?php echo $form_view['payment_mode'] ?? "" ?> "><?php echo $form_view['payment_mode'] ?? "SELECT " ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <iframe <?php if (!empty($form_view['payment_mode'])) {
                                echo 'src="../../storage/payment/' . $form_view['payment_receipt'] . '" title="Payment Receipt" height="300" width="600"';
                            } else {
                                echo 'src="../../storage/payment/noresult.png" title="Payment Receipt" height="300" width="300"';
                            }
                            ?>></iframe>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">
                                <P><strong>Payment Confirmation Status</strong></P>
                            </label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control" required="yes" name="payment_status">
                                    <option value="">Select</option>
                                    <option value="11">Successfull</option>
                                    <option value="0">Pending</option>
                                    <option value="null">Failed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="action" value="confirmPayment" class="btn btn-primary">Submit
                                    Payment Record</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>