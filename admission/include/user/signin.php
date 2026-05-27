<div class="row no-gutters">
    <div class="col-xl-12">
        <div class="auth-form">
            <div class="text-center mb-3">
                <img src="images/firsthonourschlogo.png" alt="">
            </div>

            <h4 class="text-center mb-4">Sign in your account</h4>
            <form method="POST" action="../../app/manageuser.php">
                <div class="form-group">
                    <label class="mb-1"><strong>Phone number</strong></label>
                    <input type="tel" class="form-control" name="userphone" placeholder="08012345678">
                </div>
                <div class="form-group">
                    <label class="mb-1"><strong>Password</strong></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-row d-flex justify-content-between mt-4 mb-2">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox ml-1">
                            <input type="checkbox" class="custom-control-input" id="basic_checkbox_1">
                            <label class="custom-control-label" for="basic_checkbox_1">Remember my preference</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <a type="button" onclick="load_recovery_page();">Forgot Password?</a>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="loginform" class="btn btn-primary btn-block">Sign Me In</button>
                </div>
            </form>
            <div class="new-account mt-3">
                <p>Don't have an account? <a class="text-primary" type="button"
                        onclick="load_signup_page();">Register</a></p>
            </div>
            <div class="new-account mt-3">
                <p> Log in as <a class="text-primary" type="button"
                        onclick="load_admin_page();">Admin</a></p>
            </div>
        </div>
    </div>
</div>