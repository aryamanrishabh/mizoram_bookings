<?php
    $mysqli = new mysqli('localhost', 'root', '', 'football');
    if(isset($_GET['date'])){
        $date = $_GET['date'];
        $success = 'success';
        $stmt = $mysqli->prepare('select * from b2 where date=? AND payment=?');
        $stmt->bind_param('ss',$date,$success);
        $bookings=array();
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $bookings[] = $row['timeslot'];
                }
                $stmt->close();
            }
        }
    }

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $timeslot = $_POST['timeslot'];
        $id = $_POST['id'];
        $mysqli = new mysqli('localhost', 'root', '', 'football');
        $stmt = $mysqli->prepare('select * from b2 where date=? AND timeslot=?');
        $stmt->bind_param('ss',$date,$timeslot);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $msg = "<div class='alert alert-danger'>Already Booked</div>";
            }
            else{
                $stmt = $mysqli->prepare("INSERT INTO b2 (id, name, phone, date, timeslot) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss',$id, $name, $phone, $date, $timeslot);
                $stmt->execute();
                $msg = "<div class='alert alert-success'>Booking Successfull</div>";
                $bookings[] = $timeslot;
                $stmt->close();
                $mysqli->close();
            }
        }
        
    }

    $duration = 60;
    $cleanup = 0;
    $start = "09:00";
    $end = "15:00";

    function timeslots($duration, $cleanup, $start, $end){
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = new DateInterval("PT".$duration."M");
        $cleanupinterval = new DateInterval("PT".$cleanup."M");
        $slots = array();

        for($intStart=$start; $intStart<$end; $intStart->add($interval)->add($cleanupinterval)){
            $endPeriod = clone $intStart;
            $endPeriod->add($interval);
            if($endPeriod>$end){
                break;
            }

            $slots[] = $intStart->format("H:iA")."-".$endPeriod->format("H:iA");
        }

        return $slots;
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
        <link rel="stylesheet" media="screen and (min-device-width: 841px)"s href="../../../../css/style.css" />

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
                                    href="../../../index.html#events"
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

        <div class="container-fluid football-calendar" id="events">
            <div class="container">
				<h1 class="text-center">Book for <?php echo date('F d, Y',strtotime($date)); ?></h1>
                <div class="row text-center my-5">
                    
                    <?php 
                        $timeslots = timeslots($duration, $cleanup, $start, $end); 
                        foreach($timeslots as $ts){
                    ?>
                    <div class="col-md-4">
                        <div class="form-group timeslot">
                            <?php 
                                if(in_array($ts,$bookings)){
                            ?>
                                <button class="btn btn-danger" ><?php echo $ts; ?></button>
                            <?php }else{ ?>
                                <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                            <?php } ?>
                            
                        </div>
                    </div>   
                    <?php } ?>
                </div>
            </div>
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Booking <span id="slot"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="checkout_b2.php" method="post">
                                    <div class="form-group">
                                        <label for="">Timeslot</label>
                                        <input required type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input required type="text" name="name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input required type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input required type="text" name="email" class="form-control">
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo time(); ?>" />
                                    <input type="hidden" name="date" value="<?php echo $date; ?>" />
                                    <div class="form-group pull-right">
                                        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
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
        <script>
            $(".book").click(function(){
                var timeslot = $(this).attr('data-timeslot');
                $("#slot").html(timeslot);
                $("#timeslot").val(timeslot);
                $("#myModal").modal("show");
            })
        </script>
    </body>
</html>
