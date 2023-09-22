<?php
namespace Form;

class User
{
    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * To check if the username already exists
     *
     * @param string $username
     * @return boolean
     */
    public function isUsernameExists($username)
    {
        $query = 'SELECT * FROM users where username = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $resultArray = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($resultArray)) {
            $count = count($resultArray);
        }
        if ($count > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * To check if the email already exists
     *
     * @param string $email
     * @return boolean
     */
    public function isEmailExists($email)
    {
        $query = 'SELECT * FROM users where email = ?';
        $paramType = 's';
        $paramValue = array(
            $email
        );
        $resultArray = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($resultArray)) {
            $count = count($resultArray);
        }
        if ($count > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * To register user
     *
     * @return string[]
     */
    public function registerUser()
    {
        $isUsernameExists = $this->isUsernameExists($_POST["username"]);
        $isEmailExists = $this->isEmailExists($_POST["email"]);
        if ($isUsernameExists) {
            $response = array(
                "status" => "error",
                "message" => "Username already exists."
            );
        } else if ($isEmailExists) {
            $response = array(
                "status" => "error",
                "message" => "Email already exists."
            );
        } else {
            $profile_upload = $this->uploadProfile($_FILES['profile-img']);
            if ($profile_upload['status'] == 'error') {
                return $profile_upload;
            }
            if (! empty($_POST["signup-password"])) {
                $hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);
            }
            $query = 'INSERT INTO users (username, password, email, profile) VALUES (?, ?, ?, ?)';
            $paramType = 'ssss';
            $paramValue = array(
                $_POST["username"],
                $hashedPassword,
                $_POST["email"],
                $profile_upload['path']
            );
            $memberId = $this->ds->insert($query, $paramType, $paramValue);
            if (! empty($memberId)) {
                $response = array(
                    "status" => "success",
                    "message" => "You have registered successfully."
                );
            }
        }
        return $response;
    }

    /**
     * to get user data
     *
     * @param string $username
     * @return string
     */
    public function getUser($username)
    {
        $query = 'SELECT * FROM users where username = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $memberRecord = $this->ds->select($query, $paramType, $paramValue);
        return $memberRecord;
    }

    /**
     * to login a user
     *
     * @return string
     */
    public function loginUser()
    {
        $memberRecord = $this->getUser($_POST["username"]);
        $loginPassword = 0;
        if (! empty($memberRecord)) {
            if (! empty($_POST["login-password"])) {
                $password = $_POST["login-password"];
            }
            $hashedPassword = $memberRecord[0]["password"];
            $loginPassword = 0;
            if (password_verify($password, $hashedPassword)) {
                $loginPassword = 1;
            }
        } else {
            $loginPassword = 0;
        }
        if ($loginPassword == 1) {
            session_start();
            $_SESSION["username"] = $memberRecord[0]["username"];
            $_SESSION["profile"] = $memberRecord[0]["profile"];
            session_write_close();
            $url = "./home.php";
            header("Location: $url");
        } else if ($loginPassword == 0) {
            $loginStatus = "Invalid username or password.";
            return $loginStatus;
        }
    }

    private function uploadProfile($image) {

        $target_dir = "vendor/img/profiles/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $extensions= array("jpeg","jpg","png");

        // Check if image file is a actual image or fake image
        $check = getimagesize($image["tmp_name"]);
        if($check !== false) {
            $err_msg[] = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $err_msg[] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($image["size"] > 500000) {
            $err_msg[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if(in_array($imageFileType,$extensions)=== false) {
            $err_msg[] = "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $response = array(
                "status" => "error",
                "message" => implode(" ; ", $err_msg),
                "path" => ""
            );
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $response = array(
                    "status" => "success",
                    "message" => "Success",
                    "path" => basename($image["name"])
                );
            } else {
                $response = array(
                    "status" => "error",
                    "message" => "Sorry, there was an error uploading your file.",
                    "path" => ""
                );
            }
        }

        return $response;
    }
}
