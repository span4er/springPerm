<!DOCTYPE html>
<html>
<head>
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<?php require_once 'UI/header.php'; ?>
<body>
<?php
    require_once 'php/configuration.php';
    require_once __ROOT__.'\php\get_springs.php';
    require_once __ROOT__.'\php\alert.php';
 require_once 'php/signInHandler.php';
 require_once 'php/signUpHandler.php';
  
  $errorMsg = "";

//   ini_set('display_errors', '1');
//   ini_set('display_startup_errors', '1');
//   error_reporting(E_ALL);

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

        $errorMsg = signUp($login,$password,$mail);
    }
}
?>
<body>

        <div class = "log_container">
            <div class = "log_box">
           
            <a class = "reg_button" href  = "/log_in.php">ВОЙТИ</a>
            <?php
                if ($errorMsg !== "" && !preg_match("%session_start%",$errorMsg)){
                    ?>
                    <div class='login__error'>
                        ! <?=$errorMsg?><br>
                    </div>
                    <?php
                }
            ?>

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