<header>
<div class = "header_tool">
        
        <ul>
        
        <li>
             <a href ="\index.php" class = "header_button">Главная</a>
            </li>
            <li>
             <a href = "/springs.php" class =  "header_button">Родники</a>
            </li>
                <?php 
            if(!isset($_SESSION)) 
            { 
                session_start(); 
            } 
           if(isset($_GET['exit'])) 
           {
               unset($_SESSION['user']);
               session_destroy();
               header("Location:/index.php");
               die();
           }
           if (isset($_SESSION['user']) == true)
        {
                ?>
                <div class = "log_box_main"> 
                        <a href = "/user_loged.php" class = "log"><?php echo $_SESSION['user'] ?></a>
                        <a class="log_out" href="?exit=true" name="exit">ВЫЙТИ</a>
                </div>
                <?php
        }
        else 
        {
                ?>
                <div class = "log_box_main">
                        <a href ="/log_in.php" class = "log">Войти</a>
                </div>
                <?php
        }
            ?>
           
</ul>
</div>
</header>
