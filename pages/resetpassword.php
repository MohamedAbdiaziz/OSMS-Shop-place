<?php
$title = "Reset Password";
include_once('../db/session.php');

include_once("../hf/header.php");
require_once("../db/DbConnect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        try {
            $db = new DbConnect();
            $dbConn = $db->connect();

            $sql = "SELECT * FROM password_reset WHERE token = :token AND expires >= :current_time";
            $stmt = $dbConn->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':current_time', time());
            $stmt->execute();
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reset) {
                $email = $reset['email'];
                $hashedPassword = md5($newPassword);

                $sql = "UPDATE tblcustomer SET Password = :password WHERE Email = :email";
                $stmt = $dbConn->prepare($sql);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':email', $email);

                if ($stmt->execute()) {
                    $sql = "DELETE FROM password_reset WHERE email = :email";
                    $stmt = $dbConn->prepare($sql);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    $_SESSION['success'] = "Password has been reset successfully.";
                    echo "<script>window.location.href = 'login.php';</script>";
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to reset password.";
                }
            } else {
                $_SESSION['error'] = "Invalid or expired token.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Reset Password</h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Pages</a>
                <span class="breadcrumb-item active" aria-current="page">Reset Password</span>
            </nav>
        </div>
    </div>
</section>

<section class="reset-password padding-large">
    <div class="container my-5 py-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 mt-5">
                <p class="mb-0">Enter your new password.</p>
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
                <form id="resetPasswordForm" class="form-group flex-wrap" method="POST" action="">
                    <div class="form-input col-lg-12 my-4">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <input type="password" id="newPassword" name="new_password" placeholder="New Password" class="form-control mb-3 p-4" required>
                        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm New Password" class="form-control mb-3 p-4" required>
                        <div class="d-grid my-3">
                            <button type="submit" class="btn btn-dark btn-lg rounded-1">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>
