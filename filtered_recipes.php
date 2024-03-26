<?php include('config.php'); ?>
<?php include('includes/public_functions.php'); ?>
<?php include('includes/header.php'); ?>
<?php 
        // Get recipes under a particular category
        if (isset($_GET['category'])) {
                $category_id = $_GET['category'];
                $recipe = getPublishedRecipesByCategory($category_id);
        }
?>
        <title>Recipes | Home </title>
</head>
<body>
<div class="container">
<!-- Navbar -->
        <?php include( ROOT_PATH . '/includes/nav.php'); ?>
<!-- // Navbar -->
<!-- content -->
<div class="content">
        <h2 class="content-title">
                Recipes on <u><?php echo getCategoryNameById($category_id); ?></u>
        </h2>
        <hr>
        <?php foreach ($recipes as $recipe): ?>
                <div class="post" style="margin-left: 0px;">
                        <img src="<?php echo BASE_URL . '/static/images/' . $recipe['image']; ?>" class="post_image" alt="">
                        <a href="single_recipe.php?recipe-slug=<?php echo $recipe['slug']; ?>">
                                <div class="post_info">
                                        <h3><?php echo $recipe['title'] ?></h3>
                                        <div class="info">
                                                <span><?php echo date("F j, Y ", strtotime($recipe["created_at"])); ?></span>
                                                <span class="read_more">Read more...</span>
                                        </div>
                                </div>
                        </a>
                </div>
        <?php endforeach ?>
</div>
<!-- // content -->
</div>
<!-- // container -->

<!-- Footer -->
        <?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Footer -->