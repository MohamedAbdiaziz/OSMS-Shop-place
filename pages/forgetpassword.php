<?php
$title = "Forget Password";
include_once('../db/session.php');

include_once("../hf/header.php");
require_once("../db/DbConnect.php");
require '../vendor/autoload.php'; // Autoload PHPMailer
// session_start();



?>

<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Forgot Password</h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Pages</a>
                <span class="breadcrumb-item active" aria-current="page">Forgot Password</span>
            </nav>
        </div>
    </div>
</section>

<section class="forgot-password padding-large">
    <div class="container my-5 py-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 mt-5">
                <p class="mb-0">Enter your email address to receive a password reset link.</p>
                <hr class="my-1">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                    unset($_SESSION['success']);
                }
                ?>
                <form id="forgotPasswordForm" class="form-group flex-wrap" method="POST" action="../backend/process_sendlink.php">
                    <div class="form-input col-lg-12 my-4">
                        <input type="email" id="email" name="email" placeholder="Enter your email address" class="form-control mb-3 p-4" required>
                        <div class="d-grid my-3">
                            <button type="submit" name="sendlink" class="btn btn-dark btn-lg rounded-1">Send Reset Link</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>




