
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
    require_once __ROOT__.'\php\get_springs.php';

           
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
    if(isset($_GET['added'])){
        unset($_GET['added']);
        function_alert("Родник отправлен на утверждение");
    }



    if(isset($_POST['springData_name']) && strlen(ltrim($_POST['springData_name'])) > 0){
        if ( !preg_match("/[^а-яё ]/i", $_POST['springData_name']))
        {
            $errorMsg .= "В названии должна быть только кириллица и длина названия от 5 до 40 символов!!<br>".$_POST['springData_name'];
        }
        if ( !preg_match('/^[0-9.]{5,10}$/', $_POST['springData_long']))
        {
            $errorMsg .= "Неверный формат долготы родника (используются цифры и разделитель в виде точки)!!<br>";
        }
        if ( !preg_match('/^[0-9.]{5,10}$/',  $_POST['springData_lat']))
        {
            $errorMsg .= "Неверный формат ширины родника (используются цифры и разделитель в виде точки)!!";
        }
        
        if($errorMsg == ""){
            $outcome = insert_new_spring($_POST['springData_name'], $_POST['springData_description'], $_POST['springData_long'], $_POST['springData_lat'], 
            $_POST['springData_quality'], ((isset($_FILES['springData_imgs']))?$_FILES['springData_imgs']:null));
            unset($_POST['springData_name']);
            unset($_POST['springData_description']);
            unset($_POST['springData_long']);
            unset($_POST['springData_lat']);
            unset($_POST['springData_quality']);
            unset($_FILES['springData_imgs']);
            if ($outcome == "1") {
                header("Location:/user_loged.php?entity=spring&added=1");
            }
            else 
                $errorMsg = $outcome;
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
        <h1 class="spring_head" >Личный кабинет</h1>
        <h1  class = "spring_head"><?=$user_data[0]['user_login']?></h1>
        <img class = "user_pic_acc" src = "<?= //__ROOT__."\\resources\img\\".$row['user_pic_name']
                                                "/resources/img/user_pics/".$user_data[0]['user_pic_name'];
            ?>">
        <form class ="log_label_box" name="user_img" method="POST" action="/user_loged.php?entity=user" enctype="multipart/form-data">
        <label class ="choose_image" for="img_file">Выберите изображение</label>
        <input class = "user_input" type="file" value="Изображение аватара" name="img_file">
        <input class = "user_input" type="submit" value="Обновить аватар" name="img_update">
        </form>
        <p class ="user_info">Логин пользователя: <span><?= $user_data[0]['user_login']?></span></p>
        <p class = "user_info">e-mail пользователя: <span><?= $user_data[0]['user_mail']?></span></p>
        <?php }  
        else if ($_GET['entity'] == "spring"){ ?>
                <h1 class="spring_head" >Добавление родника</h1>
        <form id = "add_spring" class ="log_label_box"  action="user_loged.php?entity=spring" method="post" enctype="multipart/form-data">
                        <label class ="add_spring_label_box_labels" for="name">Название:</label>
                        <input id="name" class = "name" name="springData_name" type="text" required>
                        <label class ="add_spring_label_box_labels" for="coordinates_long">Выберите расположение родника на карте или введите широту и долготу самостоятельно:</label>
                        <div id="map" class = "map_add_spring"></div>
                         <!-- style="width: 100%; height:800px"></div> -->
            <script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU"  type="text/javascript"></script>
                        <script src="/resources/js/event_properties.js" type="text/javascript"></script>
                        <!-- <style>
                            html, body, #map {
                                width: 100%; height: 100%; padding: 0; margin: 0;
                            }
                        </style> -->
                        <label class ="choose_image" for="coordinates_long">Широта:</label>
                        <input id="coordinates_long" name="springData_long" type="text"  required> 
                        <label class ="choose_image" for="coordinates_latitud">Долгота:</label>  
                        <input id="coordinates_latitud" name="springData_lat" type="text"  required>                                                                                       
                        <label class ="add_spring_label_box_labels" for="description">Описание:</label>
                        <textarea id="description" class = "description" name="springData_description" type="text" maxlength=800 rows = 7  required></textarea>
                        <input id="imgs" class = "imgs" name="springData_imgs[]" multiple type="file">
                        <select form ="add_spring" class = "select_drink" name="springData_quality" required="required">
                        <option value="1">Рекомендуется кипитить воду</option>
                        <option value="2">Вода подлежит обязательному кипичению</option>
                        </select>
                        <input name="submit" class="submit_button" type="submit" value = "Отправить на рассмотрение">
                </form>   
        <?php } ?>


</div>
</div>
</div>
</body>

</html>


