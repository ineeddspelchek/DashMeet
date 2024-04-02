<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- https://cs4640.cs.virginia.edu/han5jn/DashMeet -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>Account</title>
        <meta name="author" content="Sean Katauskas">
        <meta name="description" content="QuickMeet Scheduler">
        <meta name="keywords" content="QuickMeet">     
         
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="/styles/main.less" />
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
            <div class="d-flex flex-row flex-fill flex-wrap align-items-stretch column-area">

                <div class="offcanvas-xl offcanvas-start left-column left-column-mobile" tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasLeft" aria-label="Close"></button>
                        <h5 class="offcanvas-title" id="offcanvasLeftLabel">Edit Profile</h5>
                    </div>
                    <div class="p-2 d-xl-none offcanvas-body">
                    </div>
                </div>

                <div class="p-2 col-sm-2 flex-grow-1 left-column "> 
                    <h3>
                        Edit Profile:
                    </h3>
                        <div class="card">
                            <form>
                                <div class="spacing">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="Enter your first name">
                                </div>
                                <div class="spacing">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter your last name">
                                </div>
                                <div class="spacing">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" placeholder="Enter your email">
                                </div>
                                <button type="submit" class="btn btn-primary" disabled>Save Changes</button>
                            </form>
                        </div>
                </div>

                <div class="p-2 col-xl flex-grow-1 right-column">
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

            </div>
            <div class="p-2 col-xl flex-grow-1 right-column">
                <h3>Edit Availabilities:</h3>
                <a href="?command=memberMain" class="btn btn-primary">Edit</a>
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
