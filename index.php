<?php require_once('config.php') ?>
<?php require_once( ROOT_PATH . '/includes/registration_login.php') ?>
<?php require_once( ROOT_PATH . '/includes/public_functions.php') ?>
<?php require_once(ROOT_PATH .'/includes/header.php') ?>
<?php $recipes = getPublishedRecipes(); ?>

        <title>Recipe Blog | Home </title>
</head>
<body>
        <!-- container - wraps whole page -->
        <div class="container">
                <!-- navbar -->
                <?php include(ROOT_PATH .'/includes/nav.php') ?>
                <?php include(ROOT_PATH .'/includes/banner.php') ?>

                <!-- Page content -->
                <div class="content">
                        <h2 class="content-title">Our Recipes</h2>
                        <hr>
                        
                        <!-- more content still to come here ... -->
                        <?php foreach ($recipes as $recipe): ?>
        <div class="post" style="margin-left: 0px;">
                <img src="<?php echo BASE_URL . '/static/images/' . $recipe['image']; ?>" class="post_image" alt="">
        <!-- Added this if statement... -->
                <?php if (isset($recipe['category']['name'])): ?>
                        
                        <a 
                href=" filtered_recipes.php?category=' <?php echo $recipe['category']['id'] ?>"
                                class="btn category">
                                <?php echo $recipe['category']['name'] ?>
                        </a>
                <?php endif ?>
                
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
                <!-- // Page content -->

                <!-- footer -->
                <?php include(ROOT_PATH .'/includes/footer.php') ?>