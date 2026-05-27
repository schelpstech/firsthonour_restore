<?php

class User
{
    // Refer to database connection
    private $db;

    // Instantiate object with database connection
    public function __construct($db_conn)
    {
        $this->db = $db_conn;
    }

    //Insert Users
    public function insert_data($table, $data)
    {
        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values  = '';
            $i = 0;


            $columnString = implode(',', array_keys($data));
            $valueString = ":" . implode(',:', array_keys($data));
            $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
            $query = $this->db->prepare($sql);
            foreach ($data as $key => $val) {
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            return $insert ? $this->db->lastInsertId() : false;
        } else {
            return false;
        }
    }

   

    
    // Log in registered users with either their username or email and their password
    public function login($user_email, $user_password, $user_ip, $log_status, $token)
    {
        try {
            // Define query to insert values into the users table
            $sql = "SELECT * FROM crunch_user WHERE  useremail=:user_email LIMIT 1";

            // Prepare the statement
            $query = $this->db->prepare($sql);

            // Bind parameters
            $query->bindParam(":user_email", $user_email);

            // Execute the query
            $query->execute();

            // Return row as an array indexed by both column name
            $returned_row = $query->fetch(PDO::FETCH_ASSOC);

            // Check if row is actually returned
            if ($query->rowCount() > 0) {
                // Verify hashed password against entered password
                if (password_verify($user_password, $returned_row['userpwd'])) {

                    $sqll = "SELECT * FROM user_log WHERE  userid =:user_id LIMIT 1";

                    // Prepare the statement
                    $query = $this->db->prepare($sqll);

                    // Bind parameters
                    $query->bindParam(":user_id", $user_email);

                    // Execute the query
                    $query->execute();

                    // Return row as an array indexed by both column name
                    $returned_row = $query->fetch(PDO::FETCH_ASSOC);

                    // Check if row is actually returned
                    if ($query->rowCount() > 0) {
                        $sqlll = "UPDATE user_log SET login_status = 0 WHERE  userid =:user_id";
                        // Prepare the statement
                        $query = $this->db->prepare($sqlll);

                        // Bind parameters
                        $query->bindParam(":user_id", $user_email);

                        // Execute the query
                        $query->execute();
                    }

                    $sql = " INSERT INTO `user_log` (user_ip, login_status, access_token, userid) VALUES(:user_ip, :user_status,  :user_token, :user_email )";

                    // Prepare the statement
                    $query = $this->db->prepare($sql);

                    // Bind parameters

                    $query->bindParam(":user_ip", $user_ip);
                    $query->bindParam(":user_status", $log_status);
                    $query->bindParam(":user_token", $token);
                    $query->bindParam(":user_email", $user_email);
                    // Execute the query
                    $query->execute();
                    $_SESSION['token'] = $token;
                    // Define session on successful login
                    $_SESSION['uniqueid'] = $user_email;
                    return true;
                } else {
                    // Define failure
                    return false;
                }
            }
        } catch (PDOException $e) {
            array_push($errors, $e->getMessage());
        }
    }

    // Check if the admin user is already logged in
    public function admin_is_logged_in()
    {
        // Check if user session has been set
        if (isset($_SESSION['admin_uniqueid']) && isset($_SESSION['admin_token'])) {
            
                $id = $_SESSION['admin_uniqueid'];
                $token = $_SESSION['admin_token'];
                $sql = "SELECT login_status from admin_log where userid =:user_email AND access_token =:token LIMIT 1";

                // Prepare the statement
                $query = $this->db->prepare($sql);
      
                // Bind parameters
                $query->bindParam(":user_email", $id);
                $query->bindParam(":token", $token);
      
                // Execute the query
                $query->execute();
      
                // Return row as an array indexed by both column name
                $returned_row = $query->fetch(PDO::FETCH_ASSOC);
                $current_status = $returned_row['login_status'];
                return $current_status; 
        }
    }
    // Log Out User
    public function log_out_user()
    {
        session_unset();
        session_destroy();
   }

    // Redirect user
    public function redirect($url)
    {
        header("Location: $url");
    }
  // Log in registered users with either their username or email and their password
  public function admin_login($user_email, $user_password, $user_ip, $log_status, $token)
  {
      try {
          // Define query to insert values into the users table
          $sql = "SELECT * FROM crunch_manager WHERE  mgr_email=:user_email LIMIT 1";

          // Prepare the statement
          $query = $this->db->prepare($sql);

          // Bind parameters
          $query->bindParam(":user_email", $user_email);

          // Execute the query
          $query->execute();

          // Return row as an array indexed by both column name
          $returned_row = $query->fetch(PDO::FETCH_ASSOC);

          // Check if row is actually returned
          if ($query->rowCount() > 0) {
              // Verify hashed password against entered password
              if (password_verify($user_password, $returned_row['mgr_pwd'])) {

                  $sqll = "SELECT * FROM admin_log WHERE  userid =:user_id LIMIT 1";

                  // Prepare the statement
                  $query = $this->db->prepare($sqll);

                  // Bind parameters
                  $query->bindParam(":user_id", $user_email);

                  // Execute the query
                  $query->execute();

                  // Return row as an array indexed by both column name
                  $returned_row = $query->fetch(PDO::FETCH_ASSOC);

                  // Check if row is actually returned
                  if ($query->rowCount() > 0) {
                      $sqlll = "UPDATE admin_log SET login_status = 0 WHERE  userid =:user_id";
                      // Prepare the statement
                      $query = $this->db->prepare($sqlll);

                      // Bind parameters
                      $query->bindParam(":user_id", $user_email);

                      // Execute the query
                      $query->execute();
                  }

                  $sql = " INSERT INTO `admin_log` (user_ip, login_status, access_token, userid) VALUES(:user_ip, :user_status,  :user_token, :user_email )";

                  // Prepare the statement
                  $query = $this->db->prepare($sql);

                  // Bind parameters

                  $query->bindParam(":user_ip", $user_ip);
                  $query->bindParam(":user_status", $log_status);
                  $query->bindParam(":user_token", $token);
                  $query->bindParam(":user_email", $user_email);
                  // Execute the query
                  $query->execute();
                  $_SESSION['admin_token'] = $token;
                  // Define session on successful login
                  $_SESSION['admin_uniqueid'] = $user_email;
                  return true;
              } else {
                  // Define failure
                  return false;
              }
          }
      } catch (PDOException $e) {
          array_push($errors, $e->getMessage());
      }
  }

  public function is_logged_in()
  {
      // Check if user session has been set
      if (isset($_SESSION['uniqueid']) && isset($_SESSION['token'])) {
          
              $id = $_SESSION['uniqueid'];
              $token = $_SESSION['token'];
              $sql = "SELECT login_status from user_log where userid =:user_email AND access_token =:token LIMIT 1";

              // Prepare the statement
              $query = $this->db->prepare($sql);
    
              // Bind parameters
              $query->bindParam(":user_email", $id);
              $query->bindParam(":token", $token);
    
              // Execute the query
              $query->execute();
    
              // Return row as an array indexed by both column name
              $returned_row = $query->fetch(PDO::FETCH_ASSOC);
              $current_status = $returned_row['login_status'];
              return $current_status; 
      }
  }

    
}
