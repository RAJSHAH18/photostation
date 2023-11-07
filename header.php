<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$useridvar = "";
@$useridvar = $_SESSION['userid'];
@$usertypevar = $_SESSION['usertype'];

?>
<header>
    <div>
        <nav>
            <ul class="menu">
                <a href="index.php"
                    style="color:black; border: 1px solid black; padding: 10px 20px; background-color: white;">
                    The Photo Station
                </a>
                <?php if ($usertypevar != "admin"): ?>
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="photo.php">Photographer</a>
                </li>
                <li>
                    <a href="video.php">Videographer</a>
                </li>
                <?php if ($usertypevar == "customer"): ?>

                    <li>
                        <a href="payment.php">Invoice</a>
                    </li>
                    <li>
                        <a href="order.php">Order</a>
                    </li>
                <?php endif; ?>

                <?php if ($useridvar == ""): ?>
                    <li>
                        <a href="register.php">Register</a>
                    </li>
                    <li>
                        <a href="register-creator.php">Register As Creator</a>
                    </li>
                <?php endif; ?>

                <?php if ($usertypevar == "admin"): ?>
                    <li>
                        <a href="admin-photo.php">Photographer Admin</a>
                    </li>
                    <li>
                        <a href="admin-video.php">Videographer Admin</a>
                    </li>
                    <li>
                        <a href="admin-invoices.php">Invoice</a>
                    </li>
                    <li>
                        <a href="admin-calendar.php">Calendar</a>
                    </li>

                <?php endif; ?>

                <?php if ($usertypevar == "creator"): ?>
                    <li>
                        <a href="creator-service.php">Services</a>
                    </li>
                    <li>
                        <a href="creator-order.php">Orders</a>
                    </li>
                    <li>
                        <a href="creator-payment.php">Invoice</a>
                    </li>


                <?php endif; ?>


                <li>
                    <?php if ($useridvar == ""): ?>
                        <a href="login.php">Login</a>
                    <?php else: ?>
                        <a href="login.php">Logout</a>
                    <?php endif; ?>
                </li>

                <li>
                        <a href="faq.php">FAQ</a>
                    </li>
            </ul>
        </nav>
    </div>
</header>