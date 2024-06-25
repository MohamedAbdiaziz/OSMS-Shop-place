<?php
$title = "Change Password";
include_once('../db/session.php');

if (!isset($_SESSION['customer'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}
?>

<?php include_once("../hf/header.php"); ?>

<section id="change-password" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Change Password</h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Account</a>
                <span class="breadcrumb-item active" aria-current="page">Change Password</span>
            </nav>
        </div>
    </div>
</section>

<section class="change-password-form padding-large">
    <div class="container my-5 py-5">
        <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
        ?>
        <div class="row">

            <div class="col-lg-8 offset-lg-2">
                <form action="../backend/action.php" method="POST">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-dark">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>
