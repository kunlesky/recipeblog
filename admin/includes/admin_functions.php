<?php 
// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// general variables
$errors = [];

// categorys variables
$category_id = 0;
$isEditingCataegory = false;
$category_name = "";

//Admin users actions
// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
        createAdmin($_POST);
}
// if user clicks the Edit admin button
if (isset($_GET['edit-admin'])) {
        $isEditingUser = true;
        $admin_id = $_GET['edit-admin'];
        editAdmin($admin_id);
}
// if user clicks the update admin button
if (isset($_POST['update_admin'])) {
        updateAdmin($_POST);
}
// if user clicks the Delete admin button
if (isset($_GET['delete-admin'])) {
        $admin_id = $_GET['delete-admin'];
        deleteAdmin($admin_id);
}

/* - - - - - - - - - - - -
-  Admin users functions
- - - - - - - - - - - - -*/
/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new admin data from form
* - Create new admin user
* - Returns all admin users with their roles 
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
        global $conn, $errors, $role, $username, $email;
        $username = esc($request_values['username']);
        $email = esc($request_values['email']);
        $password = esc($request_values['password']);
        $passwordConfirmation = esc($request_values['passwordConfirmation']);

        if(isset($request_values['role'])){
                $role = esc($request_values['role']);
        }
        // form validation: ensure that the form is correctly filled
        if (empty($username)) { array_push($errors, "Uhmm...We gonna need the username"); }
        if (empty($email)) { array_push($errors, "Oops.. Email is missing"); }
        if (empty($role)) { array_push($errors, "Role is required for admin users");}
        if (empty($password)) { array_push($errors, "uh-oh you forgot the password"); }
        if ($password != $passwordConfirmation) { array_push($errors, "The two passwords do not match"); }
        // Ensure that no user is registered twice. 
        // the email and usernames should be unique
        $user_check_query = "SELECT * FROM users WHERE username='$username' 
                                                        OR email='$email' LIMIT 1";
        $result = mysqli_query($conn, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        if ($user) { // if user exists
                if ($user['username'] === $username) {
                  array_push($errors, "Username already exists");
                }

                if ($user['email'] === $email) {
                  array_push($errors, "Email already exists");
                }
        }
        // register user if there are no errors in the form
        if (count($errors) == 0) {
                $password = md5($password);//encrypt the password before saving in the database
                $query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
                                  VALUES('$username', '$email', '$role', '$password', now(), now())";
                mysqli_query($conn, $query);

                $_SESSION['message'] = "Admin user created successfully";
                header('location: users.php');
                exit(0);
        }
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id)
{
        global $conn, $username, $role, $isEditingUser, $admin_id, $email;

        $sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $admin = mysqli_fetch_assoc($result);

        // set form values ($username and $email) on the form to be updated
        $username = $admin['username'];
        $email = $admin['email'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Receives admin request from form and updates in database
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
        global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
        // get id of the admin to be updated
        $admin_id = $request_values['admin_id'];
        // set edit state to false
        $isEditingUser = false;


        $username = esc($request_values['username']);
        $email = esc($request_values['email']);
        $password = esc($request_values['password']);
        $passwordConfirmation = esc($request_values['passwordConfirmation']);
        if(isset($request_values['role'])){
                $role = $request_values['role'];
        }
        // register user if there are no errors in the form
        if (count($errors) == 0) {
                //encrypt the password (security purposes)
                $password = md5($password);

                $query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$admin_id";
                mysqli_query($conn, $query);

                $_SESSION['message'] = "Admin user updated successfully";
                header('location: users.php');
                exit(0);
        }
}
// delete admin user 
function deleteAdmin($admin_id) {
        global $conn;
        $sql = "DELETE FROM users WHERE id=$admin_id";
        if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "User successfully deleted";
                header("location: users.php");
                exit(0);
        }
}
//Returns all admin users and their corresponding roles

