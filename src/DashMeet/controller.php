<?php

class controller {
    private $db;

    private $errorMessage = "";

    public function __construct($input) {
        session_start();
        $this->db = new Database();

        $this->input = $input;
    }

    public function run() {
        $command = "welcome";
        if (isset($this->input["command"]))
            $command = $this->input["command"];

        if (!isset($_SESSION["email"]) && $command != "login")
            $command = "welcome";

        switch($command) {
            case "account":
                $this->account();
                break;
            case "hostMain":
                $this->hostMain();
                break;
            case "memberMain":
                $this->memberMain();
                break;
            case "login":
                $this->login();
                break;
            case "logout":
                $this->logout();
            default:
                $this->showWelcome();
                break;
        }
    }

    public function login() {
        // User must provide a non-empty name, email, and password to attempt a login
        if(isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["password"]) && !empty($_POST["password"])) {

                // Check if user is in database, by email
                $res = $this->db->query("select * from users where email = $1;", $_POST["email"]);
                if (empty($res)) {
                    // User was not there (empty result), so insert them
                    $this->db->query("insert into users (email, password) values ($1, $2);",
                        $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                    $_SESSION["email"] = $_POST["email"];
                    // Send user to the appropriate page (question)
                    header("Location: ?command=account");
                    return;
                } else {
                    // User was in the database, verify password is correct
                    // Note: Since we used a 1-way hash, we must use password_verify()
                    // to check that the passwords match.
                    if (password_verify($_POST["password"], $res[0]["password"])) {
                        // Password was correct, save their information to the
                        // session and send them to the question page
                        $_SESSION["email"] = $res[0]["email"];
                        header("Location: ?command=account");
                        return;
                    } else {
                        // Password was incorrect
                        $this->errorMessage = "Incorrect password.";
                    }
                }
        } else {
            $this->errorMessage = "Email, and password are required.";
        }
        $this->showWelcome();
    }


    public function logout() {
        session_destroy();
        session_start();
    }
    
    public function showWelcome() {
        $message = "";
        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/welcome.php");
    }

    public function account() {
        $message = "";
        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/account.php");
    }

    public function hostMain() {
        $message = "";
        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/hostMain.php");
    }

    public function memberMain() {
        $message = "";
        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/memberMain.php");
    }

}
