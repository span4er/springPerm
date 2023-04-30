<!DOCTYPE html>
<html>
<head>
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">
    <meta charset="UTF-8">
    <title>Авторизация</title>
</head>
<body>
<?php require_once 'UI/header.php'; ?>
<?php

 require_once 'php/signInHandler.php';

  $errorMsg = "";
if (isset($_GET['denied']))
{
    $errorMsg = "Авторизируйтесь для продолжения!!";
}

if (isset($_SESSION['user']) == true)
{
    header("Location:/index.php");
    die();
}


if (isset($_POST['userData_login']) && isset($_POST['userData_password'])){
    $login = $_POST['userData_login'];
    $password = $_POST['userData_password'];

    unset($_POST['userData_login']);
    unset($_POST['userData_password']);

    $res = signIn($login, $password);
    if ($res === true)
    {
        session_start();
        $_SESSION['user'] = $login;
        header("Location:/index.php");
        die();
    }

    $errorMsg = $res;
}

?>
<body>
        <div class = "log_container">
            <div class = "log_box">
           
            <a class = "reg_button" href  = "/sign_up.php">РЕГИСТРАЦИЯ</a>
            <?php
                if ($errorMsg !== "")
                    echo "
                    <div class='login__error'>
                        ! $errorMsg <br>
                    </div>
                    ";
            ?>
                <form class ="log_label_box"  action="log_in.php" method="post">
                            <label class ="log_label_box_labels" for="login">ЛОГИН:</label>
                            <input id="login" class = "login" name="userData_login" type="text"  required>
                            <label class ="log_label_box_labels" for="password">ПАРОЛЬ:</label>
                            <input id="password" class = "password" name="userData_password" type="password"  required>
                            <input name="submit" class="submit_button" type="submit" value = "ВОЙТИ">
                    </form>      
        </div>            
</div> 

</body>
</html>