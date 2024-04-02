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


?>