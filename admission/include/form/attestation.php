<div class="row">

    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $form ?></h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="../../app/formhandler.php" enctype='multipart/form-data'>
                        <div class="form-group row" style="display:none;">
                            <label class="col-sm-3 col-form-label">Formnumber</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>
                        <h4 class="col-sm-12 col-form-label"><strong> As the Parent/Guardian of the applicant, I will: </strong> </h4>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_1" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_1">See that my child goes to school regularly, on time, properly dressed and adequately equipped.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_2" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_2">Make the school aware of any concerns or problems that might affect my child’s behavior.CHIKEN POX</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_3" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_3">Support my child with his/her homework and learning opportunities and ensure my child completes his/her homework on time.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_4" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_4">Attend PTA meetings and discuss my child’s progress.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_5" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_5">Support my child in responding positively to the general expectations and regulations of the school.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_6" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_6">Pay school fees and other due without delay, at least 50% of my child school fee before a new term begins and pay 100% of transportation fee where applicable before the new term begins for adequate provision for child.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_7" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_7">Inform the school of any illness of my child. </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_8" name="parent[]" required="yes" value="a" <?php
                                                                                                                                                    if ($form_view['attestation'] == "Yes") {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    }
                                                                                                                                                    ?>>
                                <label class="custom-control-label" for="basic_checkbox_8">Support disciplinary actions taken against my child/wards when necessary.</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="action" value="attestation" class="btn btn-primary">Submit Attestation Form</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>