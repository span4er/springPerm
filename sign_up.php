<!DOCTYPE html>
<html>
<head>
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
<?php require_once 'UI/header.php'; ?>
<?php

 require_once 'php/signInHandler.php';
 require_once 'php/alert.php';
 require_once 'php/signUpHandler.php';
  
  $errorMsg = "";

if (isset($_SESSION['user']) == true)
{
    header("Location:/index.php");
    die();
}


if (isset($_POST['userData_login']) && isset($_POST['userData_password']) && isset($_POST['userData_mail'])){
    $login = $_POST['userData_login'];
    $password = $_POST['userData_password'];
    $mail = $_POST['userData_mail'];

    if(empty($login)){
        $errorMsg = "Не введён логин!!";
    }
    else if(empty($password)){
        $errorMsg = "Не введён пароль!!";
    }
    else if (empty($mail)){
        $errorMsg = "Не введена почта!!";
    }
    else {
        unset($_POST['userData_login']);
        unset($_POST['userData_password']);
        unset($_POST['userData_mail']);

        signUp($login,$password,$mail);
        // //Проверка доступности логина и почты
        // if(!checkLoginAvailable($login))
        //     {
        //         $errorMsg = "Введённый логин ";
        //         $errorMsg .= $login;
        //         $errorMsg .= " уже занят.";
        //     }
        // else if (!checkMailAvailable($mail))
        //     {
        //         $errorMsg = "Введённая почта " ;
        //         $errorMsg .= $mail;
        //         $errorMsg .= " уже указана для другого пользователя.";
        //     }
        // else {
        //     if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
        //         {
        //         $errorMsg = "Введите действительный почтовый адрес";
        //         }
        //     else if ( !preg_match('/^[A-Za-z0-9]{5,31}$/', $login) )
        //     {
        //         $errorMsg = "Логин не должен содержать пробелов и специальных символов и быть длиной не менее 5 и не более 31 символа!!";
        //     }
        //     else if ( !preg_match('/^[A-Za-z0-9]{5,31}$/', $password))
        //     {
        //         $errorMsg = "Пароль должен быть длиной не менее 5 и не более 31 символа. Допускается писать только буквы и цифры!!";
        //     }
        //     else
        //     {
        //         $pas_hash = password_hash($password, PASSWORD_DEFAULT);
        //         $res = signUp($login, $pas_hash, $mail);
        //         if ($res === true)
        //         {
        //             session_start();
        //             $_SESSION['user'] = $login;
        //             $alert_message = "Пользователь ".$login." успешно зарегистрирован.";
        //             function_alert($alert_message);
        //             header("Location:/index.php?registered=yes");
        //             die();
        //         }

        //     $errorMsg = $res;
        //     }
        // }
    }
}
?>
<body>

        <div class = "log_container">
            <div class = "log_box">
           
            <a class = "reg_button" href  = "/log_in.php">ВОЙТИ</a>
            <?php
                if ($errorMsg !== "")
                    echo "
                    <div class='login__error'>
                        ! $errorMsg <br>
                    </div>
                    ";
            ?>
            <!-- <form>
                <input class = "log_back_button" type="button" value="РЕГИСТРАЦИЯ" onclick="history.back()">
            </form> -->
                <form class ="log_label_box"  action="sign_up.php" method="post">
                            <label class ="log_label_box_labels" for="login">ЛОГИН:</label>
                            <input id="login" class = "login" name="userData_login" type="text">
                            <label class ="log_label_box_labels" for="password">ПАРОЛЬ:</label>
                            <input id="password" class = "password" name="userData_password" type="password">
                            <label class ="log_label_box_labels" for="password">E-MAIL:</label>
                            <input id="mail" class = "mail" name="userData_mail" type="mail">
                            <input name="submit" class="submit_button" type="submit" value = "ЗАРЕГИСТРИРОВАТЬСЯ">
                    </form>      
        </div>            
</div> 

</body>
</html>