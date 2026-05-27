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
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Phone number </label>
                            <div class="col-sm-9">
                                <input type="text" disabled class="form-control" required="yes" value="<?php echo $parent_ref ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" value="<?php echo $form_view['email'] ?? "" ?>" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Alternate Phone number</label>
                            <div class="col-sm-9">
                                <input type="tel" class="form-control" minlength="11" maxlength="11" value="<?php echo $form_view['phone2'] ?? "" ?>" name="phone2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nearest Bus Stop</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['busstop'] ?? "" ?>" name="busstop">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Street Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['street'] ?? "" ?>" name="street">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Town / Area</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['area']  ?? "" ?>" name="area">
                            </div>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Local Government Area</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" required="yes" value="<?php echo $form_view['lga']  ?? "" ?>" name="lga">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">State of residence</label>
                    <div class="col-sm-9">
                        <select type="text" class="form-control" required="yes" name="state_of_res">
                            <option value="<?php echo  $form_view['state_of_res'] ?? '';  ?>"><?php echo $form_view['state_of_res'] ?? 'Select State of Residence';  ?></option>
                            <?php
                            foreach ($location as $location) {
                            ?>
                                <option value="<?php echo $location['state'] ?>"><?php echo $location['state'] ?></option>
                            <?php
                            }
                            ?>
                            <option value="Other">Other</option>

                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-10">
                        <button name="action" value="contact_form" type="submit" class="btn btn-primary">Update Contact Information</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>