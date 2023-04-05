<?php
require_once 'DB.php';
require_once 'alert.php';

function signUp($login, $password, $mail){
        //Проверка доступности логина и почты
        if(!checkLoginAvailable($login))
        {
            $errorMsg = "Введёнsный логин ";
            $errorMsg .= $login;
            $errorMsg .= " уже занят.";
        }
    else if (!checkMailAvailable($mail))
        {
            $errorMsg = "Введённая почта " ;
            $errorMsg .= $mail;
            $errorMsg .= " уже указана для другого пользователя.";
        }
    else {
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
            {
            $errorMsg = "Введите действительный почтовый адрес";
            }
        else if ( !preg_match('/^[A-Za-z0-9]{5,31}$/', $login) )
        {
            $errorMsg = "Логин не должен содержать пробелов и специальных символов и быть длиной не менее 5 и не более 31 символа!!";
        }
        else if ( !preg_match('/^[A-Za-z0-9]{5,31}$/', $password))
        {
            $errorMsg = "Пароль должен быть длиной не менее 5 и не более 31 символа. Допускается писать только буквы и цифры!!";
        }
        else
        {
            $pas_hash = password_hash($password, PASSWORD_DEFAULT);
            $res = signUpDb($login, $pas_hash, $mail);
            if ($res === true)
            {
                session_start();
                $_SESSION['user'] = $login;
                $alert_message = "Пользователь ".$login." успешно зарегистрирован.";
                function_alert($alert_message);
                header("Location:/index.php");
                die();
            }

        $errorMsg = $res;
        }
    }
}