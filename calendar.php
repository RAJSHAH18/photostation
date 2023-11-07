<?php include('connect.php'); ?>
<?php
session_start();

session_start();
if (!isset($_SESSION['usertype'])) {
    header("Location: login.php");
}

if (!isset($_GET['creatorid']) && !isset($_POST['creatorid'])) {
    header("Location: index.php");
}

$creatorid = $_GET['creatorid'];
$userid = $_SESSION['userid'];
$usertype = $_SESSION['usertype'];

if (isset($_POST["submit"])) {
    $shouldSubmit = true;
    extract($_POST);

    // $crequery = "SELECT SUM(rate) AS total FROM creatorservice WHERE serviceid IN ($placeholders) AND creatorid = $creatorid";
    $filtered = array_filter(array_keys($_POST), function ($key) {
        return strpos($key, 'service') !== false;
    });
    $services = array_values(array_intersect_key($_POST, array_flip($filtered)));

    if(count($services) <= 0)
    {
        echo "<script> alert('Please select atleast one service.'); </script>";
        $shouldSubmit = false;
    }
    $checksql = "SELECT * FROM booking WHERE status = 'approved' AND creatorid = $creatorid AND ((startdate BETWEEN '$startdate' AND '$enddate') OR (enddate BETWEEN '$startdate' AND '$enddate') OR ('$startdate' >= startdate AND '$enddate' <= enddate))";
    $checkres = mysqli_query($conn, $checksql);
    $checkresults = mysqli_fetch_all($checkres, MYSQLI_ASSOC);

    if (count($checkresults) > 0) {
        echo "<script> alert('There is already an event booked for provided dates.'); </script>";
        $shouldSubmit = false;
    }

    if($shouldSubmit) {
        
        $placeholders = implode(',', $services);

        $crequery = "SELECT SUM(rate) AS total FROM creatorservice WHERE serviceid IN ($placeholders) AND creatorid = $creatorid";
        $flres = mysqli_query($conn, $crequery);
        $row = mysqli_fetch_array($flres, MYSQLI_ASSOC);
        // $row = mysqli_fetch_assoc($result);

        // $res = mysqli_query($conn, $crequery);

        $startd = new DateTime($startdate);
        $endd = new DateTime($enddate);
        $interval = $startd->diff($endd);
        $total_days = $interval->format("%a");
        ;
        $total_cost = $row['total'] * $total_days;



        $sql = "INSERT INTO `booking` (`creatorid`,`userid`,`startdate`,`enddate`, `amount`) VALUES ($creatorid,$userid,'$startdate','$enddate', $total_cost)";

        $save = $conn->query($sql);
        if ($save) {
            $bookingid = mysqli_insert_id($conn);



            foreach ($services as $serviceid) {
                $sql = "INSERT INTO `bookingservice` (`bookingid`,`serviceid`) VALUES ($bookingid,$serviceid)";

                $save = $conn->query($sql);
            }

            echo "<script> alert('Event request send successfully.'); </script>";
            header("Location: order.php");
        } else {
            echo "<pre>";
            echo "An Error occured.<br>";
            echo "Error: " . $conn->error . "<br>";
            echo "SQL: " . $sql . "<br>";
            echo "</pre>";
        }
    }


}

$message = "";

$udsql = "SELECT * FROM `creatorservice` as cs
                JOIN `service` as s ON cs.serviceid = s.serviceid
                WHERE `creatorid` = $creatorid;";

$res = mysqli_query($conn, $udsql);
$creatorservices = mysqli_fetch_all($res, MYSQLI_ASSOC);


$fludsql = "SELECT * FROM `creator` WHERE `creatorid` = $creatorid";
$flres = mysqli_query($conn, $fludsql);

$creator = mysqli_fetch_array($flres, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<?php include_once("head.php"); ?>


<body class="bg-light">

    <?php include_once("header.php"); ?>

    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Booking Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="calendar.php?creatorid=<?php echo $creator["creatorid"]; ?>" method="post"
                                id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <!-- <input type="hidden" name="creatorid" value="<?php echo $creator["creatorid"]; ?>">
                            <input type="hidden" name="userid" value="<?php echo $creator["userid"]; ?>"> -->

                                <div class="form-group mb-2">
                                    <h4 for="title" class="control-label ">
                                        <strong>
                                            <?php echo $creator["name"]; ?>
                                        </strong>
                                    </h4>
                                </div>
                                <div>
                                    <?php foreach ($creatorservices as $service): ?>
                                        <input type="checkbox" id="service"
                                            name="service-<?php echo $service["serviceid"]; ?>"
                                            value="<?php echo $service["serviceid"]; ?>">
                                        <label for="vehicle1">
                                            <?php echo $service["name"]; ?>
                                        </label><br>
                                        <label for="vehicle1"><small>Rate - $
                                                <?php echo $service["rate"]; ?> / day
                                            </small></label><br>
                                    <?php endforeach; ?>
                                </div>
                                <br /><br />
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Start</label>
                                    <input type="datetime-local" id="cal_user_sdate"
                                        class="form-control form-control-sm rounded-0" name="startdate"
                                        id="start_datetime" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">End</label>
                                    <input type="datetime-local" id="cal_user_edate"
                                        class="form-control form-control-sm rounded-0" name="enddate" id="end_datetime"
                                        required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if ($usertype == "customer"): ?>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" name="submit"
                                form="schedule-form"><i class="fa fa-save"></i> Save</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i
                                    class="fa fa-reset"></i> Cancel</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Name</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Start</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">End</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <!-- <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit"
                            data-id="">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete"
                            data-id="">Delete</button> -->
                        <button type="button" class="btn btn-secondary btn-sm rounded-0"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->

    <?php
    $sqlquery = "SELECT * FROM `booking` as b JOIN `user` as u ON b.userid = u.userid WHERE b.status = 'approved' AND b.creatorid = $creatorid ";
    $res = mysqli_query($conn, $sqlquery);
    $bookings = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $sched_res = []; foreach ($bookings as $row) {
        $row['sdate'] = date("F d, Y h:i A", strtotime($row['startdate']));
        $row['edate'] = date("F d, Y h:i A", strtotime($row['enddate']));
        $row['id'] = $row['bookingid'];
        $sched_res[$row['id']] = $row;
    }
    ?>
    <?php
    if (isset($conn))
        $conn->close();
    ?>
</body>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')


    var datepicker = document.getElementById("cal_user_sdate");
    datepicker.addEventListener("change", function () {
        var today = new Date();
        var selectedDate = new Date(datepicker.value);
        if (selectedDate < today) {
            alert("You can't select a past date!");
            datepicker.value = "";
        }
    });

    var enddatepicker = document.getElementById("cal_user_edate");
    enddatepicker.addEventListener("change", function () {
        var startdatep = new Date(datepicker.value);
        var selectedDate = new Date(enddatepicker.value);
        if (selectedDate <= startdatep || datepicker.value == "") {
            alert("You can't select older date than start date!");
            enddatepicker.value = "";
        }
    });
</script>
<script src="./js/script.js"></script>

</html>