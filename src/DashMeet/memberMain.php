<?php // If ajax requested, fulfill the request and then leave before anything else gets put in the response
    if(isset($_POST["AjaxRequest"])) {
        $res = $this->db->query("update membersOf set json=$3, jsonsubmitted=true where meetingID=$1 and memberID=$2;", 
                                intval($_POST["meetingID"]), intval($_POST["memberID"]), $_POST["availabilities"]);
        
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
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <title>Member View</title>
        <meta name="author" content="Sean Katauskas">
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

            function load() {
                $(".left-column-desktop >").clone().appendTo(".left-column-mobile");
                $('#prevPage').on("click", () => changePage(-1));
                $('#nextPage').on("click", () => changePage(1));
                $('.inner-cell').on("dblclick", innerCellDoubleClick);
                $('.inner-cell').on("click", innerCellClick);
                $('.my-calendar-checkbox').on("click", setupCal);

                setupCal();
            }


            function setupCal() {
                // CLEAR SELECTIONS
                $(".selected").removeClass("selected");
                $(".blocked").removeClass("blocked");
                $(".available").removeClass("available");
                $(".ignore").removeClass("ignore");


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
                    data: { "AjaxRequest": true, "host": false, "meetingID": <?=$meetingID?>, "memberID": <?=$userID?>, "availabilities": JSON.stringify({availabilities}) }
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
    <body onload="load();" onunload="save();">
        <?php include("header.php"); ?>

        <div class="row justify-content-between subheader">
            <span class="meeting-name">
                Meeting
            </span>
        </div>

        <div class="subbody">
            <button class="p-2 d-xl-none overflow-visible btn sidemenu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" aria-controls="offcanvasLeft">≡</button> <!-- only show on mobile -->
            
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
                    <!-- <p class="description-title">Additional Details:</p>
                    <textarea class="description-entry" name="desc-entry"></textarea> -->

                    <a class="btn btn-primary book-button" href="?command=account" type="button">Submit Availabilities</a>
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
