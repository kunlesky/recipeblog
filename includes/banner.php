<?php if (isset($_SESSION['user']['username'])) { ?>
        <div class="logged_in_info">
                <span>welcome <?php echo $_SESSION['user']['username'] ?></span>
                |
                <span><a href="logout.php">logout</a></span>
        </div>
<?php }else{ ?>
        <div class="banner">
                <div class="welcome_msg">
                        <h1>Today's Recipe</h1>
                        <p> 
                            You only live once <br> 
                            Eating good food is a way <br> 
                            to live the best of life. <br>
                                <span>~Goke Bajowa</span>
                        </p>
                        <a href="register.php" class="btn">Join us!</a>
                </div>

                <div class="login_div">
                        <form action="<?php echo BASE_URL . 'index.php'; ?>" method="post" >
                                <h2>Login</h2>
                                <div style="width: 60%; margin: 0px auto;">
                                        <?php include(ROOT_PATH . '/includes/errors.php') ?>
                                </div>
                                <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
                                <input type="password" name="password"  placeholder="Password"> 
                                <button class="btn" type="submit" name="login_btn">Sign in</button>
                        </form>
                </div>
        </div>
<?php } ?>