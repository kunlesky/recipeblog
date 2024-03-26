<?php 

// function getPublishedRecipes() {
//         // use global $conn object in function
//         global $conn;
//         $sql = "SELECT * FROM recipe WHERE published=true";
//         $result = mysqli_query($conn, $sql);

//         // fetch all recipes as an associative array called $recipes
//         $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

//         return $recipes;
// }


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
function getRecipe($slug){
        global $conn;
        // Get single recipe slug
        $recipe_slug = $_GET['recipe-slug'];
        $sql = "SELECT * FROM recipe WHERE slug='$recipe_slug' AND published=true";
        $result = mysqli_query($conn, $sql);

        // fetch query results as associative array.
        $recipe = mysqli_fetch_assoc($result);
        if ($recipe) {
                // get the topic to which this recipe belongs
                $recipe['category'] = getRecipeTopic($recipe['id']);
        }
        return $recipe;
}

function getRecipeCategory($recipe_id){
        global $conn;
        $sql = "SELECT * FROM category WHERE id=(SELECT category_id FROM recipe_category WHERE recipe_id=$recipe_id) LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $category = mysqli_fetch_assoc($result);
        return $category;
}

?>