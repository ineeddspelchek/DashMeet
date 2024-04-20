<!-- Sources: 
    https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_link_image
    https://stackoverflow.com/questions/8174282/link-to-reload-current-page
    https://getbootstrap.com/docs/5.0/examples/sidebars/
    https://community.wappler.io/t/bootstrap-5-offcanvas-not-working-with-local-bootstrap/32111/6
    https://stackoverflow.com/questions/74747140/bootstrap-error-this-config-is-undefined
    https://github.com/orgs/twbs/discussions/36626
    https://www.w3schools.com/tags/tag_textarea.asp
    https://stackoverflow.com/questions/14967421/css-calc-not-working
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>Availability Scheduler</title>
        <meta name="author" content="Sean Katauskas">
        <meta name="description" content="DashMeet Scheduler">
        <meta name="keywords" content="DashMeet">     
         
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="styles/main.less?ts=\<\?=filemtime('style.css')?"/>
        <script src="less.js" type="text/javascript"></script>
        <script src="jquery.js" type="text/javascript"></script>
        
        <script>
            function load() {
                $(".left-column-desktop >").clone().appendTo(".left-column-mobile");
            }

            function save() {
            }
        </script>

    </head>
    <body onload="load();" onunload="save();">
        <?php include("header.php"); ?>

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                General Availability Scheduler
            </span>
        </div>

        <div class="subbody">
            <button class="p-2 d-xl-none overflow-visible btn sidemenu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" aria-controls="offcanvasLeft">≡</button> <!-- only show on mobile -->
            
            <div class="d-flex flex-row flex-fill align-items-stretch column-area">

                <!-- ################################################################################################################## -->
                <div class="offcanvas-xl d-xl-none offcanvas-start left-column left-column-mobile" tabindex="-1" id="offcanvasLeft"></div>

                <div class="p-2 d-none d-xl-block flex-shrink-1 left-column left-column-desktop"> 
                    <div class="border border-dark d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-1" data-bs-toggle="collapse" href=".collapse2-1" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="inactive drop-down icon">
                        </a>    
                        <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-1" data-bs-toggle="collapse" href=".collapse2-1" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="active drop-down icon">
                        </a>
                        <p>iCloud Calendars</p>
                    </div>        
                    <div class="border border-dark collapse collapse-body collapse2-1" style="margin-left: 1rem;">
                            <!-- inside collapsed -->
                            <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                                <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-1-1" data-bs-toggle="collapse" href=".collapse2-1-1" role="button" aria-expanded="false">
                                    <img src="images/left-arrow.png" alt="inactive drop-down icon">
                                </a>    
                                <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-1-1" data-bs-toggle="collapse" href=".collapse2-1-1" role="button" aria-expanded="false">
                                    <img src="images/left-arrow.png" alt="active drop-down icon">
                                </a>      
                                <p>johndoe@icloud.com</p>
                            </div> 
                            <!-- inside collapsed -->
                            <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse2-1-1">
                                <li class="list-group-item">
                                    <div class="d-flex flex-row align-items-center others-calenders-container">
                                        <input type="checkbox" class="btn-check others-calenders" title="sean availabilities checkbox">
                                        <label class="btn btn-outline-warning others-calenders"></label><br>
                                        <p class="p-0">March Madness</p>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex flex-row align-items-center others-calendars-container">
                                        <input type="checkbox" class="btn-check others-calenders" title="henry availabilities checkbox">
                                        <label class="btn btn-outline-success others-calenders"></label><br>
                                        <p class="p-0">Birthdays</p>
                                    </div>
                                </li>
                            </ul>
                    </div>


                    <div class="border border-dark d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-2" data-bs-toggle="collapse" href=".collapse2-2" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="inactive drop-down icon">
                        </a>
                        <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-2" data-bs-toggle="collapse" href=".collapse2-2" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="active drop-down icon">
                        </a>
                        <p>Google Calendars</p>        
                    </div>     
                    <div class="border border-dark collapse collapse-body collapse2-2" style="margin-left: 1rem;">
                        <!-- inside collapsed -->
                        <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                            <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-2-1" data-bs-toggle="collapse" href=".collapse2-2-1" role="button" aria-expanded="false">
                                <img src="images/left-arrow.png" alt="inactive drop-down icon">
                            </a>    
                            <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-2-1" data-bs-toggle="collapse" href=".collapse2-2-1" role="button" aria-expanded="false">
                                <img src="images/left-arrow.png" alt="active drop-down icon">
                            </a>      
                            <p>johndoe@gmail.com</p>
                        </div>    
                        <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse2-2-1">
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calenders-container">
                                    <input type="checkbox" class="btn-check others-calenders" title="sean availabilities checkbox">
                                    <label class="btn btn-outline-warning others-calenders"></label><br>
                                    <p class="p-0">Work Events</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calendars-container">
                                    <input type="checkbox" class="btn-check others-calenders" title="henry availabilities checkbox">
                                    <label class="btn btn-outline-success others-calenders"></label><br>
                                    <p class="p-0">School Schedule</p>
                                </div>
                            </li>
                        </ul>                     
                    </div>
                    

                    <div class="border border-dark d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-3" data-bs-toggle="collapse" href=".collapse2-3" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="inactive drop-down icon">
                        </a>
                        <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-3" data-bs-toggle="collapse" href=".collapse2-3" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="active drop-down icon">
                        </a>
                        <p>Outlook Calendars</p>        
                    </div>     
                    <div class="border border-dark collapse collapse-body collapse2-3" style="margin-left: 1rem;">
                        <!-- inside collapsed -->
                        <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                            <a class="collapse show btn btn-link collapse-button collapse-button-inactive collapse2-3-1" data-bs-toggle="collapse" href=".collapse2-3-1" role="button" aria-expanded="false">
                                <img src="images/left-arrow.png" alt="inactive drop-down icon">
                            </a>    
                            <a class="collapse btn btn-link collapse-button collapse-button-active collapse2-3-1" data-bs-toggle="collapse" href=".collapse2-3-1" role="button" aria-expanded="false">
                                <img src="images/left-arrow.png" alt="active drop-down icon">
                            </a>      
                            <p>johndoe@hotmail.com</p>
                        </div> 
                        <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse2-3-1">
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calenders-container">
                                    <input type="checkbox" class="btn-check others-calenders" title="sean availabilities checkbox">
                                    <label class="btn btn-outline-warning others-calenders"></label><br>
                                    <p class="p-0">Sports Schedule</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calendars-container">
                                    <input type="checkbox" class="btn-check others-calenders" title="henry availabilities checkbox">
                                    <label class="btn btn-outline-success others-calenders"></label><br>
                                    <p class="p-0">Break Schedule</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                       
                    
                    
                </div>

                <!-- ################################################################################################################## -->
                <div class="p-2 col-xl flex-grow-1 middle-column">
                    <table class="table overflow-auto table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th class="day-th" scope="col">Su</th>
                                <th class="day-th" scope="col">M</th>
                                <th class="day-th" scope="col">Tu</th>
                                <th class="day-th" scope="col">W</th>
                                <th class="day-th" scope="col">Th</th>
                                <th class="day-th" scope="col">F</th>
                                <th class="day-th" scope="col">Sa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>12 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>1 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>2 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>3 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>4 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>5 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>6 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>7 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>8 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>9 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>10 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>11 AM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>12 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>1 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>2 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>3 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>4 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>5 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>6 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>7 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>8 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>9 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>10 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                            <tr>
                                <th>11 PM</th>
                                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ################################################################################################################## -->
                <div class="p-2 flex-shrink-1 right-column">
                    <p class="description-title">Additional Details:</p>
                    <textarea class="description-entry" name="desc-entry"></textarea>

                    <button class="btn btn-primary book-button" type="button" disabled>Submit Availabilities</button>
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
