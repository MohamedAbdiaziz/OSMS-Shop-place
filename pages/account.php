<?php 
// session_start();

include_once('../db/session.php');

include_once("../hf/header.php");
require_once("../classes/customer.class.php");
require_once("../classes/order.class.php");

// $_SESSION['customerID'] = "Yussuf488";

if (!isset($_SESSION['customer'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}



$customerID = $_SESSION['customer'];

$objCustomer = new Customer();
$objOrder = new Order();
$objCustomer->setUsername($customerID);
$customer = $objCustomer->getCustomerById();

$objOrder->setCustomer($customerID);
$orders = $objOrder->getOrderById();
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

<section class="account-info padding-large">
    <div class="container my-5 py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if($customer['profile_image'] != ""){?>
                            <img src="../images/Category/<?= $customer['profile_image'] ?>" alt="Profile Image" class="img-thumbnail mb-3">
                        <?php }?>
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td><?= $customer['Name'] ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $customer['Email'] ?></td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td><?= $customer['Username'] ?></td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td><?= $customer['Mobile'] ?></td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td><?= $customer['Address'] ?></td>
                            </tr>
                            <tr>
                                <th>Change Password</th>
                                <td><a href="./change_password.php" class="btn btn-info">Change</a></td>
                            </tr>
                            <tr>
                                
                                <td colspan="2"><a href="./logout.php" class="btn btn-danger">Logout</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Your Orders</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order['ID'] ?></td>
                                    <td><?= $order['Order_Date'] ?></td>
                                    <td><?= $order['Status'] ?></td>
                                    <td>$<?= $order['Total_Amount'] ?></td>
                                    <td><a href="../../osmsadmin/page/generate_invoice.php?order_id=<?= $order['ID'] ?>" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>



