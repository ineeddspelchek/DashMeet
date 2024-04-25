<!-- Sources: 
    https://www.w3schools.com/php/php_file_upload.asp 
    https://stackoverflow.com/questions/12796324/is-there-any-php-function-for-open-page-in-new-tab
    https://www.w3schools.com/jsref
    https://stackoverflow.com/questions/10233550/launch-bootstrap-modal-on-page-load
    https://stackoverflow.com/questions/3812526/conditional-statements-in-php-code-between-html-code
    https://stackoverflow.com/questions/2680160/how-can-i-tell-which-button-was-clicked-in-a-php-form-submit
    https://www.tutorialspoint.com/how-to-remove-event-handlers-in-javascript
    https://stackoverflow.com/questions/53594423/how-to-open-a-bootstrap-modal-without-jquery-or-bootstrap-js-javascript
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>Dashboard</title>
        <meta name="author" content="Sean Katauskas">
        <meta name="description" content="DashMeet Scheduler">
        <meta name="keywords" content="DashMeet">
         
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="styles/main.less?ts=\<\?=filemtime('style.css')?"/>
        <script src="less.js" type="text/javascript"></script>
        <script src="jquery.js" type="text/javascript"></script>
        
        <script>
            function load() {
                if(document.getElementById('joinModal') != null)
                    new bootstrap.Modal(document.getElementById('joinModal')).show();
                document.getElementById('meetingValidationMessage').style.display = 'none'; //$("#meetingValidationMessage").hide()
            }

            function save() {
            }

            function newMeetingContinue() {
                start = Date.parse(document.getElementById("meetingStart").value); 
                stop = Date.parse(document.getElementById("meetingStop").value); 
                console.log(start);
                // start = Date.parse($("#meetingStart").val());
                // stop = Date.parse($("#meetingStop").val());
                today = Date.now();

                message = ""
                
                if(start < today)
                    message = "Start time has already passed."
                else if(stop < today)
                    message = "End time has already passed."
                else if(start == stop)
                    message = "Start time is the same as end time."
                else if(stop < start)
                    message = "End time is before start time."

                if(message != "") {
                    let validationMessage = document.getElementById('meetingValidationMessage'); // $("#meetingValidationMessage").html(message)
                    validationMessage.innerHTML = message;
                    validationMessage.style.display = 'block'; //$("#meetingValidationMessage").show()
                    setTimeout(function() {
                        validationMessage.innerHTML = "" //$("#meetingValidationMessage").html("");
                        validationMessage.style.display = 'none'; //$("#meetingValidationMessage").hide();
                    }, 4000);
                    return false;
                }
                return true;
            }
        </script>

    </head>
    <body onload="load();" onunload="save();">
        <?php include("header.php"); ?>

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                Dashboard
            </span>
        </div>

        <div class="modal fade" id="newMeetingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="shareModalLabel">New Meeting</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="?command=hostMain" method="post" id="newMeetingContinue">
                    <div class="d-flex flex-column">
                        Meeting Name:
                        <input type="text" name="meetingName" required>
                        Start Time:
                        <input type="datetime-local" name="meetingStart" id="meetingStart" required>
                        End Time:
                        <input type="datetime-local" name="meetingStop" id="meetingStop" required>
                        
                        <div class="alert alert-warning" id="meetingValidationMessage"></div>
                        <button class="btn btn-primary">Continue</button>
                        
                    </div>
                </form>
                <script>
                    document.getElementById('newMeetingContinue').addEventListener('submit', function handleSubmit(event) {
                        event.preventDefault();
                        if(newMeetingContinue()) {
                            nothingVariable = <?=$_SESSION["makingNew"] = true;?>;
                            document.getElementById('newMeetingContinue').removeEventListener('submit', handleSubmit);
                            document.getElementById('newMeetingContinue').submit();
                        }
                    });
                    // $("#newMeetingContinue").on('submit', (event) => {
                    //     event.preventDefault();
                    //     if(newMeetingContinue())
                    //         $("#newMeetingContinue").off('submit');
                    //         $("#newMeetingContinue").submit();
                    // });
                </script>
                </div>
              </div>
            </div>
        </div>
        
        <?php if(isset($joinID)) {?>
            <div class="modal fade" id="joinModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="joinModalLabel">Invited to Meeting</h1>
                    </div>
                    <div class="d-flex flex-column modal-body">
                        <p>Meeting Name: <?= $_SESSION["meeting"]["name"]?></p>
                        <p>Host: <?= $_SESSION["meeting"]["fullname"]?> (<?= $_SESSION["meeting"]["email"]?>)</p>
                        <form action="?command=account" method="post">
                            <button type="submit" class="btn btn-primary" name="accept">Accept</button>
                            <button type="submit" class="btn btn-danger" name="decline">Decline</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        <?php } ?>

        <div class="container">         
            <div class="d-flex flex-row flex-wrap align-items-stretch column-area">
                <div class="p-2 flex-fill"> 
                    <h3>
                        Edit Profile
                    </h3>
                    <div class="card">
                        <form action="?command=changeProfile" method="post">
                            <div class="spacing">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your name">
                            </div>
                            <div class="spacing">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="Change your password">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                <div class="p-2 w-100 flex-fill">
                    <h3>My Hosted Meetings</h3>
                    <div class="card spacing">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Meeting Title</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $res = $this->db->query("select * from meetings where hostID=$1",
                                        $userID);
                                        
                                        foreach ($res as $key => $meeting) {
                                            echo "<tr>";
                                            echo "<td>" . $meeting["name"] . "</td>";
                                            echo "<td>" . $meeting["start"] . "</td>";
                                            echo "<td>" . $meeting["stop"] . "</td>";

                                            echo "<td><div class=\"d-flex\">";

                                            echo "<form action=\"?command=hostMain\" method=\"POST\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-primary\" type=\"submit\" name=\"viewMeeting\">View</button> </div>";
                                                echo "<input type=\"hidden\" name=\"meetingID\" value=\"" . $meeting["id"] . "\">";
                                            echo "</form>";

                                            echo "<form action=\"?command=account\" method=\"POST\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-danger\" type=\"submit\" name=\"cancelMeeting\">Cancel</button> </div>";
                                                echo "<input type=\"hidden\" name=\"delMeetingID\" value=\"" . $meeting["id"] . "\">";
                                            echo "</form>";

                                            echo "</div></td></tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation example">
                                <!-- <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul> -->
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMeetingModal">Start New Meeting</button>
                            </nav>
                    </div>
                </div>
                
                <div class="p-2 w-100 flex-fill">
                    <h3>My Joined Meetings</h3>
                    <div class="card spacing">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Meeting Title</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $res = $this->db->query("select * from meetings natural join membersOf where memberID=$1",
                                        $userID);
                                        
                                        foreach ($res as $key => $meeting) {
                                            echo "<tr>";
                                            echo "<td>" . $meeting["name"] . "</td>";
                                            echo "<td>" . $meeting["start"] . "</td>";
                                            echo "<td>" . $meeting["stop"] . "</td>";

                                            echo "<td><div class=\"d-flex\">";

                                            echo "<form action=\"?command=memberMain\" method=\"POST\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-primary\" type=\"submit\" name=\"viewMeeting\">View</button> </div>";
                                                echo "<input type=\"hidden\" name=\"meetingID\" value=\"" . $meeting["id"] . "\">";
                                            echo "</form>";

                                            echo "</div></td></tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <!-- <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav> -->
                    </div>
                </div>

                <div class="p-2 col-12 flex-fill">
                    <h3>My Calendars</h3>
                    <div class="card spacing">
                        <form action="?command=import" method="post" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <button class="btn btn-primary" type="submit" name="submitImport" id="submitImport">Import New Calendar</button>
                                <!-- <label for="import">Upload File</label> -->
                                <input type="file" class="form-control" name="import" id="import" aria-label="Upload File" required>
                        </div>
                        </form>
                        <?php 
                            $res = $this->db->query("SELECT name, id FROM calendars where userID=$1;",$userID);
                            foreach ($res as $i => $cal) {
                                if($res[$i]["name"] !== NULL) {
                                    if($res[$i]["name"] !== NULL) {
                                        echo "<div class=\"container row\">";
                                            echo "<form action=\"?command=account\" method=\"POST\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-danger\" type=\"submit\" name=\"deleteCal\">Delete</button> </div>";
                                                echo "<input type=\"hidden\" name=\"deleteCalId\" value=\"" . $res[$i]["id"] . "\">";
                                            echo "</form>";
    
                                            echo "<form action=\"?command=viewEvents\" method=\"POST\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-primary\" type=\"submit\" name=\"viewEvents\">View All Events</button> </div>";
                                                echo "<input type=\"hidden\" name=\"getID\" value=\"" . $res[$i]["id"] . "\">";
                                            echo "</form>";
    
    
                                            echo "<form action=\"?command=getJSON\" method=\"POST\" target=\"_blank\" class=\"col col-auto me-auto\">";
                                                echo "<div> <button class=\"btn btn-secondary\" type=\"submit\" name=\"getJSON\">Get JSON</button> </div>";
                                                echo "<input type=\"hidden\" name=\"getID\" value=\"" . $res[$i]["id"] . "\">";
                                            echo "</form>";
    
                                            echo "<div class=\"col\">" . $res[$i]["name"] . "</div>";
                                        echo "</div>";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>

                <!-- <div class="p-2 flex-fill">
                    <h3>Edit Availabilities:</h3>
                    <a href="?command=memberMain" class="btn btn-primary">Edit</a>
                </div> -->
            </div>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
        
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
    </body>
</html>
