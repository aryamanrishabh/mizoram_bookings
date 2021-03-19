<?php
function build_calendar($month, $year){

    $mysqli =  new mysqli('localhost','root','','football');
    

    $daysOfWeek = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    $firstDayOfMonth  = mktime(0,0,0,$month,1,$year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getDate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];
    $dateToday = date('Y-m-d');
    // $calendar = "<table class='table table-bordered'>";
    
    $prev_month = date('m', mktime(0,0,0,$month-1,1,$year));
    $prev_year = date('Y', mktime(0,0,0,$month-1,1,$year));
    $next_month = date('m', mktime(0,0,0,$month+1,1,$year));
    $next_year = date('Y', mktime(0,0,0,$month+1,1,$year));
    $calendar = "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."'>Prev Month</a>";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a>";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=".$next_year."'>Next Month</a></center>";
    
    $calendar .= "<br><table class='table table-bordered'>";
    $calendar .= "<tr>";
    foreach($daysOfWeek as $day){
        $calendar .= "<th class='header'>$day</th>";
    }
    $calendar .= "</tr><tr>";
    $currentDay = 1;
    if($dayOfWeek>0){
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    $month = str_pad($month,2,"0",STR_PAD_LEFT);
    while($currentDay<=$numberDays){
        if($dayOfWeek == 7){
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay,2,"0",STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayName = strtolower(date('l',strtotime($date)));
        $today = $date==date('Y-m-d')?'today':'';
        if($date<date('Y-m-d')){
            $calendar .="<td class='$today'><h4>$currentDay</h4> <a class='btn btn-danger btn-xs'><i class='fa fa-times' aria-hidden='true'></i>
            </a></td>";
        }
        
        else{
            $calendar .="<td class='$today'><h4>$currentDay</h4> <a href='book_kk.php?date=".$date."' class='btn btn-success btn-xs'><i class='fa fa-check' aria-hidden='true'></i>
            </a></td>";
        }

        // $calendar .="<td class='$today'><h4>$currentDay</h4> <a class='btn btn-success btn-xs'>Book</a></td>";
        $currentDay++;
        $dayOfWeek++;

    }

    if($dayOfWeek<7){
        $remainingDays = 7 - $dayOfWeek;
        for($i=0;$i<$remainingDays;$i++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr></table>";


    return $calendar;
    
    
    
    
}
?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" media="screen and (min-device-width: 841px)" href="../../../../css/style.css" />

        <link rel="stylesheet" href="../../../../css/font-awesome.min.css" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=Roboto&display=swap"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            media="screen and (max-device-width: 840px)"
            href="../../../../css/mobstyle.css"
        />
        <style>
            /* @media only screen and(max-width:760px),
            (min-device-width:802px) and (max-device-width: 1020px) {
                table,
                thead,
                tbody,
                th,
                td,
                tr{
                    display: block;
                }
                .empty{
                    display: none;
                }
                th{
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                tr{
                    border: 1px solid #ccc;
                }
                td{
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding-left: 50%;
                }
                td:nth-of-type(1):before{
                    content: "Sunday";
                }
                td:nth-of-type(2):before{
                    content: "Monday";
                }
                td:nth-of-type(3):before{
                    content: "Tuesday";
                }
                td:nth-of-type(4):before{
                    content: "Wednesday";
                }
                td:nth-of-type(5):before{
                    content: "Thursday";
                }
                td:nth-of-type(6):before{
                    content: "Friday";
                }
                td:nth-of-type(7):before{
                    content: "Saturday";
                }
            } */

            @media only screen and (min-device-width:320px) and (max-device-width: 480px){
                body{
                    padding:0;
                    margin:0;
                }
            }

            @media only screen and (min-device-width:802px) and (max-device-width: 1020px){
                body{
                    width: 495px;
                }
            }

            @media (min-width:641px){
                table {
                    table-layout: fixed;
                }
                /* td {
                    width: 33%;
                } */
                
            }

            .row{
                margin-top: 20px;
            }

            .today{
                background: yellow;
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <header class="header">
            <nav class="navbar fixed-top navbar-dark py-4">
                <div class="container">
                    <a class="navbar-brand" href="../../../../index.html"
                        ><strong>BRAND</strong></a
                    >
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#collapsingNavbar"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="collapsingNavbar">
                        <ul class="navbar-nav float-right">
                            <li class="nav-item">
                                <a
                                    class="nav-link float-right"
                                    href="../../../../index.html"
                                    >Home</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link float-right"
                                    href="../../../../index.html#events"
                                    >Bookings</a
                                >
                            </li>
                            <li class="nav-item">
                                <a class="nav-link float-right" href="#"
                                    >Contact</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Landing -->

        <div class="container-fluid football-calendar" >
            <div class="container">
                <div class="row align-items-center py-4 py-md-0">
                    <div class="col-md-12">
                        <?php
                            $dateComponents = getdate();
                            if(isset($_GET['month']) && isset($_GET['year'])){
                                $month = $_GET['month'];
                                $year = $_GET['year'];
                            }
                            else{
                                $month = $dateComponents['mon'];
                                $year = $dateComponents['year'];
                            }

                            echo build_calendar($month, $year);
                        ?>
					</div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script
            src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"
        ></script>
        <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"
        ></script>
        <script>
            $("#button").click(function () {
                $("html, body").animate(
                    {
                        scrollTop: $("#myDiv").offset().top,
                    },
                    2000
                );
            });
        </script>
    </body>
</html>
