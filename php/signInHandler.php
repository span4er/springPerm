<?php
require_once 'DB.php';

function signIn($login, $password){

    $query = "SELECT user_password from spring_perm.dim_users where user_login = '$login'";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        $found = false;
        foreach ($data as $pass){
            //if ($pass['user_password'] === $password)
            if(password_verify($password, $pass['user_password']))
                $found = true;
        }
    }
    else return "Пользователь не найден";

    if ($found)
        return true;
    else
        return "Не верный пароль";
}

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