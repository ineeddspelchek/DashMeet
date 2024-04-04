<?php
// By Henry Newton

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

        if (!isset($_SESSION["email"]) && $command != "login" && $command != "register" && $command != "createAccount")
            $command = "welcome";

        switch($command) {
            case "account":
                $this->account();
                break;
            case "import":
                $this->import();
                break;
            case "getJSON":
                $this->getJSON();
                break;
            case "viewEvents":
                $this->viewEvents();
                break;
            case "hostMain":
                $this->hostMain();
                break;
            case "memberMain":
                $this->memberMain();
                break;
            case "register":
                $this->register();
                break;
            case "login":
                $this->login();
                break;
            case "createAccount":
                $this->createAccount();
                break;
            case "changeProfile":
                $this->changeProfile();
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
                    $this->errorMessage = "Email is not associated with an account.";
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

    public function createAccount() {
        if(isset($_POST["email"]) && !empty($_POST["email"]) &&
        isset($_POST["password"]) && !empty($_POST["password"]) &&
        isset($_POST["fullname"]) && !empty($_POST["fullname"])
        ) {
            $res = $this->db->query("select * from users where email = $1;", $_POST["email"]);
            if (empty($res)) {
                $this->db->query("insert into users (email, fullname, password) values ($1, $2, $3);",
                    $_POST["email"], $_POST["fullname"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                $_SESSION["email"] = $_POST["email"];
                header("Location: ?command=account");
                return;
            } else {
                    $this->errorMessage = "An account is already created with this email.";
            }
        } else {
            $this->errorMessage = "Full name, email, and password are required.";
        }
        $this->register();
    }

    public function changeProfile() {
        $email = $_SESSION["email"];
        $password = $_SESSION["password"];
        $fullname = $_SESSION["fullname"];
        if(isset($_POST["password"]) && !empty($_POST["password"])) {
         $res = $this->db->query("select * from users where email = $1;", $email);
            $this->db->query("update users set password = $1 where email = $2;",
                password_hash($_POST["password"], PASSWORD_DEFAULT), $email);
        }
        if (isset($_POST["fullname"]) && !empty($_POST["fullname"])){
            $res = $this->db->query("select * from users where email = $1;", $email);
            $this->db->query("update users set fullname = $1 where email = $2;",
                             $_POST["fullname"], $email);
        }
        header("Location: ?command=account");
        return;
    }

    public function register() {
        $message = "";

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/register.php");
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
        include("/students/han5jn/students/han5jn/DashMeet/welcome.php");
    }

    public function account() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }

        if(isset($_POST["deleteCalId"])) {
            $res = $this->db->query("delete from calendars where id = $1 AND useremail = $2;", $_POST["deleteCalId"], $email);
        }

        include("/students/han5jn/students/han5jn/DashMeet/account.php");
    }

    public function import() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/import.php");
    }

    public function getJSON() {
        $message = "";
        $email = $_SESSION["email"];
        $id = $_POST["getID"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/getJSON.php");
    }
    public function viewEvents() {
        $message = "";
        $email = $_SESSION["email"];
        $id = $_POST["getID"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/viewEvents.php");
    }

    public function hostMain() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/hostMain.php");
    }

    public function memberMain() {
        $message = "";
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("/students/han5jn/students/han5jn/DashMeet/memberMain.php");
    }

}
