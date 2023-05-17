
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
    $quality_data = get_dim_quality();
    $user_role = null;
    $user_roles = null;
    $bit = null;

    if (isset($_SESSION['user']) == true){
        $user_role = get_user_role($_SESSION['user']);
        $user_role = $user_role[0]['user_role_id'];
    }

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

   
    if($_GET['entity'] == "super" && isset($_SESSION['user'])){
            $user_roles =  get_users_roles();
    }

    if(isset($_POST['user_id'])){
        $up_outcome = update_role_super($_POST['user_id']);
        if($up_outcome == "Nope")
        {
            header("Location:/user_loged.php?entity=super");
        }
        else 
            {
                $errorMsg = $up_outcome;
            }     
    }
    if(isset($_POST['decline_user_id'])){
        $d_outcome = decline_role_super($_POST['decline_user_id']);
        if($d_outcome == "Nope")
        {
            header("Location:/user_loged.php?entity=super");
        }
        else 
            {
                $errorMsg = $d_outcome;
            }     
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
    <?php if($user_role == '100' || $user_role == '99') {?><a class="menu_button" href="?entity=super" name="spring" value="Сотрудники">Роли пользователей</a><br><?php } ?>
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
                        <label class ="add_spring_label_box_labels" for="springData_imgs[]">Добавьте изображения:</label>
                        <input id="imgs" class = "imgs" name="springData_imgs[]" multiple type="file">
                        <select form ="add_spring" class = "select_drink" name="springData_quality" required="required">
                        <?php foreach ($quality_data as $row){ ?>
                        <option value="<?=$row['quality_id']?>"><?=$row['quality_name']?></option>
                        <?php } ?>
                        <!-- <option value="2">Вода подлежит обязательному кипичению</option> -->
                        </select>
                        <input name="submit" class="submit_button" style="font-size:30px" type="submit" value = "Отправить на рассмотрение">
                </form>   
        <?php } 
        else if ($_GET['entity'] == "super" && ($user_role == '99' || $user_role == '100')){  
            echo $bit;
            ?><h1 class="spring_head" >Редактирование роли пользователей</h1>
            <table class = "spring_head" style = "width:100%">
            <thead style="font-size:25px;" >
                <tr>              
                    <th>Логин</th>
                    <th>Роль</th>
                    <th></th>
                </tr>
            </thead> 
            <?php
            foreach($user_roles as $row){?>         
            <tr class = "spring_tab"  style="font-size:15px;">
            <script type="text/javascript">
                        function proverka1(name) {
                            if (confirm("Наделить пользователя " + name + " правами суперпользователя?")) {                                    
                                let form = document.getElementById('change_role');
                                form.submit();
                            } else {
                                return false;
                            }
                        }
                        function proverka_decline(name) {
                            if (confirm("Убрать у пользователя " + name + " права суперпользователя?")) {                                    
                                let form = document.getElementById('decline_role');
                                form.submit();
                            } else {
                                return false;
                            }
                        }
                    </script>
                <td style="width:20%;"><?=$row['user_login']?></td>
                <td style="width:20%;"><?=$row['user_role_name']?></td>
                        <td style="width:20%;">
                        <form id = "change_role" action="user_loged.php?entity=super" method="post" enctype="multipart/form-data">
                        <input class="" name="user_id" style="font-size:30px" type="hidden" value = "<?=$row['user_id']?>">
                        <button style="font-size:15px;" <?php if($row['user_role_id'] == 99 || $row['user_role_id'] == 100) echo 'disabled ';?>onclick="return proverka1('<?=$row['user_login']?>');">Наделить правами суперпользователя</button>     
                    </form>
                    </td>
                    <td style="width:20%;">
                        <form id = "decline_role" action="user_loged.php?entity=super" method="post" enctype="multipart/form-data">
                        <input class="" name="decline_user_id" style="font-size:30px" type="hidden" value = "<?=$row['user_id']?>">
                        <button style="font-size:15px;" <?php if($row['user_role_id'] == 0 || $row['user_role_id'] == 100) echo 'disabled ';?>onclick="return proverka_decline('<?=$row['user_login']?>');">Убрать права суперпользователя</button>     
                    </form>
                    </td>
                    </tr>        
        <?php } }
        else {?>
            <p class = "spring_head" style ="color:red" >Нет доступа</p>
            <?php } ?>
        </table>



</div>
</div>
</div>
</body>

</html>


