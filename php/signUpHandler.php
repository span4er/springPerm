<?php
require_once 'configuration.php';
require_once __ROOT__.'\php\DB.php';
require_once __ROOT__.'\php\alert.php';
require_once __ROOT__.'\php\signInHandler.php';

function signUpDb($login, $password, $mail){
    $query = "INSERT INTO spring_perm.dim_users
    (user_login, user_password, user_mail, user_role_id)
    VALUES('$login', '$password', '$mail', 0);";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
        // return "Не удалось подключиться к Базе данных";
        return $e;
    }

    if ($data === TRUE){
        return true;
    }
    else
        return "Пользователь не найден";



}

function checkLoginAvailable($login)
{
    $query = "SELECT user_login from spring_perm.dim_users where user_login = '$login'";
    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return 0;
    }
    else return 1;
}

function checkMailAvailable($mail)
{
    $query = "SELECT user_mail from spring_perm.dim_users where user_mail = '$mail'";
    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return 0;
    }
    else return 1;
}

function signUp($login, $password, $mail){
        //Проверка доступности логина и почты
        if(!checkLoginAvailable($login))
        {
            $errorMsg = "Введённый логин ";
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
                if(!isset($_SESSION)) 
                { 
                    session_start(); 
                } 
                $_SESSION['user'] = $login;
                $alert_message = "Пользователь ".$login." успешно зарегистрирован.";
                function_alert($alert_message);
                header("Location:/index.php?registered=true");
           
                die();
            }

        $errorMsg = $res;
       
        }        
    }
    return $errorMsg;
}