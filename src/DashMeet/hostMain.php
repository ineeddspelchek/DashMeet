<!-- Sources: 
    https://www.w3schools.com/tags/tryit.asp?filename=tryhtml_link_image
    https://stackoverflow.com/questions/8174282/link-to-reload-current-page
    https://getbootstrap.com/docs/5.0/examples/sidebars/
    https://community.wappler.io/t/bootstrap-5-offcanvas-not-working-with-local-bootstrap/32111/6
    https://stackoverflow.com/questions/74747140/bootstrap-error-this-config-is-undefined
    https://github.com/orgs/twbs/discussions/36626
    https://www.w3schools.com/tags/tag_textarea.asp
    https://stackoverflow.com/questions/14967421/css-calc-not-working
    https://developer.mozilla.org/en-US/docs/Web/JavaScript/
    https://stackoverflow.com/questions/61969955/bootstrap-fixed-width-table-columns-in-container-fluid
    https://www.w3schools.com/jquery/
    https://stackoverflow.com/questions/8481628/preventing-jquery-mouseleave-event-from-firing-while-mouse-button-is-held-down
    https://stackoverflow.com/questions/15470660/check-if-all-elements-satisfy-a-condition
    https://stackoverflow.com/questions/4287357/access-php-variable-in-javascript
    https://stackoverflow.com/questions/9989382/how-can-i-add-1-day-to-current-date
    https://stackoverflow.com/questions/563406/how-to-add-days-to-date
    https://stackoverflow.com/questions/3239598/how-can-i-get-the-id-of-an-element-using-jquery
    https://stackoverflow.com/questions/3954438/how-to-remove-item-from-array-by-value

