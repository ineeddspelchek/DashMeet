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
        if(isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["password"]) && !empty($_POST["password"])) {
                $res = $this->db->query("select * from users where email = $1;", $_POST["email"]);
                if (empty($res)) {
                    $this->db->query("insert into users (email, password) values ($1, $2);",
                        $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                    $_SESSION["email"] = $_POST["email"];
                    header("Location: ?command=account");
                    return;
                } else {
                    if (password_verify($_POST["password"], $res[0]["password"])) {
                        $_SESSION["email"] = $res[0]["email"];
                        header("Location: ?command=account");
                        return;
                    } else {
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
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/account.php");
    }

    public function hostMain() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/hostMain.php");
    }

    public function memberMain() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/opt/src/DashMeet/memberMain.php");
    }

}
