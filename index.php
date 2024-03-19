<?php require_once('config.php') ?>
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
                        <?php foreach ($recipes as $recipes): ?>
        <div class="post" style="margin-left: 0px;">
                <img src="<?php echo BASE_URL . '/static/images/' . $recipes['image']; ?>" class="post_image" alt="">
                <a href="single_post.php?post-slug=<?php echo $recipes['slug']; ?>">
                        <div class="post_info">
                                <h3><?php echo $recipes['title'] ?></h3>
                                <div class="info">
                                        <span><?php echo date("F j, Y ", strtotime($recipes["created_at"])); ?></span>
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