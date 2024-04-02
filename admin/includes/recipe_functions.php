<?php 
// Post variables
$recipe_id = 0;
$isEditingRecipe = false;
$published = 0;
$title = "";
$recipe_slug = "";
$body = "";
$featured_image = "";
$recipe_category = "";

/* - - - - - - - - - - 
-  recipe functions
- - - - - - - - - - -*/
// get all recipes from DB
function getAllRecipes()
{
        global $conn;
        
        // Admin can view all recipes
        // Chef can only view their reicpes
        if ($_SESSION['user']['role'] == "Admin") {
                $sql = "SELECT * FROM recipe";
        } elseif ($_SESSION['user']['role'] == "Chef") {
                $user_id = $_SESSION['user']['id'];
                $sql = "SELECT * FROM recipe WHERE user_id=$user_id";
        }
        $result = mysqli_query($conn, $sql);
        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $final_recipes = array();
        foreach ($recipes as $recipe) {
                $recipe['chef'] = getRecipeChefById($recipe['user_id']);
                array_push($final_recipes, $recipe);
        }
        return $final_recipes;
}
// get the author/username of a post
function getRecipeChefById($user_id)
{
        global $conn;
        $sql = "SELECT username FROM users WHERE id=$user_id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
                // return username
                return mysqli_fetch_assoc($result)['username'];
        } else {
                return null;
        }
}

/* - - - - - - - - - - 
-  Post actions
- - - - - - - - - - -*/
// if user clicks the create post button
if (isset($_POST['create_recipe'])) { createRecipe($_POST); }
// if user clicks the Edit post button
if (isset($_GET['edit-recipe'])) {
        $isEditingRecipe = true;
        $recipe_id = $_GET['edit-recipe'];
        editPost($recipe_id);
}
// if user clicks the update post button
if (isset($_POST['update_recipe'])) {
        updateRecipe($_POST);
}
// if user clicks the Delete post button
if (isset($_GET['delete-recipe'])) {
        $recipe_id = $_GET['delete-recipe'];
        deleteRecipe($recipe_id);
}

/* - - - - - - - - - - 
-  Post functions
- - - - - - - - - - -*/
function createRecipe($request_values)
        {
                global $conn, $errors, $title, $featured_image, $category_id, $body, $published;
                $title = esc($request_values['title']);
                $body = htmlentities(esc($request_values['body']));
                if (isset($request_values['category_id'])) {
                        $category_id = esc($request_values['category_id']);
                }
                if (isset($request_values['publish'])) {
                        $published = esc($request_values['publish']);
                }
                // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
                $recipe_slug = makeSlug($title);
                // validate form
                if (empty($title)) { array_push($errors, "Recipe title is required"); }
                if (empty($body)) { array_push($errors, "Recipe body is required"); }
                if (empty($topic_id)) { array_push($errors, "Recipe topic is required"); }
                // Get image name
                $featured_image = $_FILES['featured_image']['name'];
                if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
                // image file directory
                $target = "../static/images/" . basename($featured_image);
                if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
                        array_push($errors, "Failed to upload image. Please check file settings for your server");
                }
                // Ensure that no recipe is saved twice. 
                $recipe_check_query = "SELECT * FROM recipe WHERE slug='$recipe_slug' LIMIT 1";
                $result = mysqli_query($conn, $recipe_check_query);

                if (mysqli_num_rows($result) > 0) { // if recipe exists
                        array_push($errors, "A recipe already exists with that title.");
                }
                // create recipe if there are no errors in the form
                if (count($errors) == 0) {
                        $query = "INSERT INTO recipe (user_id, title, slug, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
                        if(mysqli_query($conn, $query)){ // if recipe created successfully
                                $inserted_recipe_id = mysqli_insert_id($conn);
                                // create relationship between recipe and category
                                $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES($inserted_recipe_id, $category_id)";
                                mysqli_query($conn, $sql);

                                $_SESSION['message'] = "Recipe created successfully";
                                header('location: recipes.php');
                                exit(0);
                        }
                }
        }

        /* * * * * * * * * * * * * * * * * * * * *
        * - Takes recipe id as parameter
        * - Fetches the recipe from database
        * - sets recipe fields on form for editing
        * * * * * * * * * * * * * * * * * * * * * */
        function editRecipe($role_id)
        {
                global $conn, $title, $recipe_slug, $body, $published, $isEditingRecipe, $recipe_id;
                $sql = "SELECT * FROM recipe WHERE id=$role_id LIMIT 1";
                $result = mysqli_query($conn, $sql);
                $recipe = mysqli_fetch_assoc($result);
                // set form values on the form to be updated
                $title = $recipe['title'];
                $body = $recipe['body'];
                $published = $recipe['published'];
        }

        function updateRecipe($request_values)
        {
                global $conn, $errors, $recipe_id, $title, $featured_image, $categort_id, $body, $published;

                $title = esc($request_values['title']);
                $body = esc($request_values['body']);
                $recipe_id = esc($request_values['recipe_id']);
                if (isset($request_values['categort_id'])) {
                        $category_id = esc($request_values['category_id']);
                }
                // create slug: if title is "The Storm Is Over", return "the-storm-is-over" as slug
                $post_slug = makeSlug($title);

                if (empty($title)) { array_push($errors, "Recipe title is required"); }
                if (empty($body)) { array_push($errors, "Recipe body is required"); }
                // if new featured image has been provided
                if (isset($_POST['featured_image'])) {
                        // Get image name
                        $featured_image = $_FILES['featured_image']['name'];
                        // image file directory
                        $target = "../static/images/" . basename($featured_image);
                        if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
                                array_push($errors, "Failed to upload image. Please check file settings for your server");
                        }
                }

                // register category if there are no errors in the form
                if (count($errors) == 0) {
                        $query = "UPDATE recipe SET title='$title', slug='$recipe_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$recipe_id";
                        // attach topic to post on recipe_category table
                        if(mysqli_query($conn, $query)){ // if recipe created successfully
                                if (isset($category_id)) {
                                        $inserted_recipe_id = mysqli_insert_id($conn);
                                        // create relationship between recipe and recipe
                                        $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES($inserted_recipe_id, $category_id)";
                                        mysqli_query($conn, $sql);
                                        $_SESSION['message'] = "Recipe created successfully";
                                        header('location: recipes.php');
                                        exit(0);
                                }
                        }
                        $_SESSION['message'] = "Recipe updated successfully";
                        header('location: recipes.php');
                        exit(0);
                }
        }
        // delete recipe 
        function deleteRecipe($recipe_id)
        {
                global $conn;
                $sql = "DELETE FROM recipe WHERE id=$recipe_id";
                if (mysqli_query($conn, $sql)) {
                        $_SESSION['message'] = "Recipe successfully deleted";
                        header("location: recipes.php");
                        exit(0);
                }
        }


// if user clicks the publish post button
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
        $message = "";
        if (isset($_GET['publish'])) {
                $message = "Recipe published successfully";
                $post_id = $_GET['publish'];
        } else if (isset($_GET['unpublish'])) {
                $message = "Recipe successfully unpublished";
                $post_id = $_GET['unpublish'];
        }
        togglePublishRecipe($recipe_id, $message);
}
// delete blog post
function togglePublishRecipe($recipe_id, $message)
{
        global $conn;
        $sql = "UPDATE recipe SET published=!published WHERE id=$recipe_id";
        
        if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = $message;
                header("location: recipes.php");
                exit(0);
        }
}


?>