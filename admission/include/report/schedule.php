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
                            <label class="col-sm-4 col-form-label"><strong>Gender</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['gender'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Date of Birth</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['dateofbirth'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Previous Class</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['last_class'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Prospective Class</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['classname'] ?>" name="amount_paid">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Examination Date & Time</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['exam_date'] ?>" name="payment_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="button" name="action" onclick="PrintElem('#myDiv')" class="btn btn-primary">Print Examination Schedule</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>