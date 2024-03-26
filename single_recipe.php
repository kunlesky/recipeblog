<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php 
        if (isset($_GET['post-slug'])) {
                $recipe = getRecipe($_GET['recipe_slug']);
        }
        $topics = getAllTopics();
?>
<?php include('includes/header.php'); ?>
<title> <?php echo $recipe['title'] ?> | Recipe</title>
</head>
<body>
<div class="container">
        <!-- Navbar -->
                <?php include( ROOT_PATH . '/includes/nav.php'); ?>
        <!-- // Navbar -->
        
        <div class="content" >
                <!-- Page wrapper -->
                <div class="post-wrapper">
                        <!-- full post div -->
                        <div class="full-post-div">
                        <?php if ($recipe['published'] == false): ?>
                                <h2 class="post-title">Sorry... This post has not been published</h2>
                        <?php else: ?>
                                <h2 class="post-title"><?php echo $recipe['title']; ?></h2>
                                <div class="post-body-div">
                                        <?php echo html_entity_decode($recipe['body']); ?>
                                </div>
                        <?php endif ?>
                        </div>
                        <!-- // full post div -->
                        
                        <!-- comments section -->
                        <!--  coming soon ...  -->
                </div>
                <!-- // Page wrapper -->

                <!-- post sidebar -->
                <div class="post-sidebar">
                        <div class="card">
                                <div class="card-header">
                                        <h2>Topics</h2>
                                </div>
                                <div class="card-content">
                                        <?php foreach ($topics as $topic): ?>
                                                <a 
                                                        href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
                                                        <?php echo $topic['name']; ?>
                                                </a> 
                                        <?php endforeach ?>
                                </div>
                        </div>
                </div>
                <!-- // post sidebar -->
        </div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>