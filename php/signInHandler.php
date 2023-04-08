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
?>