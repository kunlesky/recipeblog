<?php 

function getPublishedRecipes() {
        // use global $conn object in function
        global $conn;
        $sql = "SELECT * FROM recipe WHERE published=true";
        $result = mysqli_query($conn, $sql);
        // fetch all recipes as an associative array called $recipes
        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $final_recipes = array();
        foreach ($recipes as $recipe) {
                $recipe['category'] = getRecipeCategory($recipe['id']); 
                array_push($final_recipes, $recipe);
        }
        return $final_recipes;
}


// more functions to come here ...

//this will bring a single recipe
// function getRecipe($slug){
//         global $conn;
//         // Get single recipe slug
//         $recipe_slug = $_GET['recipe-slug'];
//         $sql = "SELECT * FROM recipe WHERE slug='$recipe_slug' AND published=true";
//         $result = mysqli_query($conn, $sql);

//         // fetch query results as associative array.
//         $recipe = mysqli_fetch_assoc($result);
//         if ($recipe) {
//                 // get the topic to which this recipe belongs
//                 $recipe['category'] = getRecipeCategory($recipe['id']);
//         }
//         return $recipe;
// }

//this fetches the category of the recipe
function getRecipeCategory($recipe_id){
        global $conn;
        $sql = "SELECT * FROM category WHERE id=(SELECT category_id FROM recipe_category WHERE recipe_id=$recipe_id) LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $category = mysqli_fetch_assoc($result);
        return $category;
}






// Returns all recipes under a category

function getPublishedRecipesByCategory($category_id) {
        global $conn;
        $sql = "SELECT * FROM recipe r
                        WHERE r.id IN 
                        (SELECT rc.recipe_id FROM recipe_category rc
                                WHERE rc.category_id=$category_id GROUP BY rc.recipe_id 
                                HAVING COUNT(1) = 1)";
        $result = mysqli_query($conn, $sql);
        // fetch all recipes as an associative array called $recipes
        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $final_recipes = array();
        foreach ($recipes as $recipe) {
                $recipe['category'] = getRecipeCategory($recipe['id']); 
                array_push($final_recipes, $recipe);
        }
        return $final_recipes;
}






//Returns category name by category id
function getCategoryNameById($id)
{
        global $conn;
        $sql = "SELECT name FROM category WHERE id=$id";
        $result = mysqli_query($conn, $sql);
        $category = mysqli_fetch_assoc($result);
        return $category['name'];
}





//Returns a single post

function getRecipe($slug){
        global $conn;
        // Get single recipe slug
        $recipe_slug = $_GET['recipe-slug'];
        $sql = "SELECT * FROM recipe WHERE slug='$recipe_slug' AND published=true";
        $result = mysqli_query($conn, $sql);

        // fetch query results as associative array.
        $recipe = mysqli_fetch_assoc($result);
        if ($recipe) {
                // get the category to which this recipe belongs
                $recipe['category'] = getRecipeCategory($recipe['id']);
        }
        return $recipe;
}
//Returns all topics
function getAllCategories()
{
        global $conn;
        $sql = "SELECT * FROM category";
        $result = mysqli_query($conn, $sql);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $categories;
}





?>