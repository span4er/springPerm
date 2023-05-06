<?php
require_once 'DB.php';

function get_all_comments($spring_id)
{
    $query = "SELECT
	uc.comment_id,
	uc.spring_id,
	uc.user_id,
	uc.comment_text,
	DATE_FORMAT(uc.created_dttm , '%d/%m/%y %H:%i') as created_dttm,
	du.user_login,
	du.user_pic_name 
from 
	spring_perm.data_user_comment uc 
join 
	spring_perm.dim_users du 
	on du.user_id = uc.user_id 
where 
	uc.spring_id = $spring_id
    and coalesce(uc.is_deleted,0) <> 1
order by uc.created_dttm ASC";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return $data;
        
    }
    else return ;
}

function upload_comments($user_id, $spring_id, $comment_text){
    $query = "INSERT into spring_perm.data_user_comment(user_id, spring_id, comment_text)
    select 
        du.user_id,
        $spring_id,
        '$comment_text'
    from 
        spring_perm.dim_users du
    where 
        du.user_login = '$user_id';";

    try {
        $DB_arr1 = executeSQL($query);
    } catch (Exception $e) {
        $DB_arr1 = [];
        echo "$query </br>";
        echo $e;
        die();
    }


}

function delete_comment($comment_id){

    $query = "UPDATE spring_perm.data_user_comment uc
                set is_deleted = 1
                where uc.comment_id = $comment_id";

    // return $query;
    try {
        $DB_arr1 = executeSQL($query);
        return '1';
    } catch (Exception $e) {
        return $e;
    }
}

?>