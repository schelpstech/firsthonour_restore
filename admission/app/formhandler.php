<?php
require_once('../controller/start.inc.php');
require_once('../controller/utility.class.php');
$utility = new Utility();



if (isset($_POST['action']) && ($_POST['action'] == 'application_begin')) {
    // Retrieve form input
    $surname = trim($_POST['surname']);
    $firstname = trim($_POST['firstname']);
    $othername = trim($_POST['othername']);
    $pros_class = trim($_POST['pros_class']);
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $token = 'FORM-' . strtoupper($utility->generateRandomString(6));
    $parent_ref = $_SESSION['uniqueid'];
    $errormsg = '';
    // Check for empty and invalid inputs
    if (empty($surname)) {
        $errormsg .= ' Your surname is required <br/>';
    }
    if (empty($firstname)) {
        $errormsg .= ' Your Firstname is required <br/>';
    }
    if (empty($othername)) {
        $errormsg .= ' Othername is required <br/>';
    }
    if (empty($pros_class)) {
        $errormsg .= ' Prospective Class is required. Select a Class <br/>';
    }
    if (empty($errormsg)) {
        // Check if learner has a form in Database
        $tblName = 'application_tbl';
        $conditions = array(
            'return_type' => 'count',
            'where' => array(
                'firstname' => $firstname,
                'othername' => $othername,
                'surname' => $surname,
                'classid' => $pros_class,
                'parent_ref' => $parent_ref,
            )

        );
        $verify = $model->getRows($tblName, $conditions);
        if (empty($verify)) {

            // Create new application form
            $formdata = array(
                'firstname' => $firstname,
                'othername' => $othername,
                'surname' => $surname,
                'classid' => $pros_class,
                'parent_ref' => $parent_ref,
                'form_number' => $token,
            );
            $insert = $model->insert_data($tblName, $formdata);

            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success!</strong> A new application form has been created for ' . $surname . ' ' . $firstname . ' ! With Reference number : ' . $token . ' 
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
					<strong>Error!</strong> We found an existing application for the learner linked to this Phonenumber. You can continue filling the existing form
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
} elseif (isset($_POST['action']) && ($_POST['action'] == 'bio_data_form')) {
    $gender = trim($_POST['gender']);
    $stateoforigin = trim($_POST['stateoforigin']);
    $dateofbirth = trim($_POST['dateofbirth']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    if (empty($gender)) {
        $errormsg .= ' Gender is required <br/>';
    }
    if (empty($stateoforigin)) {
        $errormsg .= ' State of Origin is required <br/>';
    }
    if (empty($dateofbirth)) {
        $errormsg .= ' Date of Birth is required <br/>';
    }
    if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
        //passportdata
        $fileTmpPath = $_FILES['passport']['tmp_name'];
        $fileName = $_FILES['passport']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        //Check file extension
        $allowedfileExtensions = array('png', 'jpeg', 'jpg');
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errormsg .= ' Invalid File format. Only JPG, JPEG and PNG files allowed. <br/>';
        }
        $passport = $form_number . "." . $fileExtension;
        // directory in which the uploaded file will be moved
        $dir = '../storage/passport/';
        $dest_path = $dir . $passport;
        if ($errormsg == '') {

            if (move_uploaded_file($fileTmpPath, $dest_path)) {

                $tblName = 'application_tbl';
                $conditions = array(
                    'form_number' => $form_number,
                );
                $parameters = array(
                    'passport' => $passport,
                    'gender' => $gender,
                    'dateofbirth' => $dateofbirth,
                    'state_of_origin' => $stateoforigin,
                );
                $updateform = $model->upDate($tblName, $parameters, $conditions);
                if ($updateform) {
                    $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant information' . $passport . '
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
                    <strong>Ooops!</strong>  There has been a glitch on the server. Kindly try at a later time.
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
    } else {
        if ($errormsg == '') {

            $tblName = 'application_tbl';
            $conditions = array(
                'form_number' => $form_number,
            );
            $parameters = array(
                'gender' => $gender,
                'dateofbirth' => $dateofbirth,
                'state_of_origin' => $stateoforigin,
            );
            $updateform = $model->upDate($tblName, $parameters, $conditions);
            if ($updateform) {
                $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant information
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
    }
} elseif (isset($_POST['action']) && ($_POST['action'] == 'academic_data')) {
    $last_school = trim($_POST['last_school']);
    $last_class = trim($_POST['last_class']);
    $last_year = trim($_POST['last_year']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    if (empty($last_school)) {
        $errormsg .= ' Name of last school attended is required <br/>';
    }
    if (empty($last_class)) {
        $errormsg .= ' Last class attended is  required <br/>';
    }
    if (empty($last_year)) {
        $errormsg .= ' Year last class was completed is required <br/>';
    }
    if (isset($_FILES['last_result']) && $_FILES['last_result']['error'] === UPLOAD_ERR_OK ) {
        //passportdata
        $fileTmpPath = $_FILES['last_result']['tmp_name'];
        $fileName = $_FILES['last_result']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        //Check file extension
        $allowedfileExtensions = array('png', 'jpeg', 'jpg', 'pdf');
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errormsg .= ' Invalid File format. Only PDF, JPG, JPEG and PNG files allowed. <br/>';
        }
        $last_result = $form_number . "." . $fileExtension;
        // directory in which the uploaded file will be moved
        $dir = '../storage/credential/';
        $dest_path = $dir . $last_result;
        if ($errormsg == '') {

            if (move_uploaded_file($fileTmpPath, $dest_path)) {

                $tblName = 'application_tbl';
                $conditions = array(
                    'form_number' => $form_number,
                );
                $parameters = array(
                    'last_result' => $last_result,
                    'last_class' => $last_class,
                    'last_year' => $last_year,
                    'last_school' => $last_school,
                );
                $updateform = $model->upDate($tblName, $parameters, $conditions);
                if ($updateform) {
                    $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Academic Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant academic information' . $passport . '
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
                    <strong>Ooops!</strong>  There has been a glitch on the server. Kindly try at a later time.
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
    } else {
        if ($errormsg == '') {

            $tblName = 'application_tbl';
            $conditions = array(
                'form_number' => $form_number,
            );
            $parameters = array(
                'last_class' => $last_class,
                'last_year' => $last_year,
                'last_school' => $last_school,
            );
            $updateform = $model->upDate($tblName, $parameters, $conditions);
            if ($updateform) {
                $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Academic Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant academic information
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
    }
} elseif (isset($_POST['action']) && ($_POST['action'] == 'contact_form')) {
    $phone2 = trim($_POST['phone2']);
    $email = trim($_POST['email']);
    $busstop = trim($_POST['busstop']);
    $street = trim($_POST['street']);
    $area = trim($_POST['area']);
    $lga = trim($_POST['lga']);
    $state_of_res = trim($_POST['state_of_res']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';

    if (!empty($phone2) && strlen($phone2) != 11) {
        $errormsg .= ' Phone number must be 11 digits <br/>';
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
            'user_phone' => $_SESSION['uniqueid'],
        );
        $parameters = array(
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
                    <strong>Success! </strong>Contact Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                        <strong>Error!</strong> There has been an error updating applicant contact information
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
} elseif (isset($_POST['action']) && ($_POST['action'] == 'health_data')) {
    $surgery = $_POST['surgery'];
    $illness = (!empty($_POST['illness'])) ? implode($_POST['illness']) : "";
    $vaccine = (!empty($_POST['vaccine'])) ? implode($_POST['vaccine']) : "";
    $form_number = $_POST['form_number'];
    $errormsg = '';

    if (empty($surgery)) {
        $errormsg .= ' Nearest Bust Stop  is required <br/>';
    }

    if ($errormsg == '') {
        $tblName = 'application_tbl';
        $conditions = array(
            'form_number' => $form_number,
        );
        $parameters = array(
            'health_surgery' => $surgery,
            'health_illness' => $illness,
            'health_vaccine' => $vaccine,
        );
        $updateform = $model->upDate($tblName, $parameters, $conditions);
        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Health Information for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                        <strong>Error!</strong> There has been an error updating applicant health information
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
} elseif (isset($_POST['action']) && ($_POST['action'] == 'attestation')) {
    $parent = implode($_POST['parent']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';

    if (strlen($parent) != 8) {
        $errormsg .= ' All conditions must be agreed with <br/>';
    }

    if ($errormsg == '') {
        $tblName = 'application_tbl';
        $conditions = array(
            'form_number' => $form_number,
        );
        $parameters = array(
            'attestation' => 'Yes',
        );
        $updateform = $model->upDate($tblName, $parameters, $conditions);
        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Attestation records for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                        <strong>Error!</strong> There has been an error updating applicant contact information
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
                        <strong>Ooops!</strong>  We noticed some error in your submission <br/> ' . trim($errormsg) . $parent . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                    ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
} elseif (isset($_POST['action']) && ($_POST['action'] == 'payment_data')) {
    $amount_paid = trim($_POST['amount_paid']);
    $payment_date = trim($_POST['payment_date']);
    $payment_mode = trim($_POST['payment_mode']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';
    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    if (empty($amount_paid)) {
        $errormsg .= ' Amount Paid is required <br/>';
    }
    if (empty($payment_date)) {
        $errormsg .= ' Payment Date is  required <br/>';
    }
    if (empty($payment_mode)) {
        $errormsg .= ' Payment Mode is required <br/>';
    }
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK ) {
        //passportdata
        $fileTmpPath = $_FILES['payment_receipt']['tmp_name'];
        $fileName = $_FILES['payment_receipt']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        //Check file extension
        $allowedfileExtensions = array('png', 'jpeg', 'jpg');
        if (!in_array($fileExtension, $allowedfileExtensions)) {
            $errormsg .= ' Invalid File format. Only JPG, JPEG and PNG files allowed. <br/>';
        }
        $payment_receipt = $form_number . "." . $fileExtension;
        // directory in which the uploaded file will be moved
        $dir = '../storage/payment/';
        $dest_path = $dir . $payment_receipt;
        if ($errormsg == '') {

            if (move_uploaded_file($fileTmpPath, $dest_path)) {

                $tblName = 'application_tbl';
                $conditions = array(
                    'form_number' => $form_number,
                );
                $parameters = array(
                    'amount_paid' => $amount_paid,
                    'payment_date' => $payment_date,
                    'payment_mode' => $payment_mode,
                    'payment_receipt' => $payment_receipt,
                    'payment_verified' => 0,
                );
                $updateform = $model->upDate($tblName, $parameters, $conditions);
                if ($updateform) {
                    $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Registration Fee Payment for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant Registration Fee Payment
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
                    <strong>Ooops!</strong>  There has been a glitch on the server. Kindly try at a later time.
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
    } else {
        if ($errormsg == '') {

            $tblName = 'application_tbl';
            $conditions = array(
                'form_number' => $form_number,
            );
            $parameters = array(
                'amount_paid' => $amount_paid,
                'payment_date' => $payment_date,
                'payment_mode' => $payment_mode,
            );
            $updateform = $model->upDate($tblName, $parameters, $conditions);
            if ($updateform) {
                $response = '
                    <div class="alert alert-success alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                        <strong>Success! </strong>Registration Fee Payment for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                            <strong>Error!</strong> There has been an error updating applicant Registration Fee Payment 
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
    }
} elseif (isset($_POST['action']) && ($_POST['action'] == 'schedule_data')) {
    $schedule = trim($_POST['schedule']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';

    if (strlen($schedule) == "") {
        $errormsg .= ' You must schedule date and time for exam <br/>';
    }

    if ($errormsg == '') {
        $tblName = 'application_tbl';
        $conditions = array(
            'form_number' => $form_number,
        );
        $parameters = array(
            'exam_date' => $schedule,
        );
        $updateform = $model->upDate($tblName, $parameters, $conditions);
        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Entrance Exam has been scheduled for Applicant with Reference number :: ' . $form_number . '  successfully. 
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
                        <strong>Error!</strong> There has been an error scheduling applicant entrance exam
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
                        <strong>Ooops!</strong>  We noticed some error in your submission <br/> ' . trim($errormsg) . $parent . '
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                    ';
        $user->redirect($_SERVER['HTTP_REFERER']);
        $_SESSION['msg'] = $response;
    }
}elseif (isset($_POST['action']) && ($_POST['action'] == 'confirmPayment')){
    $payment_status = trim($_POST['payment_status']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';
    
    if (empty($form_number)) {
        $errormsg .= ' Form Number is  required <br/>';
    }
    if (empty($payment_status)) {
        $errormsg .= ' Payment Status is required <br/>';
    }
    if ($errormsg == '') {
        $tblName = 'application_tbl';
        $conditions = array(
            'form_number' => $form_number,
        );
        $parameters = [
            'payment_verified' => $payment_status,
        ];
        $updateform = $model->upDate($tblName, $parameters, $conditions);

        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Registration Fee Payment Status for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                        <strong>Error!</strong> There has been an error updating applicant Registration Fee Payment Status
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
}elseif (isset($_POST['action']) && ($_POST['action'] == 'updateExamScore')){
    $examScore = trim($_POST['examScore']);
    $form_number = trim($_POST['form_number']);
    $errormsg = '';
    
    if (empty($form_number)) {
        $errormsg .= ' Form Number is  required <br/>';
    }
    if (empty($examScore)) {
        $errormsg .= ' Exam Score is required <br/>';
    }
    if ($errormsg == '') {
        $tblName = 'application_tbl';
        $conditions = array(
            'form_number' => $form_number,
        );
        $parameters = [
            'exam_score' => $examScore,
        ];
        $updateform = $model->upDate($tblName, $parameters, $conditions);

        if ($updateform) {
            $response = '
                <div class="alert alert-success alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>	
                    <strong>Success! </strong>Entrance Examination Score for Applicant with Reference number :: ' . $form_number . ' has been successfully updated. 
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
                        <strong>Error!</strong> There has been an error updating applicant Entrance Examination Score
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
}