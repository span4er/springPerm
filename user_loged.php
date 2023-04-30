
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
    require_once 'php\configuration.php';
    require_once __ROOT__.'\php\DB.php';
    require_once __ROOT__.'\php\alert.php';   
    require_once __ROOT__.'\php\userHandler.php';

           
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    $errorMsg = "";
 if (!isset($_SESSION["user"])) //Проверяем условия для входа
    {
        header("Location:/index.php");
        die();
    }
    if(isset($_GET['exit'])) 
    {
        unset($_SESSION['user']);
        session_destroy();
        header("Location:/index.php");
        die();
    }

    $user_data =  get_user_data($_SESSION['user']);
    if($_GET['entity'] == "user" && isset($_SESSION['user'])){
        if(isset($_POST["img_update"])){
            if(isset($_FILES['img_file']) && $_FILES['img_file'] !=""){ 
                $img_outcome = update_user_img($_FILES['img_file'], $user_data);
                if($img_outcome == "Nope")
                {
                    header("Location:/user_loged.php?entity=user");
                }
                else 
                    {
                        $errorMsg = $img_outcome;
                    }
                }
            }
        }

?>

<div class = "container">
<div class = "container_admin">

<div class="admin-sidebar" style = "margin-left:2%">
   <h3 class="admin-bar-menu" >Меню</h3>
    <a class="menu_button" href="?entity=user" name="user" value="Администраторы">Аккаунт</a><br>
    <a class="menu_button" href="?entity=spring" name="spring" value="Сотрудники">Добавить родник</a><br>
    <a class="menu_button" href="?exit=true" name="exit">Выйти </a><br>
</div>
<div class = "main_content">
            <?php
            if ($errorMsg !== "" && !preg_match("%session_start%",$errorMsg)){
                ?>
                <div class='login__error'>
                    ! <?=$errorMsg?><br>
                </div>
                <?php
            }
        ?>
        <?php if ($_GET['entity'] == "user"){ ?>
        <h1  class = "spring_head"><?=$user_data[0]['user_login']?></h1>
        <img class = "user_pic_acc" src = "<?= //__ROOT__."\\resources\img\\".$row['user_pic_name']
                                                "/resources/img/user_pics/".$user_data[0]['user_pic_name'];
            ?>">
        <form name="user_img" method="POST" action="/user_loged.php?entity=user" enctype="multipart/form-data">
        <input type="file" value="" name="img_file">
        <input type="submit" value="Обновить аватар" name="img_update">
        </form>
        <p>Логин пользователя: <span><?= $user_data[0]['user_login']?></span></p>
        <p>e-mail пользователя: <span><?= $user_data[0]['user_mail']?></span></p>
        <?php }  
        else if ($_GET['entity'] == "spring"){ ?>
        <form class ="spring_add_box"  action="user_loged.php" method="post" enctype="multipart/form-data">
                        <label class ="log_label_box_labels" for="login">Название:</label>
                        <input id="login" class = "login" name="userData_login" type="text"  required>
                        <label class ="log_label_box_labels" for="password">Широта долгота:</label>
                        <input id="password" class = "password" name="userData_password" type="password"  required>
                        <label class ="log_label_box_labels" for="password">Описание:</label>
                        <input id="imgs" class = "imgs" name="springData_imgs[]" multiple type="file">
                        <input name="submit" class="submit_button" type="submit" value = "Отправить на рассмотрение">
                </form>   
        <?php } ?>


</div>
</div>
</div>
</body>
</html>


