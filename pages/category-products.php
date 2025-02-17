<?php 
$title = "Category Products";
include_once('../db/session.php');
include_once("../hf/header.php"); ?>
<?php require_once("../classes/workshop.class.php");?>
<?php require_once("../classes/cart.class.php");?>

<?php
require_once("../classes/workshop.class.php");
$objProduct = new workshop();

if (isset($_GET['id'])) {
    $category = $_GET['category'];
    $category_id = intval($_GET['id']);
    $objProduct->setCId($category_id);
    $products = $objProduct->getProductsByCategory();
} else {
    echo "<h1>No category ID provided</h1>";
    exit();
}
?>

<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Products in <span class="text-primary"><?= $category; ?></span></h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Shop</a>
                <a class="breadcrumb-item nav-link" href="category.php">Categories</a>
                <span class="breadcrumb-item active" aria-current="page">Products</span>
            </nav>
        </div>
    </div>
</section>

<section id="products">
    <div class="container my-md-5 py-5">
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border">
                            <img class="card-img-top" src="../images/Category/<?php echo $product['Image']; ?>" alt="<?php echo $product['ProductName']; ?>">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="single-product.php?id=<?php echo $product['ID']; ?>"><?php echo $product['ProductName']; ?></a>
                                </h4>
                                <h5>$<?php echo $product['Price']; ?></h5>
                                <!-- <p class="card-text"><?php echo $product['Description']; ?></p> -->
                                <?php if(isset($_SESSION['customer'])){?>
                                    <div class="d-flex flex-wrap pt-4">
                                        <?php
                                        
                                        $objCart = new cart();
                                        $objCart->setCid($_SESSION['customer']);
                                          $cartItems = $objCart->getAllCartItems();
                                          $cartProductIds = array_column($cartItems, 'Product');
                                          $disabled = "";
                                          if (in_array($product['ID'], $cartProductIds)) {
                                              $disabled = "disabled";
                                          }
                                        ?>
                                        <button id="cartBtn_<?= $product['ID'];?>" role="button" class="btn-cart me-3 px-4 pt-3 pb-3" onclick="addToCart(<?= $_GET['id'];?>,this.id);" <?php echo $disabled;?>>
                                          <h5 class="text-uppercase m-0" >Add to Cart</h5>
                                        </button><!-- 
                                        <a href="#" class="btn-wishlist px-4 pt-3">
                                            <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                                        </a> -->
                                    </div>
                                <?php } else{?>
                                    <a href="login.php" class="btn me-3 px-4 pt-3 pb-3 bg-info">
                                          <h5 class="text-uppercase m-0" >Add to Cart</h5>
                                        </a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>
