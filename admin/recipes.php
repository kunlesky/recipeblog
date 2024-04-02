<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/recipe_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/header.php'); ?>


<!-- Get all admin categories from DB -->
<?php $recipes = getAllRecipes(); ?>
        <title>Admin | Manage Recipe</title>
</head>
<body>
        <!-- admin navbar -->
        <?php include(ROOT_PATH . '/admin/includes/nav.php') ?>

        <div class="container content">
                <!-- Left side menu -->
                <?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

                <!-- Display records from DB-->
                <div class="table-div"  style="width: 80%;">
                        <!-- Display notification message -->
                        <?php include(ROOT_PATH . '/includes/messages.php') ?>

                        <?php if (empty($recipes)): ?>
                                <h1 style="text-align: center; margin-top: 20px;">No recipes in the database.</h1>
                        <?php else: ?>
                                <table class="table">
                                                <thead>
                                                <th>N</th>
                                                <th>Title</th>
                                                <th>Chef</th>
                                                <th>Views</th>
                                                <!-- Only Admin can publish/unpublish category -->
                                                <?php if ($_SESSION['user']['role'] == "Admin"): ?>
                                                        <th><small>Publish</small></th>
                                                <?php endif ?>
                                                <th><small>Edit</small></th>
                                                <th><small>Delete</small></th>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($recipes as $key => $recipe): ?>
                                                <tr>
                                                        <td><?php echo $key + 1; ?></td>
                                                        <td><?php echo $recipe['chef']; ?></td>
                                                        <td>
                                                                <a   target="_blank"
                                                                href="<?php echo BASE_URL . 'single_recipe.php?recipe-slug=' . $recipe['slug'] ?>">
                                                                        <?php echo $recipe['title']; ?>     
                                                                </a>
                                                        </td>
                                                        <td><?php echo $recipe['views']; ?></td>
                                                        
                                                        <!-- Only Admin can publish/unpublish category -->
                                                        <?php if ($_SESSION['user']['role'] == "Admin" ): ?>
                                                                <td>
                                                                <?php if ($recipe['published'] == true): ?>
                                                                        <a class="fa fa-check btn unpublish"
                                                                                href="recipes.php?unpublish=<?php echo $recipe['id'] ?>">
                                                                        </a>
                                                                <?php else: ?>
                                                                        <a class="fa fa-times btn publish"
                                                                                href="recipes.php?publish=<?php echo $recipe['id'] ?>">
                                                                        </a>
                                                                <?php endif ?>
                                                                </td>
                                                        <?php endif ?>

                                                        <td>
                                                                <a class="fa fa-pencil btn edit"
                                                                        href="create_recipe.php?edit-recipe=<?php echo $recipe['id'] ?>">
                                                                </a>
                                                        </td>
                                                        <td>
                                                                <a  class="fa fa-trash btn delete" 
                                                                        href="create_recipe.php?delete-recipe=<?php echo $recipe['id'] ?>">
                                                                </a>
                                                        </td>
                                                </tr>
                                        <?php endforeach ?>
                                        </tbody>
                                </table>
                        <?php endif ?>
                </div>
                <!-- // Display records from DB -->
        </div>
</body>
</html>