function getAdminUsers(){
        global $conn, $roles;
        $sql = "SELECT * FROM users WHERE role IS NOT NULL";
        $result = mysqli_query($conn, $sql);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $users;
}

//Escapes form submitted value, hence, preventing SQL injection

function esc(String $value){
        // bring the global db connect object into function
        global $conn;
        // remove empty space sorrounding string
        $val = trim($value); 
        $val = mysqli_real_escape_string($conn, $value);
        return $val;
}
// Receives a string like 'Some Sample String'
// and returns 'some-sample-string'
function makeSlug(String $string){
        $string = strtolower($string);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return $slug;
}


/* - - - - - - - - - - 
-  category actions
- - - - - - - - - - -*/
// if user clicks the create category button
if (isset($_POST['create_category'])) { createcategory($_POST); }
// if user clicks the Edit category button
if (isset($_GET['edit-category'])) {
        $isEditingcategory = true;
        $category_id = $_GET['edit-category'];
        editcategory($category_id);
}
// if user clicks the update category button
if (isset($_POST['update_category'])) {
        updateCategory($_POST);
}
// if user clicks the Delete category button
if (isset($_GET['delete-category'])) {
        $category_id = $_GET['delete-category'];
        deleteCategory($category_id);
}


/* - - - - - - - - - - 
-  categorys functions
- - - - - - - - - - -*/
// get all categorys from DB
function getAllCategory() {
        global $conn;
        $sql = "SELECT * FROM category";
        $result = mysqli_query($conn, $sql);
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $categories;
}
function createcategory($request_values){
        global $conn, $errors, $category_name;
        $category_name = esc($request_values['category_name']);
        // create slug: if category is "Life Advice", return "life-advice" as slug
        $category_slug = makeSlug($category_name);
        // validate form
        if (empty($category_name)) { 
                array_push($errors, "Category name required"); 
        }
        // Ensure that no category is saved twice. 
        $category_check_query = "SELECT * FROM category WHERE slug='$category_slug' LIMIT 1";
        $result = mysqli_query($conn, $category_check_query);
        if (mysqli_num_rows($result) > 0) { // if category exists
                array_push($errors, "Category already exists");
        }
        // register category if there are no errors in the form
        if (count($errors) == 0) {
                $query = "INSERT INTO category (name, slug) 
                                  VALUES('$category_name', '$category_slug')";
                mysqli_query($conn, $query);

                $_SESSION['message'] = "Category created successfully";
                header('location: category.php');
                exit(0);
        }
}
/* * * * * * * * * * * * * * * * * * * * *
* - Takes category id as parameter
* - Fetches the category from database
* - sets category fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editCategory($category_id) {
        global $conn, $category_name, $isEditingCategory, $category_id;
        $sql = "SELECT * FROM category WHERE id=$category_id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $category = mysqli_fetch_assoc($result);
        // set form values ($category_name) on the form to be updated
        $category_name = $category['name'];
}
function updateCategory($request_values) {
        global $conn, $errors, $category_name, $category_id;
        $category_name = esc($request_values['category_name']);
        $category_id = esc($request_values['category_id']);
        // create slug: if category is "Life Advice", return "life-advice" as slug
        $category_slug = makeSlug($category_name);
        // validate form
        if (empty($category_name)) { 
                array_push($errors, "category name required"); 
        }
        // register category if there are no errors in the form
        if (count($errors) == 0) {
                $query = "UPDATE categorys SET name='$category_name', slug='$category_slug' WHERE id=$category_id";
                mysqli_query($conn, $query);

                $_SESSION['message'] = "category updated successfully";
                header('location: category.php');
                exit(0);
        }
}
// delete category 
function deleteCategory($category_id) {
        global $conn;
        $sql = "DELETE FROM categorys WHERE id=$category_id";
        if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "category successfully deleted";
                header("location: category.php");
                exit(0);
        }
}

?>