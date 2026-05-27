<?php
require_once('../controller/start.inc.php');
require_once('../controller/utility.class.php');
$utility = new Utility();

if (isset($_POST['register'])) {
    // Retrieve form input
    $surname = trim($_POST['surname']);
    $firstname = trim($_POST['firstname']);
    $phone = trim($_POST['phone']);
    $password = htmlspecialchars($_POST['password']);
    $user_ip =  $_SERVER['REMOTE_ADDR'];
    $token =   $utility->generateRandomString(16);
    $errormsg = '';
    // Check for empty and invalid inputs
    if (empty($surname)) {
        $errormsg .= ' Your surname is required <br/>';
    }
    if (empty($firstname)) {
        $errormsg .= ' Your Firstname is required <br/>';
    }
    if (empty($phone) || strlen($phone) != 11) {
        $errormsg .= ' A valid phonenumber is required. Phone number must be 11 characters. <br/>';
    }
    if (empty($password)) {
        $errormsg .= ' Password is required <br/>';
    }
    if (empty($errormsg)) {
        // Check if Phone number Exist in Database
        $tblName = 'tbl_users';
        $conditions = array(
            'return_type' => 'count',
            'where' => array(
                'user_phone' => $phone,
            )
        );
        $verify = $model->getRows($tblName, $conditions);
        // Check for usernames or e-mail addresses that have already been used
        if (empty($verify)) {
            // Hash password
            $user_hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Create new applicant account
            $userdata = array(

                'user_surname' => $surname,
                'user_firstname' => $firstname,
                'user_phone' => $phone,
                'user_passphrase' => $user_hashed_password,
            );
            $insert = $model->insert_data($tblName, $userdata);

            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success!</strong> Welcome ' . $surname . ' ' . $firstname . ' ! We have created your applicant account. Begin application  
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
                </div>
                ';
            $user->redirect('../view/applicant/home.php');
            $_SESSION['msg'] = $response;
            $_SESSION['uniqueid'] = $phone;
        } else {
            $response = '
               <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					<strong>Error!</strong> We found an existing account linked to this Phonenumber. You can reset your password 
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
              ';
            $user->redirect($_SERVER['HTTP_REFERER']);
            $_SESSION['msg'] = $response;
        }
    } else {
        $response = '
                <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <strong>Ooops!</strong>  We noticed some error in your submission <br/> ' . trim($errormsg) . '
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
                ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
} elseif (isset($_POST['loginform'])) {
    $userphone = trim($_POST['userphone']);
    $password = htmlspecialchars($_POST['password']);
    $user_ip =  $_SERVER['REMOTE_ADDR'];
    $token =   $utility->generateRandomString(16);
    $errormsg = '';
    // Check for empty and invalid inputs
    if (empty($userphone)) {
        $errormsg .= ' Your phone number is required <br/>';
    }
    if (empty($password)) {
        $errormsg .= ' Password is required <br/>';
    }
    if (empty($errormsg)) {
        // Check if Phone number Exist in Database
        $tblName = 'tbl_users';
        $conditions = array(
            'return_type' => 'count',
            'where' => array(
                'user_phone' => $userphone,
            )

        );
        $verify = $model->getRows($tblName, $conditions);
        // Check if username exist
        if ($verify == 1) {
            // Hash password

            // Check applicant account
            $conditions = array(
                'return_type' => 'single',
                'where' => array(
                    'user_phone' => $userphone,
                )

            );
            $returned_row = $model->getRows($tblName, $conditions);
            if ((password_verify($password, $returned_row['user_passphrase'])) && ($userphone == $returned_row['user_phone'])) {
                $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success!</strong> Welcome ' . $returned_row['user_surname'] . ' ' . $returned_row['user_firstname'] . ' to First Honour Schools Central Admission Portal!   
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
                </div>
                ';
                $user->redirect('../view/applicant/home.php');
                $_SESSION['msg'] = $response;
                $_SESSION['uniqueid'] = $userphone;
            } else {
                $response = '
                   <div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>Error!</strong> Incorrect Login credentials. Try Again!
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                  ';
                $user->redirect($_SERVER['HTTP_REFERER']);
                $_SESSION['msg'] = $response;
            }
        } else {
            $response = '
               <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					<strong>Error!</strong> No user account linked to this phonenumber
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
              ';
            $user->redirect($_SERVER['HTTP_REFERER']);
            $_SESSION['msg'] = $response;
        }
    } else {
        $response = '
                <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <strong>Ooops!</strong>  We noticed some error in your log in details :  <br/> ' . trim($errormsg) . '
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
                ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
} elseif (isset($_GET['userid'])) {
    unset($_SESSION['uniqueid']);
    session_destroy();
    $user->redirect('../view/applicant/index.php');
} elseif (isset($_POST['action']) && ($_POST['action'] == 'profile_form')) {
    $user_surname = trim($_POST['user_surname']);
    $user_firstname = trim($_POST['user_firstname']);
    $user_pwd = trim($_POST['user_pwd']);
    $phone2 = trim($_POST['phone2']);
    $email = trim($_POST['email']);
    $busstop = trim($_POST['busstop']);
    $street = trim($_POST['street']);
    $area = trim($_POST['area']);
    $lga = trim($_POST['lga']);
    $state_of_res = trim($_POST['state_of_res']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';

    if (!empty($user_pwd)) {
        $user_pwd = password_hash($user_pwd, PASSWORD_DEFAULT);
    } else {
        $tblName = 'tbl_users';
        $conditions = array(
            'return_type' => 'single',
            'where' => array(
                'user_phone' => $_SESSION['uniqueid'],
            )
        );
        $verify = $model->getRows($tblName, $conditions);
        $user_pwd = $verify['user_passphrase'];
    }
    if (!empty($phone2) && strlen($phone2) != 11) {
        $errormsg .= ' Phone number must be 11 digits <br/>';
    }
    if (empty($user_surname)) {
        $errormsg .= 'Parent Surname   is required <br/>';
    }
    if (empty($user_firstname)) {
        $errormsg .= ' Parent First Name  is required <br/>';
    }
    if (empty($busstop)) {
        $errormsg .= ' Nearest Bust Stop  is required <br/>';
    }
    if (empty($street)) {
        $errormsg .= ' Street Name  is  required <br/>';
    }
    if (empty($area)) {
        $errormsg .= ' Area / Town is required <br/>';
    }
    if (empty($lga)) {
        $errormsg .= ' LGA is required <br/>';
    }
    if (empty($state_of_res)) {
        $errormsg .= ' State of residence is required <br/>';
    }

    if ($errormsg == '') {
        $tblName = 'tbl_users';
        $conditions = array(
            'user_phone' =>  $_SESSION['uniqueid'],
        );
        $parameters = array(
            'user_surname' => $user_surname,
            'user_firstname' => $user_firstname,
            'user_passphrase' => $user_pwd,
            'phone2' => $phone2,
            'email' => $email,
            'busstop' => $busstop,
            'street' => $street,
            'area' => $area,
            'lga' => $lga,
            'state_of_res' => $state_of_res,
        );
        $updateform = $model->upDate($tblName, $parameters, $conditions);
        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Your Profile has been successfully updated. 
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
                </div>
                ';
            $user->redirect($_SERVER['HTTP_REFERER']);
            $_SESSION['msg'] = $response;
        } else {
            $response = '
                   <div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>Error!</strong> There has been an error updating your profile
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                  ';
            $user->redirect($_SERVER['HTTP_REFERER']);
            $_SESSION['msg'] = $response;
        }
    } else {
        $response =  '
                    <div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>Ooops!</strong>  We noticed some error in your submission <br/> ' . trim($errormsg) . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                    ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
} elseif (isset($_POST['adminloginform'])) {
    $username = trim($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $user_ip =  $_SERVER['REMOTE_ADDR'];
    $token =   $utility->generateRandomString(16);
    $errormsg = '';
    // Check for empty and invalid inputs
    if (empty($username)) {
        $errormsg .= ' Your username is required <br/>';
    }
    if (empty($password)) {
        $errormsg .= ' Password is required <br/>';
    }
    if (empty($errormsg)) {
        // Check if Phone number Exist in Database
        $tblName = '123admin';
        $conditions = array(
            'return_type' => 'count',
            'where' => array(
                'dname' => $username,
            )

        );
        $verify = $model->getRows($tblName, $conditions);
        // Check if username exist
        if ($verify == 1) {
            // Hash password

            // Check applicant account
            $conditions = array(
                'return_type' => 'single',
                'where' => array(
                    'dname' => $username,
                )

            );
            $returned_row = $model->getRows($tblName, $conditions);
            if (($password ===  $returned_row['dpwd']) && ($username == $returned_row['dname'])) {
                $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success!</strong> Welcome ' . $returned_row['dname'] . ' to First Honour Schools Central Admission Portal!   
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
                </div>
                ';
                $user->redirect('../view/manager/home.php');
                $_SESSION['msg'] = $response;
                $_SESSION['uniqueid'] = $username;
            } else {
                $response = '
                   <div class="alert alert-danger alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        <strong>Error!</strong> Incorrect Login credentials. Try Again!
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                  ';
                $user->redirect($_SERVER['HTTP_REFERER']);
                $_SESSION['msg'] = $response;
            }
        } else {
            $response = '
               <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
					<strong>Error!</strong> No user account linked to this username
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
              ';
            $user->redirect($_SERVER['HTTP_REFERER']);
            $_SESSION['msg'] = $response;
        }
    } else {
        $response = '
                <div class="alert alert-danger alert-dismissible fade show">
					<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <strong>Ooops!</strong>  We noticed some error in your log in details :  <br/> ' . trim($errormsg) . '
					<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                    </button>
				</div>
                ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
}
