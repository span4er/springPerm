<?php
require_once 'configuration.php';
require_once __ROOT__.'\php\DB.php';

function get_user_data($login){
    $sqlString = "SELECT
        *
        from 
        spring_perm.dim_users se
        where user_login = '".$login."'";
    try {
        $data = executeSQL($sqlString);
    } catch (Exception $e) {
       //return "Не удалось подключиться к Базе данных";
        return $e;
    }

    //Проверим наличие аватара на сервере
    // url файла для проверки на существование
    $url = "http://www.springpermdistrict.ru/resources/img/user_pics/".$data[0]['user_pic_name'];
    $urlHeaders = @get_headers($url);
    // проверяем ответ сервера на наличие кода: 200 - ОК
    if(strpos($urlHeaders[0], '200')) {
        
    } else {
        $data[0]['user_pic_name'] = "user_default.png";
    }

    if ($data){
        return $data;       
    }
    else return ;
}


function update_user_img($file, $user_data){
    $whitelist = array("gif", "jpeg", "png", "jpg", "bmp"); 
    $info = new SplFileInfo($_FILES['img_file']['name']);
    $file_ext = $info->getExtension();

    //===>>>
    $error = true; 
    foreach  ($whitelist as  $item) {
        if(strtoupper($item) == strtoupper($file_ext)) $error = false; 
    }
    //<<<===
    if($error){
        $errorMsg = 'Не верный формат картинки!';
    }else{
        $_FILES["img_file"]["name"] = "user_".$user_data[0]['user_id'].".".$file_ext;
        move_uploaded_file($_FILES["img_file"]["tmp_name"], __ROOT__."\\resources\img\user_pics\\".$_FILES["img_file"]["name"]);
        $sql = "UPDATE spring_perm.dim_users
        set user_pic_name = '".$_FILES["img_file"]["name"]."'
        WHERE
            user_id = ".$user_data[0]['user_id'].";"
        or die(mysql_error());
        try {
            $data = executeSQL($sql);
            $errorMsg = "Nope";
        } catch (Exception $e) {
            $errorMsg = $e;
        }	    	
    }
    return $errorMsg;	
}


function get_user_role($login){
        $sqlString = "SELECT
        user_id,
        user_role_id
        from 
        spring_perm.dim_users se
        where user_login = '".$login."'";
    try {
        $data = executeSQL($sqlString);
    } catch (Exception $e) {
    //return "Не удалось подключиться к Базе данных";
        return $e;
    }

    if ($data){
        return $data;       
    }
    else return ;
}

function get_users_roles(){
    $sqlString = "SELECT
    user_id,
    user_login,
    se.user_role_id,
    t.user_role_name
    from 
    spring_perm.dim_users se
    join 
    spring_perm.dim_user_type t
    on se.user_role_id = t.user_role_id";
    try {
    $data = executeSQL($sqlString);
} catch (Exception $e) {
//return "Не удалось подключиться к Базе данных";
    return $e;
}

if ($data){
    return $data;       
}
else return ;
}

function update_role_super($user_id){
    $sqlString = "UPDATE
    spring_perm.dim_users se
    set user_role_id = 99
    where user_id = $user_id;";
    try {
    $data = executeSQL($sqlString);
    return "Nope";
} catch (Exception $e) {
//return "Не удалось подключиться к Базе данных";
    return $e;
}
}
function decline_role_super($user_id){
    $sqlString = "UPDATE
    spring_perm.dim_users se
    set user_role_id = 0
    where user_id = $user_id;";
    try {
    $data = executeSQL($sqlString);
    return "Nope";
} catch (Exception $e) {
//return "Не удалось подключиться к Базе данных";
    return $e;
}
}