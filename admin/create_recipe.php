<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/recipe_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/header.php'); ?>
<!-- Get all topics -->
<?php $categories = getAllCategory();      ?>
        <title>Admin | Create Recipe</title>
</head>
<body>
        <!-- admin navbar -->
        <?php include(ROOT_PATH . '/admin/includes/nav.php') ?>

        <div class="container content">
                <!-- Left side menu -->
                <?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

                <!-- Middle form - to create and edit  -->
                <div class="action create-post-div">
                        <h1 class="page-title">Create/Edit Recipe</h1>
                        <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_recipe.php'; ?>" >
                                <!-- validation errors for the form -->
                                <?php include(ROOT_PATH . '/includes/errors.php') ?>

                                <!-- if editing recipe, the id is required to identify that recipe -->
                                <?php if ($isEditingRecipe === true): ?>
                                        <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                                <?php endif ?>

                                <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Title">
                                <label style="float: left; margin: 5px auto 5px;">Featured image</label>
                                <input type="file" name="featured_image" >
                                <textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
                                <select name="cateogory_id">
                                        <option value="" selected disabled>Choose category</option>
                                        <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>">
                                                        <?php echo $category['name']; ?>
                                                </option>
                                        <?php endforeach ?>
                                </select>
                                
                                <!-- Only admin users can view publish input field -->
                                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                                        <!-- display checkbox according to whether recipe has been published or not -->
                                        <?php if ($published == true): ?>
                                                <label for="publish">
                                                        Publish
                                                        <input type="checkbox" value="1" name="publish" checked="checked">&nbsp;
                                                </label>
                                        <?php else: ?>
                                                <label for="publish">
                                                        Publish
                                                        <input type="checkbox" value="1" name="publish">&nbsp;
                                                </label>
                                        <?php endif ?>
                                <?php endif ?>
                                
                                <!-- if editing recipe, display the update button instead of create button -->
                                <?php if ($isEditingRecipe === true): ?> 
                                        <button type="submit" class="btn" name="update_recipe">UPDATE</button>
                                <?php else: ?>
                                        <button type="submit" class="btn" name="create_recipe">Save Recipe</button>
                                <?php endif ?>

                        </form>
                </div>
                <!-- // Middle form - to create and edit -->
        </div>
</body>
</html>

<script>
        CKEDITOR.replace('body'); //I need to refere ce CDEditor in my report
</script>