<?php // If ajax requested, fulfill the request and then leave before anything else gets put in the response
    if(isset($_POST["AjaxRequest"])) {
        $res = $this->db->query("update meetings set hostJSON=$2 where id=$1;", 
                                $_POST["meetingID"], $_POST["availabilities"]);
        
        return;
    }
?>

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
    https://stackoverflow.com/questions/28993157/visibilitychange-event-is-not-triggered-when-switching-program-window-with-altt
    https://developer.mozilla.org/en-US/docs/Web/
    https://stackoverflow.com/questions/1090815/how-to-clone-a-date-object
    https://stackoverflow.com/questions/1885557/simplest-code-for-array-intersection-in-javascript

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
            addEventListener("beforeunload", (event) => {
                event.preventDefault();

                save();
            })
            var DAY_IN_SECS = 86400000;
            
            var currPage = 0;
            var meetingStart = new Date("<?= $meetingStart ?>");
            var meetingStop = new Date("<?= $meetingStop ?>");
            var sunday = new Date(meetingStart - meetingStart.getDay()*DAY_IN_SECS);
            sunday.setHours(0);
            sunday.setMinutes(0);
            sunday.setSeconds(0);
            var availabilities = <?= $availabilities ?>["availabilities"];
            var myEvents = <?php echo $myEvents; ?>;
            var tempMemberJson = <?php echo $memberJson; ?>;
            var memberJson = [];
            tempMemberJson.forEach(member => {
                memberJson[parseInt(member["memberid"])] = JSON.parse(member["json"])["availabilities"];
            });

            <?php 
            if(isset($_SESSION["makingNew"]))
                    unset($_SESSION["makingNew"]); 
            ?>

            function load() {
                $(".left-column-desktop >").clone().appendTo(".left-column-mobile");
                $('#prevPage').on("click", () => changePage(-1));
                $('#nextPage').on("click", () => changePage(1));
                $('.inner-cell').on("dblclick", innerCellDoubleClick);
                $('.inner-cell').on("click", innerCellClick);
                $('.my-calendar-checkbox').on("click", setupCal);
                $('.others-calendar-checkbox').on("click", setupCal);
                $('.shared-availabilities').on("click", setupCal);
                
                setupCal();
            }


            function setupCal() {
                // CLEAR SELECTIONS
                $(".selected").removeClass("selected");
                $(".blocked").removeClass("blocked");
                $(".available").removeClass("available");
                $(".ignore").removeClass("ignore");
                $(".shared").removeClass("shared");

                if($('.shared-availabilities').is(':checked')) {
                    var intersectArr = "no";
                    $(".others-calendar-checkbox").each(function() {
                        if($(this).is(':checked')) {
                            id = parseInt($(this).attr("id"));
                            if(intersectArr == "no") {
                                intersectArr = memberJson[id][currPage];
                            }
                            else {
                                intersectArr = intersectArr.filter(value => memberJson[id][currPage].includes(value));
                            }
                        }
                    });
                    if(intersectArr != "no") {
                        intersectArr.forEach(block => {
                                $("#"+block).addClass("shared");
                        });
                    }
                }

                get15Blocks(sunday, meetingStart).forEach(ignore => {
                    $("#"+ignore).addClass("ignore");
                });

                let tempDate = new Date(sunday);
                tempDate.setDate(sunday.getDate() + 7);
                get15Blocks(meetingStop, tempDate).forEach(ignore => {
                    $("#"+ignore).addClass("ignore");
                });

                $(".my-calendar-checkbox").each(function() {
                    if($(this).is(':checked')) {
                        id = parseInt($(this).attr("id"));
                        myEvents[id].forEach(event => {
                            blocks = get15Blocks(new Date(event["start"]), new Date(event["stop"]));
                            blocks.forEach(block => {
                                $("#"+block).addClass("blocked");
                            });
                        });
                    }
                })

                $(".others-calendar-checkbox").each(function() {
                    if($(this).is(':checked')) {
                        id = parseInt($(this).attr("id"));
                        memberJson[id][currPage].forEach(element => {
                            $("#"+element).addClass("available");
                        });
                    }
                });

                
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

                tempDate = new Date(sunday);
                tempDate.setDate(sunday.getDate() + 7);
                if(tempDate >= meetingStop) {
                    $("#nextPage").addClass("disabled");
                }
                else {
                    $("#nextPage").removeClass("disabled");
                }
            }

            function save() {
                saveCurrentAvailabilities();
                $.ajax({
                    method: "POST",
                    data: { "AjaxRequest": true, "host": true, "meetingID": <?=$meetingID?>, "memberID": <?=$userID?>, "availabilities": JSON.stringify({availabilities}) }
                }).done((x) => console.log(x));
            }

            function innerCellClick() {
                if($(this).hasClass("selected"))
                    $(this).removeClass("selected");
                else {
                    $(this).addClass("selected");
                }
                saveCurrentAvailabilities();
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
                saveCurrentAvailabilities();
            }

            function changePage(dir) {
                saveCurrentAvailabilities();
                
                currPage += dir;
                $("#currPage").html(currPage+1);

                sunday.setDate(sunday.getDate() + dir*7);

                setupCal();
            }

            function saveCurrentAvailabilities() {
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

            function get15Blocks(start, stop) {
                let startCopy = new Date(start.valueOf());
                let stopCopy = new Date(stop.valueOf());

                if(start >= stop) {
                    return [];
                }
                blocks = [];
                let startDay = Math.floor((start - sunday) / DAY_IN_SECS);
                let stopDay = Math.floor((stop - sunday) / DAY_IN_SECS);

                if(startDay != stopDay) {
                    out = [];

                    startEnd = new Date(start.valueOf());
                    startEnd.setHours(23);
                    startEnd.setMinutes(45);
                    out = out.concat(get15Blocks(start, startEnd));

                    let tempDate2 = new Date(start.valueOf());
                    tempDate2.setHours(0);
                    tempDate2.setMinutes(0);
                    for (let i = startDay+1; i < stopDay; i++) {
                        tempDate2.setDate(tempDate2.getDate() + 1);
                        tempDate3 = new Date(tempDate2.valueOf());
                        tempDate3.setHours(23);
                        tempDate3.setMinutes(50);
                        out = out.concat(get15Blocks(tempDate2, tempDate3));
                    }

                    stopStart = new Date(stop.valueOf());
                    stopStart.setHours(0);
                    stopStart.setMinutes(0);
                    out = out.concat(get15Blocks(stopStart, stop));

                    return out; 
                }

                startHour = Math.floor((((start - sunday) / DAY_IN_SECS) - startDay ) * 24);

                stopHour = Math.floor((((stop - sunday) / DAY_IN_SECS) - stopDay ) * 24);

                startRem = (((start - sunday) / DAY_IN_SECS) - startDay ) * 24 - startHour;

                stopRem = (((stop - sunday) / DAY_IN_SECS) - stopDay ) * 24 - stopHour;

                if((startDay < 0 && stopDay < 0) || (startDay > 7 && startDay > 7)){
                    return [];
                }

                if(startDay < 0) {
                    startDay = 0;
                    startHour = 0;
                    startRem = 0;
                }
                if(stopDay > 7) {
                    stopDay = 7;
                    stopHour = 23;
                    stopRem = 45;
                }

                if(startRem >= .75)
                    startSub = 45;
                else if(startRem >= .5)
                    startSub = 30;
                else if(startRem >= .25)
                    startSub = 15;
                else
                    startSub = 0;

                if(stopRem >= .75)
                    stopSub = 45;
                else if(stopRem >= .5)
                    stopSub = 30;
                else if(stopRem >= .25)
                    stopSub = 15;
                else
                    stopSub = 0;

                startHour = startHour;
                startRem = startRem;
                hour = startHour;
                rem = startRem * 60 - startRem * 60 % 15;

                while(hour != stopHour || rem != stopSub) {
                    tempH = (""+hour).padStart(2, "0");
                    tempR = (""+rem).padStart(2, "0");
                    blocks.push("" + startDay + "-" + tempH + tempR);
                    rem = rem + 15;
                    if(rem == 60) {
                        hour = parseInt(hour) + 1;
                        rem = 0;
                    }
                }

                //DO IT ONE MORE TIME
                tempH = (""+hour).padStart(2, "0");
                tempR = (""+rem).padStart(2, "0");
                blocks.push("" + startDay + "-" + tempH + tempR);
                rem = rem + 15;
                if(rem == 60) {
                    hour = parseInt(hour) + 1;
                    rem = 0;
                }

                return blocks;
            }
        </script>

    </head>
    <body onload="load();">
        <?php include("header.php"); ?>

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                <?php $res = $this->db->query("SELECT name FROM meetings where id=$1;",$meetingID);?>
                Your <i>"<?=$res[0]["name"]?>"</i> Meet
            </span>
        </div>

        <div class="modal fade" id="shareModal" aria-hidden="true"> <!-- removed tabindex="-1" -->
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="shareModalLabel">Share Meeting</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" tabindex="0"></button>
                </div>
                <div class="d-flex flex-column modal-body">
                    <label for="link">Invite Link</label>
                    <input type="text" readonly="readonly" id="link" value="https://cs4640.cs.virginia.edu/han5jn/?joinID=<?=$encodedMeetingID?>"> 
                    <img class="copy-icon" src="images/copy.png" alt="copy icon">

                    <div class="d-flex align-items-center btn btn-light gmail" onclick="window.open('<?=$url?>', '_blank')">
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
                                            <input type="checkbox" class="form-check-input my-calendar-checkbox" id="<?=$calendar["id"]?>" title="sean availabilities checkbox">
                                            <p class="p-1"><?=$calendar["name"]?></p>
                                        </div>
                                    </li>
                                <?php }}?>
                            </ul>
                        </div>
                    </div>

                    <div class="p-2 d-flex flex-row align-items-center shared-availabilities-container">
                        <input type="checkbox" class="shared-availabilities" title="shared availabilities checkbox">
                        <p class="p-2">Shared Availabilities</p>
                    </div>

                    <div class="d-flex flex-row align-items-center drop-down-header drop-down-header-h1">
                        <a class="btn btn-link collapse-button" onclick="$(this).hasClass('collapse-active') ? $(this).removeClass('collapse-active') : $(this).addClass('collapse-active')" data-bs-toggle="collapse" href=".collapse-2" role="button" aria-expanded="false">
                            <img src="images/left-arrow.png" alt="dropdown icon">
                        </a>
                        <p>Other's Calendars</p>        
                    </div>
                    <ul class="collapse collapse-body list-group others-calendars-collapse-body collapse-2">
                        <?php 
                        $res = $this->db->query("SELECT fullname, id from membersOf join ourUsers on membersOf.memberID=ourUsers.id where meetingID=$1;", $meetingID);
                        foreach ($res as $key => $member) {
                        ?> 
                            <li class="list-group-item">
                                <div class="d-flex flex-row align-items-center others-calenders-container">
                                    <input type="checkbox" class="form-check-input others-calendar-checkbox" id="<?=$member["id"]?>" title="sean availabilities checkbox">
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
                                <th class="col-1" scope="col" style="width: 70px"></th>
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
                    <button class="btn btn-success book-button" type="button">Book Meeting</button>
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