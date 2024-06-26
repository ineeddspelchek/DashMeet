<?php
// By Henry Newton
// Sources:
// https://stackoverflow.com/questions/2944297/postgresql-function-for-last-inserted-id
// https://stackoverflow.com/questions/4142809/simple-php-post-redirect-get-code-example
// https://www.phptutorial.net/php-tutorial/php-prg/
// https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-a-php-string/

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
                if(isset($_SESSION["email"])) {
                    $this->account();
                    break;
                }
                else {
                    $this->showWelcome();
                    break;
                }
        }
    }

    public function login() {
        if(isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["password"]) && !empty($_POST["password"])) {
                $res = $this->db->query("select * from ourUsers where email = $1;", $_POST["email"]);
                if (empty($res)) {
                    $this->errorMessage = "Email is not associated with an account.";
                } else {
                    if (password_verify($_POST["password"], $res[0]["password"])) {
                        $_SESSION["email"] = $res[0]["email"];
                        
                        $res = $this->db->query("select * from ourUsers where email = $1;",$_POST["email"]);
                        $_SESSION["userID"] = intval($res[0]["id"]);
                        $_SESSION["name"] = $res[0]["fullname"];

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
            $res = $this->db->query("select * from ourUsers where email = $1;", $_POST["email"]);
            if (empty($res)) {
                $this->db->query("insert into ourUsers (email, fullname, password) values ($1, $2, $3);",
                    $_POST["email"], $_POST["fullname"], password_hash($_POST["password"], PASSWORD_DEFAULT));
                
                $res = $this->db->query("select id from ourUsers where email = $1;",$_POST["email"]);
                $_SESSION["userID"] = intval($res[0]["id"]);
                $_SESSION["email"] = $_POST["email"];
                $_SESSION["name"] = $_POST["fullname"];
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
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];
        $password = $_SESSION["password"];
        $fullname = $_SESSION["fullname"];
        if(isset($_POST["password"]) && !empty($_POST["password"])) {
         $res = $this->db->query("select * from ourUsers where email = $1;", $email);
            $this->db->query("update ourUsers set password = $1 where email = $2;",
                password_hash($_POST["password"], PASSWORD_DEFAULT), $email);
        }
        if (isset($_POST["fullname"]) && !empty($_POST["fullname"])){
            $res = $this->db->query("select * from ourUsers where email = $1;", $email);
            $this->db->query("update ourUsers set fullname = $1 where email = $2;",
                             $_POST["fullname"], $email);
            $_SESSION["name"] = $_POST["fullname"];
        }
        header("Location: ?command=account");
        return;
    }

    public function register() {
        $message = "";

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/register.php");
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

        if(isset($_GET["joinID"])) {
            $decodedMeetingID = openssl_decrypt($_GET["joinID"], "AES-128-CTR", "hopefullySecure", $options = 0, $iv = '3262560861013191');
            $decodedMeetingID = substr($decodedMeetingID, 0, strlen($decodedMeetingID)/20);
            $_SESSION["joinID"] = intval($decodedMeetingID);
        }

        include("./opt/src/DashMeet/welcome.php");
    }

    public function account() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }

        if(isset($_POST["deleteCalId"])) {
            $res = $this->db->query("delete from calendars where id = $1 AND userID = $2;", $_POST["deleteCalId"], $userID);
        }

        if(isset($_POST["delMeetingID"])) {
            $res = $this->db->query("delete from meetings where id = $1 AND hostID = $2;", $_POST["delMeetingID"], $userID);
        }

        
        if(isset($_POST["accept"]) and isset($_SESSION["joinID"])) {
            $res = $this->db->query("insert into membersOf (meetingID, memberID) values ($1, $2);",
                    $_SESSION["joinID"], $userID);
            unset($_SESSION["joinID"]);
            unset($_SESSION["meeting"]);
        }
        else if(isset($_POST["decline"])) {
            unset($_SESSION["joinID"]);
            unset($_SESSION["meeting"]);
        }
        else if(isset($_SESSION["joinID"])) {
            $joinID = $_SESSION["joinID"];
            $res = $this->db->query("select * from meetings join ourUsers on meetings.hostID=ourUsers.id where meetings.id=$1;", $joinID);
            if(isset($res[0])) {
                $_SESSION["meeting"] = $res[0];
            }
            else {
                unset($joinID);
            }
        }

        include("./opt/src/DashMeet/account.php");
    }

    public function import() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/import.php");
    }

    public function getJSON() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];
        $id = $_POST["getID"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/getJSON.php");
    }
    
    public function viewEvents() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];
        $id = $_POST["getID"];

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/viewEvents.php");
    }

    public function hostMain() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];
        $name = $_SESSION["name"];

        # creating new meeting
        if(isset($_POST["meetingName"]) && isset($_SESSION["makingNew"])) { 
            $res = $this->db->query("insert into meetings (name, hostID, start, stop) values ($1, $2, $3, $4) returning *;",
                                    $_POST["meetingName"], $userID, $_POST["meetingStart"], $_POST["meetingStop"]);
            unset($_POST["meetingName"]);
            $meetingID = intval($res[0]["id"]);
            $_SESSION["meetingID"] = $meetingID;
 
            $encodedMeetingID = urlencode(openssl_encrypt(str_repeat(strval($meetingID), 20), "AES-128-CTR", "hopefullySecure", 0, '3262560861013191'));
            $encodedMeetingID = str_replace("%", "%25", $encodedMeetingID);

            $meetingName = urlencode($res[0]["name"]);
            $emaillink1 = "https://mail.google.com/mail/?view=cm&ui=2&tf=0&fs=1&su=";
            
            // https://www.geeksforgeeks.org/get-the-full-url-in-php/
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";
                
            // Here append the common URL characters.
            $link .= "://";
            
            // Append the host(domain name, ip) to the URL.
            $link .= $_SERVER['HTTP_HOST'];
            
            // Append the requested resource location to the URL
            $link .= $_SERVER['PHP_SELF'];

            $emaillink2 = "&body=The+host+" . $name ."+invites+you+to+join+the+" .$meetingName ."." . "%0aClick Here: " . $link . "?joinID=" . $encodedMeetingID;
            $url = $emaillink1 . $meetingName . $emaillink2;
            $meetingStart = $_POST["meetingStart"];
            $meetingStop = $_POST["meetingStop"];
            $availabilities = "{\"availabilities\": []}";
        }

        # viewing meeting that was already made
        else {
            if(isset($_POST["meetingID"]))
                $meetingID = intval($_POST["meetingID"]);
            else 
                $meetingID = intval($_SESSION["meetingID"]);

            $res = $this->db->query("select * from meetings where id=$1;", $meetingID);
            
            $encodedMeetingID = urlencode(openssl_encrypt(str_repeat(strval($meetingID), 20), "AES-128-CTR", "hopefullySecure", 0, '3262560861013191'));
            $encodedMeetingID = str_replace("%", "%25", $encodedMeetingID);

            $meetingName = urlencode($res[0]["name"]);
            $emaillink1 = "https://mail.google.com/mail/?view=cm&ui=2&tf=0&fs=1&su=";

            // https://www.geeksforgeeks.org/get-the-full-url-in-php/
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";
                
            // Here append the common URL characters.
            $link .= "://";
            
            // Append the host(domain name, ip) to the URL.
            $link .= $_SERVER['HTTP_HOST'];
            
            // Append the requested resource location to the URL
            $link .= $_SERVER['PHP_SELF'];

            $emaillink2 = "&body=The+host+" . $name ."+invites+you+to+join+the+" .$meetingName ."." . "%0aClick Here: " . $link . "?joinID=" . $encodedMeetingID;
            $url = $emaillink1 . $meetingName . $emaillink2;
            $meetingStart = $res[0]["start"];
            $meetingStop = $res[0]["stop"];
            $availabilities = $res[0]["hostjson"];
        }

        $myEvents = [];
        $res = $this->db->query("select id from calendars where userID=$1;", $userID);
        foreach ($res as $key => $calID) {
            $calID = intval($calID["id"]);
            $myEvents[$calID] = $this->db->query("select * from events where calendarID=$1;", $calID);
        }
        $myEvents = json_encode($myEvents);

        $memberJson = [];
        $res = $this->db->query("SELECT memberID, json, jsonSubmitted from membersOf where meetingID=$1;", $meetingID);
        foreach ($res as $key => $member) {
            if($member["jsonsubmitted"] == true) {
                $memberID = intval($member["memberid"]);
                $memberJson[$memberID] = $member["json"];
            }
        }
        $memberJson = json_encode($res);

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/hostMain.php");
    }

    public function memberMain() {
        $message = "";
        $userID = $_SESSION["userID"];
        $email = $_SESSION["email"];

        $res = $this->db->query("select * from meetings where id=$1;", $_POST["meetingID"]);
        $meetingID = intval($_POST["meetingID"]);
        $meetingName = urlencode($res[0]["name"]);
        $meetingStart = $res[0]["start"];
        $meetingStop = $res[0]["stop"];
        $res = $this->db->query("select * from membersOf where meetingID=$1 and memberID=$2;", $_POST["meetingID"], $userID);
        $availabilities = $res[0]["json"];

        $myEvents = [];
        $res = $this->db->query("select id from calendars where userID=$1;", $userID);
        foreach ($res as $key => $calID) {
            $calID = intval($calID["id"]);
            $myEvents[$calID] = $this->db->query("select * from events where calendarID=$1;", $calID);
        }
        $myEvents = json_encode($myEvents);

        if (!empty($this->errorMessage)) {
            $message = "<div class='alert alert-danger'>{$this->errorMessage}</div>";
        }
        include("./opt/src/DashMeet/memberMain.php");
    }

}
