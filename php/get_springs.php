<?php
require_once 'DB.php';

function get_count_springs()
{
    $query = "SELECT
    count(spring_id) as count_springs
from 
	spring_perm.data_spring se
where 
	se.is_verified = true";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return $data;
        
    }
    else return "Нет данных по родникам";
}


function get_all_springs_main_map()
{
    $query = "SELECT
    se.spring_id as spring_id,
	se.spring_name as spring_name,
	se.spring_description as spring_description,  
	concat(se.spring_longtitude,',',se.spring_latitude) as spring_point
from 
	spring_perm.data_spring se
where 
	se.is_verified = true";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return $data;
        
    }
    else return "Нет данных по родникам";
}


function get_outset_springs($offset, $size_page)
{
    $query = "SELECT
    se.spring_id as spring_id,
	se.spring_name as spring_name
from 
	spring_perm.data_spring se
where 
	se.is_verified = true
    order by se.spring_name
    LIMIT $offset, $size_page";


    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return $data;
        
    }
    else return "Нет данных по родникам";
}



function get_one_spring($spring_id)
{
    $query = "SELECT 
                se.spring_name as spring_name,
                se.spring_description as spring_description
              from  
                spring_perm.data_spring se
              where 
                se.spring_id =".$spring_id;
    try {
        $data = executeSQL($query);
    } catch (Exception $e) {
       return "Не удалось подключиться к Базе данных";
        //return $e;
    }

    if ($data){
        return $data;       
    }
    else 
    {   
        $array = array(
            "spring_name" => "Не выбран",
            "spring_description" => "Выберите родник на карте",
        );
        return $array;
    }


}

function get_one_spring_pic($spring_id){
    $query = "SELECT 
                image_id,
                image_path
            from 
            spring_perm.map_image2spring
            where spring_id = $spring_id
            and is_verified = 1";
    try {
    $data = executeSQL($query);
    } catch (Exception $e) {
    return "Не удалось подключиться к Базе данных";
    //return $e;
    }

    if ($data){
        return $data;       
    }   
    else 
    {   
        return null;
    }


}



?>