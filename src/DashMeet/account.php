<!-- Sources: 
    https://www.w3schools.com/php/php_file_upload.asp 
    https://stackoverflow.com/questions/12796324/is-there-any-php-function-for-open-page-in-new-tab

-->

<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>Account</title>
        <meta name="author" content="Sean Katauskas">
        <meta name="description" content="QuickMeet Scheduler">
        <meta name="keywords" content="QuickMeet">
         
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="/styles/main.less"/>
        <script src="/less.js" type="text/javascript"></script>
        
    </head>
    <body>
        <?php include("header.php"); ?>
        

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                Account Page
            </span>
        </div>


        <div class="container">
            <h1>Dashboard</h1>            
            <div class="d-flex flex-row flex-wrap align-items-stretch column-area">

                <div class="p-2 flex-fill"> 
                    <h3>
                        Edit Profile:
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

                <div class="p-2 flex-fill">
                    <h3>My Meetings</h3>
                    <div class="card spacing">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Date & Time</th>
                                        <th scope="col">Meeting Title</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Feb 29, 2024</td>
                                        <td>Graphic Design is Our Passion</td> 
                                        <td>Pending</td>
                                        <td>Host</td>
                                        <td><a href="?command=hostMain" class="btn btn-primary">View Meeting</a></td>
                                    </tr>
                                    <tr>
                                        <td>Mar 5, 2024</td>
                                        <td>Transitional Product Meeting</td> 
                                        <td>Pending</td>
                                        <td>Member</td>
                                        <td><a href="meeting2.html" class="btn btn-primary disabled">View Meeting</a></td>
                                    </tr>
                                    <tr>
                                        <td>Mar 8, 2024</td>
                                        <td>Boring Meeting</td> 
                                        <td>Booked</td>
                                        <td>Host</td>
                                        <td><a href="meeting3.html" class="btn btn-primary disabled">View Meeting</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                    </div>
                </div>

                <div class="p-2 col-12 flex-fill">
                    <h3>My Calendars:</h3>
                    <div class="card spacing">
                        <form action="?command=import" method="post" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <button class="btn btn-primary" type="submit" name="submitImport" id="submitImport">Import New Calendar</button>
                                <input type="file" class="form-control" name="import" id="import" required>
                        </div>
                        </form>
                        <?php 
                            $res = $this->db->query("SELECT name, id FROM calendars;");
                            foreach ($res as $i => $cal) {
                                if($res[$i]["name"] !== NULL) {
                                    echo "<div class=\"row\">";
                                        echo "<form action=\"?command=account\" method=\"POST\">";
                                            echo "<div class=\"row\">";
                                                echo "<div class=\"col col-auto\"> <button class=\"btn btn-danger\" type=\"submit\" name=\"deleteCal\">Delete</button> </div>";
                                                echo "<div class=\"col\">" . $res[$i]["name"] . "</div>";
                                                echo "<input type=\"hidden\" name=\"deleteCalId\" value=\"" . $res[$i]["id"] . "\">";
                                            echo "</div>";
                                        echo "</form>";

                                        echo "<form action=\"?command=viewEvents\" method=\"POST\">";
                                            echo "<div class=\"col col-auto\"> <button class=\"btn btn-primary\" type=\"submit\" name=\"viewEvents\">View All Events</button> </div>";
                                            echo "<input type=\"hidden\" name=\"getID\" value=\"" . $res[$i]["id"] . "\">";
                                        echo "</form>";


                                        echo "<form action=\"?command=getJSON\" method=\"POST\" target=\"_blank\">";
                                            echo "<div class=\"col col-auto\"> <button class=\"btn btn-secondary\" type=\"submit\" name=\"getJSON\">Get JSON</button> </div>";
                                            echo "<input type=\"hidden\" name=\"getID\" value=\"" . $res[$i]["id"] . "\">";
                                        echo "</form>";
                                    echo "</div>";
                                }
                            }
                        ?>
                    </div>
                </div>

                <div class="p-2 flex-fill">
                    <h3>Edit Availabilities:</h3>
                    <a href="?command=memberMain" class="btn btn-primary">Edit</a>
                </div>
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
