<div class="row no-gutters">
    <div class="col-xl-12">
        <div class="auth-form">
            <div class="text-center mb-3">
                <img src="images/firsthonourschlogo.png" alt="">
            </div>
            <h4 class="text-center mb-4">Create Applicant Account</h4>
            <form method="POST" action="../../app/manageuser.php">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="mb-1"><strong>Surname</strong></label>
                        <input type="text" class="form-control" autocomplete="new-password" required="yes"
                            placeholder=" Surname" name="surname">
                    </div>
                    <div class="col-sm-6 mt-2 mt-sm-0">
                        <label class="mb-1"><strong>First name</strong></label>
                        <input type="text" class="form-control" autocomplete="new-password" required="yes"
                            placeholder="First name" name="firstname">
                    </div>
                </div>

                <div class="form-group">
                    <label class="mb-1"><strong>Phone number</strong></label>
                    <input type="tel" autocomplete="new-password" name="phone" class="form-control" required="yes"
                        minlength="11" maxlength="11" placeholder="08012345678">
                </div>
                <div class="form-group">
                    <label class="mb-1"><strong>Password -<small> 6 characters</small></strong></label>
                    <input type="password" onchange="deleteconfirm()" autocomplete="new-password" name="password"
                        required="yes" id="password" class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                    <label class="mb-1"><strong> Confirm Password</strong></label>
                    <input type="password" id="confirm" autocomplete="new-password" required="yes" class="form-control"
                        placeholder="Password" onchange="checkpin()">
                </div>
                <div class="text-center mt-4">
                    <button type="submit" name="register" class="btn btn-primary btn-block">Create account</button>
                </div>
            </form>
            <div class="new-account mt-3">
                <p>Already have an account? <a class="text-primary" type="button" onclick="load_signin_page();">Sign
                        in</a></p>
            </div>
            <div class="new-account mt-3">
                <p>Log in as <a class="text-primary" type="button"
                        onclick="load_admin_page();">Admin</a></p>
            </div>
        </div>
    </div>
</div>