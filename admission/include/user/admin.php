<div class="row no-gutters">
    <div class="col-xl-12">
        <div class="auth-form">
            <div class="text-center mb-3">
                <img src="images/firsthonourschlogo.png" alt="">
            </div>
            <h4 class="text-center mb-4">Admin :: Sign in </h4>
            <form method="POST" action="../../app/manageuser.php">
                <div class="form-group">
                    <label class="mb-1"><strong>Username</strong></label>
                    <input type="text" class="form-control" name="username" placeholder="">
                </div>
                <div class="form-group">
                    <label class="mb-1"><strong>Password</strong></label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="text-center">
                    <button type="submit" name="adminloginform" class="btn btn-primary btn-block">Sign Me In</button>
                </div>
            </form>
            <div class="new-account mt-3">
                <p>Not an admin? <a class="text-primary" type="button"
                        onclick="load_signup_page();"> Sign up</a></p>
            </div>
        </div>
    </div>
</div>