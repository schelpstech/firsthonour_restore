<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
<div class="row">
    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $form ?></h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="../../app/manageuser.php" enctype='multipart/form-data'>


                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Username </label>
                            <div class="col-sm-9">
                                <input type="text" disabled class="form-control" required="yes" value="<?php echo $parent_ref ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Surname</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $parent['user_surname'] ?? "" ?>" name="user_surname">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">First name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $parent['user_firstname'] ?? "" ?>" name="user_firstname">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" id="password" class="form-control" value="" name="user_pwd">
                            </div>
                            <div class="col-sm-3">
                                <button type="button" onclick="myFunction()" class="btn btn-default">Show Password </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Email address</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" value="<?php echo $parent['email'] ?? "" ?>" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Alternate Phone number</label>
                            <div class="col-sm-9">
                                <input type="tel" class="form-control" minlength="11" maxlength="11" value="<?php echo $parent['phone2'] ?? "" ?>" name="phone2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nearest Bus Stop</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $parent['busstop'] ?? "" ?>" name="busstop">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Street Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $parent['street'] ?? "" ?>" name="street">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Town / Area</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $parent['area']  ?? "" ?>" name="area">
                            </div>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Local Government Area</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" required="yes" value="<?php echo $parent['lga']  ?? "" ?>" name="lga">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">State of residence</label>
                    <div class="col-sm-9">
                        <select type="text" class="form-control" required="yes" name="state_of_res">
                            <option value="<?php echo  $parent['state_of_res'] ?? '';  ?>"><?php echo $parent['state_of_res'] ?? 'Select State of Residence';  ?></option>
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
                        <button name="action" value="profile_form" type="submit" class="btn btn-primary">Update Profile Information</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>