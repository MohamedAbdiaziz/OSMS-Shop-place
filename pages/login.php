
<?php

$title = "Login & Registration";
include_once('../db/session.php');
if(isset($_SESSION['customer'])){
    header("location: ./account.php");
}
 include_once("../hf/header.php"); 
    
?>


<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Account</h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Pages</a>
                <span class="breadcrumb-item active" aria-current="page">Account</span>
            </nav>
        </div>
    </div>
</section>

<section class="login-tabs padding-large">
    <div class="container my-5 py-5">
        <?php
            if (isset($_SESSION['success'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }
        ?>
        <div class="row">
            <div class="tabs-listing">
                <nav>
                    <div class="nav nav-tabs d-flex justify-content-center border-dark-subtle mb-3" id="nav-tab" role="tablist">
                        <button class="nav-link mx-3 fs-3 border-bottom border-dark-subtle border-0 text-uppercase" id="nav-sign-in-tab" data-bs-toggle="tab" data-bs-target="#nav-sign-in" type="button" role="tab" aria-controls="nav-sign-in" aria-selected="false" tabindex="-1">Log In</button>
                        <button class="nav-link mx-3 fs-3 border-bottom border-dark-subtle border-0 text-uppercase active" id="nav-register-tab" data-bs-toggle="tab" data-bs-target="#nav-register" type="button" role="tab" aria-controls="nav-register" aria-selected="true">Sign Up</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade" id="nav-sign-in" role="tabpanel" aria-labelledby="nav-sign-in-tab">
                        <div class="col-lg-8 offset-lg-2 mt-5">
                            <p class="mb-0">Log-In With Username</p>
                            <hr class="my-1">
                            <form class="form-group flex-wrap" method="POST" action="../backend/process_login.php">
                                <div class="form-input col-lg-12 my-4">
                                    <input type="text" id="loginUsername" name="username" placeholder="Enter Username" class="form-control mb-3 p-4" required>
                                    <input type="password" id="loginPassword" name="password" placeholder="Enter Password" class="form-control mb-3 p-4" required>
                                    <label class="py-3 d-flex flex-wrap justify-content-between">
                                        <div id="passwordHelpBlock" class="form-text">
                                            <a href="forgetpassword.php" class="text-primary fw-bold"> Forgot Password?</a>
                                        </div>
                                    </label>
                                    <div class="d-grid my-3">
                                        <button type="submit" name="Login" class="btn btn-dark btn-lg rounded-1">Log In</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade active show" id="nav-register" role="tabpanel" aria-labelledby="nav-register-tab">
                        <div class="col-lg-8 offset-lg-2 mt-5">
                            <p class="mb-0">Sign-Up With Email</p>
                            <hr class="my-1">
                            <form id="registerForm" class="form-group flex-wrap" method="POST" action="../backend/process_register.php">
                                <div class="form-input col-lg-12 my-4">
                                    <input type="text" id="registerName" name="name" placeholder="Your Full Name" class="form-control mb-3 p-4" required>
                                    <input type="text" id="registerUsername" name="username" placeholder="Your Username" class="form-control mb-3 p-4" required>
                                    <input type="email" id="registerEmail" name="email" placeholder="Your Email Address" class="form-control mb-3 p-4" required>
                                    <input type="password" id="registerPassword1" name="password" placeholder="Set Your Password" class="form-control mb-3 p-4" required>
                                    <input type="password" id="registerPassword2" name="confirm_password" placeholder="Retype Your Password" class="form-control mb-3 p-4" required>
                                    <input type="number" onkeydown="javascript: return event.keyCode == 69 ? false : true" id="registerMobile" name="mobile" placeholder="Your Mobile Number" class="form-control mb-3 p-4" required>
                                    <input type="text" id="registerAddress" name="address" placeholder="Your Address" class="form-control mb-3 p-4" required>
                                    <div class="d-grid my-3">
                                        <button type="submit" class="btn btn-dark btn-lg rounded-1">Sign Up</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>
