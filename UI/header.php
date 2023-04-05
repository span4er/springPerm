 <?php
        // require_once 'E:/xammpp/htdocs/springPerm/php/configuration.php';
        // require_once 'php/alert.php';
        
        // // require_once __ROOT__.'E:/xammpp/htdocs/springPerm/php/singInHandler.php';
        // // require_once __ROOT__.'\php\singInHandler.php';
        // session_start();
        // ini_set('display_errors', '1');
        // ini_set('display_startup_errors', '1');
        // error_reporting(E_ALL);
        
        // if (isset($_GET['registered']))
        // {
        //         // unset($_GET['registered']);
        //         $alert_message = "Пользователь ".$_SESSION['user']." успешно зарегистрирован.";
        //         function_alert($alert_message);
        // }

        // $htmlString1 = "";
        // if(isset($_GET['exit'])) 
        // {
        //     unset($_SESSION['user']);
        //     session_destroy();
        //     header("Location:/index.php");
        //     die();
        //     //$table = $_GET['table'];
        // }
        ?> 
<header>
<!-- <script src="/time.js"></script> -->
<div class = "header_tool">
        
        <ul>
        
        <li>
             <a href ="\index.php" class = "header_button">Главная</a>
            </li>
            <li>
             <a href = "/springs.php" class =  "header_button">Родники</a>
            </li>
                <?php 
           // echo "$htmlString1";
           session_start();
           if (isset($_SESSION['user']) == true)
        {
                // $htmlString1 .= "<div class = \"log_box_main\"><a href =\"admin/admin_loged.php\" class = \"log\">";
                // $htmlString1 .= $_SESSION['user'];
                // $htmlString1 .= "</a><a class = \"log_out\">ВЫЙТИ</a></div>";
                ?>
                <div class = "log_box_main"> 
                        <a href = "/user_loged.php" class = "log"><?php echo $_SESSION['user'] ?></a>
                        <a class="log_out" href="?exit=true" name="exit">ВЫЙТИ</a>
                </div>
                <?php
        }
        else 
        {
               // $htmlString1 .= "<div class = \"log_box_main\"><a href =\"admin/admin.php\" class = \"log\">Войти</a></div>";
                ?>
                <div class = "log_box_main">
                        <a href ="/log_in.php" class = "log">Войти</a>
                </div>
                <?php
        }
            ?>
           
            <!-- <a href ="../admin/admin.php" class = "log">Войти</a> -->
</ul>
</div>
</header>
