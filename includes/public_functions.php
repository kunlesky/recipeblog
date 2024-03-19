<?php 
/* * * * * * * * * * * * * * *
* Returns all published posts
* * * * * * * * * * * * * * */
function getPublishedRecipes() {
        // use global $conn object in function
        global $conn;
        $sql = "SELECT * FROM recipe WHERE published=true";
        $result = mysqli_query($conn, $sql);

        // fetch all posts as an associative array called $posts
        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $recipes;
}

// more functions to come here ...


?>