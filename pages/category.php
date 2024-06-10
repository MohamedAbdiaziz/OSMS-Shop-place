<?php include_once("../hf/header.php"); ?>


<?php
require_once "../classes/category.class.php";
$objcategories = new category();
$categories = $objcategories->getCategories();
?>

<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0">Shop by <span class="text-primary">Category</span></h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="#">Shop</a>
                <span class="breadcrumb-item active" aria-current="page">Categories</span>
            </nav>
        </div>
    </div>
</section>

<section id="categories">
    <div class="container my-md-5 py-5">
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="../images/Category/<?php echo htmlspecialchars($category['Image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($category['name']); ?>">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="category-products.php?id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
                                </h4>
                                <p class="card-text"><?php echo $category['description']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No categories found.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include_once("../hf/footer.php"); ?>
