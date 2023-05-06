
<!DOCTYPE html>
<html>
<head>
<!--<link reL=stylesheet HREF="css/main.css">-->
    <link reL=stylesheet HREF="../css/new.css">
    <link reL=stylesheet HREF="../css/admin.css">

<title> Главная страница </title>
</head>
<body>
    <?php
    require_once '../UI/header.php';
    ?>

<?php
    require_once '../php/DB.php';
    require_once '../php/str_includes.php';
    require_once '../php/validateStr.php';
    require_once '../php/isLoggedIn.php';
    require_once '../php/drawTable.php';
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

    if(!isset($_POST['table'])) $table = $_GET['table'];
    else $table = $_POST['table'];

    $currentTable ="";
    $toInsert ="";
    switch ($table){
        case 'emp':
            $query = "SELECT * from insuranceforallprom.dim_emp de";
            $currentTable = "insuranceforallprom.dim_emp";
            break;
        case 'admin':
            $query = "SELECT * from insuranceforallprom.admin_users de";
            $currentTable = "insuranceforallprom.admin_users";
            break;
        case 'insur':
            $query = "SELECT * from insuranceforallprom.dim_insurance";
            $currentTable = "insuranceforallprom.dim_insurance";
            break;
    default: 
        $query = "SELECT * from insuranceforallprom.admin_users de";
        $currentTable = "insuranceforallprom.admin_users";
        break;
        }
        
    try {
        $DB_arr = executeSQL($query);
    } catch (Exception $e) {
        $DB_arr = [];
    }
    $header = $DB_arr[0];

    //Проверки удаления вставки обновления
    if(isset($_GET['toDelete']) && $_GET['toDelete'] =="yes" && isset($_POST['delete']))
    {
        $toDeleteKeys = "";
        $collumnToIdent = $_POST['main_col'];
        $delete = $_POST['delete'];
        foreach($delete as $key => $value){
            if(is_numeric($value))
                {
                    $toDeleteKeys .= $value;
                    $toDeleteKeys .= ",";
                }
            else 
                {
                    $toDeleteKeys .= "'$value'";
                    $toDeleteKeys .= ",";
    
                }
            } 
            $toDeleteKeys = mb_substr($toDeleteKeys, 0, -1);
            $sqlString = "DELETE FROM ";
            $sqlString .= $currentTable;
            $sqlString .= " WHERE ";
            $sqlString .= $collumnToIdent;
            $sqlString .= " in (";
            $sqlString .= $toDeleteKeys;
            $sqlString .= ")";
            try {
                $DB_arr1 = executeSQL($sqlString);
                header("Location:/admin/admin_loged.php?table=$table");
            } catch (Exception $e) {
                $DB_arr1 = [];
                echo "$sqlString </br>";
                echo $e;
                die();
            }

    } 


    if(isset($_POST['newRow']))
    {
        $toRow = $_POST['newRow'];
        $insertTrue = true;

        foreach($toRow as $key => $value){
            if(is_numeric($value))
                {
                    $toInsert .= $value;
                    $toInsert .= ",";
                }
            else if (is_null($value))
            {
                $toInsert .= "NULL";
                $toInsert .= ",";
            }
            else 
                {
                    
                    $toInsert .= "'$value'";
                    $toInsert .= ",";
    
                }
            
        }
        $toInsert = mb_substr($toInsert, 0, -1);
        if ($insertTrue = true)
        {
            // $table = $_GET["table"];
            $sqlString = "INSERT INTO ";
            $sqlString .= $currentTable;
            $sqlString .= " VALUES ";
            $sqlString .= "(";
            $sqlString .= $toInsert;
            $sqlString .= ")";
            try {
                $DB_arr1 = executeSQL($sqlString);
                header("Location:/admin/admin_loged.php?table=$table");
            } catch (Exception $e) {
                $DB_arr1 = [];
                echo "$sqlString </br>";
                echo $e;
                die();
            }
        }

         header("Location:/admin/admin_loged.php?table=$table");

    }

    if(isset($_POST['updateRow']))
    {
        $toRow = $_POST['updateRow'];
        $updateTrue = true;
        $toUpdate = "";
        $collumnToIdent = $_POST['main_col'];
        $counter = 0;

        foreach($toRow as $key => $value){
            if(is_numeric($value))
                {   
                   if($counter ==0 ) $rowToUpdate = $value;
                    $toUpdate .= $key;
                    $toUpdate .= " = ";
                    $toUpdate .= $value;
                    $toUpdate .= " ,";
                }
            else if (is_null($value))
            {
                $toUpdate .= $key;
                $toUpdate .= " = ";
                $toUpdate .= "NULL";
                $toUpdate .= ",";
            }
            else if ($value == '')
            {
                
            }
            else 
                {
                    if($counter ==0 ) $rowToUpdate = $value;
                    $toUpdate .= $key;
                    $toUpdate .= " = ";
                    $toUpdate .= "'$value'";
                    $toUpdate .= ",";
    
                }
                $counter = $counter +1;
           
        }
        $toUpdate = mb_substr($toUpdate, 0, -1);
        if ($updateTrue = true)
        {
            // $table = $_GET["table"];
            $sqlString = "UPDATE ";
            $sqlString .= $currentTable;
            $sqlString .= " SET ";
           // $sqlString .= "(";
            $sqlString .= $toUpdate;
            $sqlString .= " WHERE ";
            $sqlString .= $collumnToIdent;
            $sqlString .= " = '";
            $sqlString .=  $rowToUpdate;
            $sqlString .= "'";

            
           // $sqlString .= ")";
            try {
                $DB_arr1 = executeSQL($sqlString);
                header("Location:/admin/admin_loged.php?table=$table");
            } catch (Exception $e) {
                $DB_arr1 = [];
                $errorMsg = $e;
                echo "$sqlString </br>";
                echo $e;
                die();
            }
        }

         header("Location:/admin/admin_loged.php?table=$table");

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


