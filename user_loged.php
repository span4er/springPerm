
<!DOCTYPE html>
<html>
<head>
<!--<link reL=stylesheet HREF="css/main.css">-->
<link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">

<title> Главная страница </title>
</head>
<body>
    <?php
    require_once 'UI/header.php';
    ?>

<?php
    require_once 'php/DB.php';
    require_once 'php/str_includes.php';
    require_once 'php/validateStr.php';
    require_once 'php/isLoggedIn.php';
    require_once 'php/drawTable.php';
    //session_start();
    // if (!isset($_SESSION["user"])) //Проверяем условия для входа
    // {
    //     header("Location:/admin/admin.php?denied=true");
    //     die();
    // }


    $errorMsg = "";

    if(isset($_GET['exit'])) 
    {
        unset($_SESSION['user']);
        session_destroy();
        header("Location:/index.php");
        die();
        //$table = $_GET['table'];
    }


?>

<div class = "container_admin">

<div class="admin-sidebar" style = "margin-left:2%">
   <h3 class="admin-bar-menu" >Меню</h3>
    <a class="admin-bar-item admin-button" href="?table=admin" name="tabl" value="Администраторы">Администраторы</a><br>
    <a class="admin-bar-item admin-button" href="?table=emp" name="tabl" value="Сотрудники"> Сотрудники</a><br>
    <a class="admin-bar-item admin-button" href="?table=insur" name="tabl" value="Страхование">Страхование </a><br>
    <a class="admin-bar-item admin-button" href="?exit=true" name="exit" value="Страхование">Выйти </a><br>
</div>
<div class = "main_content">
<form action = "admin_loged.php?toDelete=yes" method = "POST">
<table class = "all_table">
        <caption></caption>
        <?php
                echo draw_table($DB_arr,$table);
        ?>    
        <input class="admin-bar-item admin-button " type = "submit" value = "Удалить выбранные записи">
</form>
        <div class = "admin_block">
        <div class = "block_insert">
        <form action = "admin_loged.php" method = "POST">
    <?php

    $htmlString1 = "";
    foreach ($header as $key => $value) {
                $htmlString1 .= "<label class =\"admin_label\" for=\"$key\">$key </label>";                
                $htmlString1 .= "<input id = $key name =\"newRow[$key]\" class = \"\" type = \"text\"><br>";
            }
            $htmlString1 .= "<input name=\"table\" type=\"hidden\" value=\"$table\">";
            $htmlString1 .= "<input class=\"admin-bar-item admin-button \" type = \"submit\" value = \"Добавить запись\">";
            echo "$htmlString1";
    ?>    
    </form>    
     </div>
     <div class = "block_update">
        <form action = "admin_loged.php?toUpdate=yes" method = "POST">
    <?php

    $htmlString1 = "";
    $main_colt = "";
    $counter = 0;
    foreach ($header as $key => $value) {
        if($counter ==0 ) $main_colt = $key;
                $htmlString1 .= "<label class =\"admin_label\" for=\"$key\">$key </label>";                
                $htmlString1 .= "<input id = $key name =\"updateRow[$key]\" class = \"\" type = \"text\"><br>";
                $counter = $counter + 1;
            }
            $htmlString1 .= "<input name=\"table\" type=\"hidden\" value=\"$table\">";
            $htmlString1 .= "<input name=\"main_col\" type=\"hidden\" value=\"$main_colt\">";
            $htmlString1 .= "<input class=\"admin-bar-item admin-button \" type = \"submit\" value = \"Изменить запись\">";
            echo "$htmlString1";
    ?>    
    </form>    
    </div>
    <?php
                if ($errorMsg !== "")
                    echo "
                    <div class='login__error'>
                        ! $errorMsg <br>
                    </div>
                    ";
            ?>
</div>

</div>
        </div>

<?php
    require_once '../UI/footer.php';
?>

</body>
</html>