-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>DashMeet - Graphic Design is Our Passion</title>
        <meta name="author" content="Henry Newton">
        <meta name="description" content="DashMeet Scheduler">
        <meta name="keywords" content="DashMeet">
         
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet/less" type="text/css" href="styles/main.less?ts=\<\?=filemtime('style.css')?"/>
        <script src="less.js" type="text/javascript"></script>
        <script src="jquery.js" type="text/javascript"></script>
        
        <script>
            var DAY_IN_SECS = 86400000;
            
            var currPage = 0;
            var meetingStart = new Date("<?= $meetingStart ?>");
            var meetingStop = new Date("<?= $meetingStop ?>");
            var sunday = new Date(meetingStart - meetingStart.getDay()*DAY_IN_SECS);
            sunday.setHours(0);
            sunday.setMinutes(0);
            sunday.setSeconds(0);
            var availabilities = [];

            function load() {
                $(".left-column-desktop >").clone().appendTo(".left-column-mobile");
                $('#prevPage').on("click", () => changePage(-1));
                $('#nextPage').on("click", () => changePage(1));
                $('.inner-cell').on("dblclick", innerCellDoubleClick);
                $('.inner-cell').on("click", innerCellClick);
                setupCal();
            }

            function setupCal() {
                // CLEAR SELECTIONS
                $(".selected").removeClass("selected");
                
                if(currPage >= availabilities.length) {
                    availabilities.push([]);
                }
                else {
                    availabilities[currPage].forEach(selection => {
                        $("#" + selection).addClass("selected");
                    });
                }
                
                for (let i = 0; i < 7; i++) {
                    tempDay = new Date(sunday);
                    tempDay.setDate(tempDay.getDate() + i);
                    $(".day-th:nth(" + i + ")").html((tempDay.getMonth()+1) + "/" + tempDay.getDate());
                }

                if(currPage == 0) {
                    $("#prevPage").addClass("disabled");
                }
                else {
                    $("#prevPage").removeClass("disabled");
                }

                let tempDate = new Date(sunday);
                tempDate.setDate(sunday.getDate() + 7);
                if(tempDate >= meetingStop) {
                    $("#nextPage").addClass("disabled");
                }
                else {
                    $("#nextPage").removeClass("disabled");
                }
            }

            function save() {
                saveAvailabilities();
            }

            function innerCellClick() {
                if($(this).hasClass("selected"))
                    $(this).removeClass("selected");
                else {
                    $(this).addClass("selected");
                }
            }

            function innerCellDoubleClick() {
                $siblings = $(this).siblings();

                // No clue why this works how I want it to, but it does so I'm not touching it anymore
                if($.grep($siblings, () => $(this).hasClass("selected")).length + $(this).hasClass("selected") === 4) {
                    $siblings.removeClass("selected");
                    $(this).removeClass("selected");
                }
                else {
                    $siblings.addClass("selected");
                    $(this).addClass("selected");
                }
            }

            function changePage(dir) {
                saveAvailabilities();
                
                currPage += dir;
                $("#currPage").html(currPage+1);

                sunday.setDate(sunday.getDate() + dir*7);

                setupCal();
            }

            function saveAvailabilities() {
                $(".selected").each(function() {
                    if(!availabilities[currPage].includes($(this).attr('id')))
                        availabilities[currPage].push($(this).attr('id'));
                });
                $("div:not(.selected)").each(function() {
                    ind = availabilities[currPage].indexOf($(this).attr('id'));
                    if(ind !== -1) {
                        availabilities[currPage].splice(ind, 1);
                    }
                });
            }
        </script>

    </head>
    <body onload="load();" onunload="save();">
        <?php include("header.php"); ?>

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                <?php $res = $this->db->query("SELECT name FROM meetings where id=$1;",$meetingID);?>
                Your <i>"<?=$res[0]["name"]?>"</i> Meet
            </span>
        </div>

        <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="shareModalLabel">Share Meeting</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="d-flex flex-column modal-body">
                    <input type="text" readonly="readonly" value="localhost:8080/?joinID=<?=$meetingID?>">
                    <img class="copy-icon" src="images/copy.png" alt="copy icon">

                    <div class="d-flex align-items-center btn btn-light gmail">
                        <img class="share-icon" src="images/gmail.png" alt="gmail icon">
                        <p>Gmail</p>
                    </div>
                    <div class="d-flex align-items-center btn btn-light facebook">
                        <img class="share-icon" src="images/facebook.png" alt="facebook icon">
                        <p>Facebook</p>
                    </div>
                    <div class="d-flex align-items-center btn btn-light twitter">
                        <img class="share-icon" src="images/twitter.png" alt="twitter icon">
                        <p>X (Twitter)</p>
                    </div>
                </div>
              </div>
            </div>
        </div>


        <div class="subbody">
            <button class="p-2 d-xl-none overflow-visible btn sidemenu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft">≡</button> <!-- only show on mobile -->
            
            <div class="d-flex flex-row flex-fill align-items-stretch column-area">
                <!-- ################################################################################################################## -->
                <div class="offcanvas-xl d-xl-none offcanvas-start left-column left-column-mobile" tabindex="-1" id="offcanvasLeft"></div>

                <div class="p-2 d-none d-xl-block flex-shrink-1 left-column left-column-desktop"> 
                    <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="btn btn-link collapse-button" onclick="$(this).hasClass('collapse-active') ? $(this).removeClass('collapse-active') : $(this).addClass('collapse-active')" data-bs-toggle="collapse" href=".collapse-1" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="dropdown icon">
                        </a>
                        <p>My Calendars</p>
                    </div>        
                    <div class="collapse collapse-body collapse-1">
                        <div class="card card-body">
                            <div class="border border-dark d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                                <a class="btn btn-link collapse-button" onclick="$(this).hasClass('collapse-active') ? $(this).removeClass('collapse-active') : $(this).addClass('collapse-active')" data-bs-toggle="collapse" href=".collapse-1-1" role="button" aria-expanded="false">
                                    <img src="images/left-arrow.png" alt="dropdown icon">
                                </a>
                                <p>File Calendars</p>
                            </div>
                            <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse-1-1">
                                <?php 
                                $res = $this->db->query("SELECT name, id FROM calendars where userID=$1;",$userID);
                                foreach ($res as $key => $calendar) {
                                    if(isset($calendar["name"])) {    
                                ?> 
                                    <li class="list-group-item">
                                        <div class="d-flex flex-row align-items-center others-calenders-container">
                                            <input type="checkbox" class="form-check-input" title="sean availabilities checkbox">
                                            <p class="p-1"><?=$calendar["name"]?></p>
                                        </div>
                                    </li>
                                <?php }}?>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex flex-row align-items-center shared-availabilities-container">
                        <input type="checkbox" class="btn-check shared-availabilities" title="shared availabilities checkbox">
                        <label class="btn btn-outline-primary shared-availabilities"></label><br>
                        <p class="p-0">Shared Availabilities</p>
                    </div>

                    <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="btn btn-link collapse-button" onclick="$(this).hasClass('collapse-active') ? $(this).removeClass('collapse-active') : $(this).addClass('collapse-active')" data-bs-toggle="collapse" href=".collapse-2" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="dropdown icon">
                        </a>
                        <p>Other's Calendars</p>        
                    </div>
                    <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse-2">
                        <?php 
                        $res = $this->db->query("SELECT fullname from membersOf join ourUsers on membersOf.memberID=ourUsers.id where meetingID=$1;", $meetingID);
                        foreach ($res as $key => $member) {
                        ?> 
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calenders-container">
                                    <input type="checkbox" class="form-check-input" title="sean availabilities checkbox">
                                    <p class="p-1"><?=$member["fullname"]?></p>
                                </div>
                            </li>
                        <?php }?>
                    </ul>
                    
                </div>

                <!-- ################################################################################################################## -->
                <div class="p-2 col-xl flex-grow-1 middle-column d-flex flex-column align-items-center">
                    <nav class = "pb-1" aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                            <a class="page-link disabled" id="prevPage" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                            </li>
                            <li class="page-item"><a class="page-link disabled" id="currPage" href="#">1</a></li>
                            <li class="page-item">
                            <a class="page-link" id="nextPage" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            </li>
                        </ul>
                    </nav>
                    <table class="table overflow-auto table-bordered">
                        <thead>
                            <tr class="d-flex justify-content-evenly">
                                <th class="col-1" scope="col"></th>
                                <th class="day-th flex-grow-1" scope="col">Su</th>
                                <th class="day-th flex-grow-1" scope="col">M</th>
                                <th class="day-th flex-grow-1" scope="col">Tu</th>
                                <th class="day-th flex-grow-1" scope="col">W</th>
                                <th class="day-th flex-grow-1" scope="col">Th</th>
                                <th class="day-th flex-grow-1" scope="col">F</th>
                                <th class="day-th flex-grow-1" scope="col">Sa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $times = ["12 AM"];
                            for ($i=1; $i < 12; $i++)
                                array_push($times, $i . " AM");
                            array_push($times, "12 PM");
                            for ($i=1; $i < 12; $i++)
                                array_push($times, $i . " PM");

                            foreach ($times as $key => $time) {
                                echo "<tr class='d-flex justify-content-evenly'>";
                                echo "<th class='col-1'>" . $time . "</th>";
                                for ($i=0; $i < 7; $i++) { 
                                    echo "<td class='p-0 d-flex flex-column flex-grow-1 justify-content-evenly'>";
                                    for ($j=0; $j < 4; $j++) {
                                        $loc = strval($key*100 + $j*15);
                                        $loc = str_pad($loc, 4, "0", STR_PAD_LEFT);
                                        echo "<div class='flex-grow flex-fill inner-cell' id='" . $i . "-" . $loc . "'></div>";
                                    }
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- ################################################################################################################## -->
                <div class="p-2 flex-shrink-1 right-column">
                    <p class="host-title">Host:</p>
                    <p class="host-body">
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?=$email?>"><?=$name?></a>
                    </p>

                    <p class="member-title">Joined Attendees:</p>
                    <p class="d-flex flex-column member-body">
                        <?php 
                        $res = $this->db->query("SELECT fullname, email from membersOf join ourUsers on membersOf.memberID=ourUsers.id where meetingID=$1;", $meetingID);
                        foreach ($res as $key => $member) {
                        ?>
                            <a class="w-100" href="#" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?=$member["email"]?>"><?=$member["fullname"]?></a>
                        <?php } ?>
                    </p>

                    <!-- <p class="description-title">Description:</p>
                    <textarea class="description-entry" name="desc-entry" title="description entry box"></textarea> -->

                    <button class="btn btn-dark share-button" type="button" data-bs-toggle="modal" data-bs-target="#shareModal">Invite More</button>
                    <button class="btn btn-success book-button" type="button" disabled>Book Meeting</button>
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