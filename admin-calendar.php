<?php include('connect.php'); ?>
<?php
session_start();
$message = "";

$userid = "";
$userid = $_SESSION['userid'];
$email = $_SESSION['email'];
$usertype = $_SESSION['usertype'];

$udsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'photographer' LIMIT 3";

$res = mysqli_query($conn, $udsql);
$photographers = mysqli_fetch_all($res, MYSQLI_ASSOC);


$fludsql = "SELECT * FROM `creator` as c JOIN `user` as u ON c.userid = u.userid WHERE `creatortype` = 'videographer' LIMIT 3";
$flres = mysqli_query($conn, $fludsql);
$videographers = mysqli_fetch_all($flres, MYSQLI_ASSOC);


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
            <div class="col-md-12">
                <div id="calendar"></div>
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
                            <dt class="text-muted">Title</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Description</dt>
                            <dd id="description" class=""></dd>
                            <dt class="text-muted">Start</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">End</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                       
                        <button type="button" class="btn btn-secondary btn-sm rounded-0"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->

    <?php
    $schedules = $conn->query("SELECT *, c.name as username FROM `booking` as b JOIN `creator` as c ON b.creatorid = c.creatorid WHERE b.status = 'approved'");

    // $schedules = $conn->query("SELECT * FROM `booking`");
    $sched_res = [];
    foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
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
</script>
<script src="./js/script.js"></script>

</html>