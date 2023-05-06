<?php
require_once 'DB.php';
require_once 'php\configuration.php';

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
                se.spring_description as spring_description,
                q.quality_name
              from  
                spring_perm.data_spring se
              join 
                spring_perm.dim_spring_quality q
                on se.quality_id = q.quality_id
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
            "quality_name" => "Пусто"
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

function reArrayFiles($file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function insert_new_spring($spring_name, $spring_description, $spring_longtitude, $spring_latitude, $quality_id, $spring_images){
    $whitelist = array("gif", "jpeg", "png", "jpg", "bmp"); 

    $spring_description = nl2br($spring_description);

        //spring_data
        $query = "INSERT INTO  spring_perm.data_spring (spring_name, spring_description, spring_longtitude, spring_latitude, quality_id, is_verified)
        values('$spring_name','$spring_description', '$spring_longtitude', '$spring_latitude', $quality_id, 0);";

        //spring_image

        try {
            $data = executeSQL($query);
        } catch (Exception $e) {
            return $e;
        }

        try{
            $query = "SELECT max(spring_id) as spring_id from spring_perm.data_spring";
            $data1 = executeSQL($query);
            $data1 = $data1[0]['spring_id'];
        } catch (Exception $e) {
            return $e;
        }


        if(isset($spring_images) && strval($_FILES['springData_imgs']['name'][0]) !=""){
        $file_ary = reArrayFiles($spring_images);
        $error = true; 
        $count_image = 0;
        foreach($file_ary as $file){  
            $info = new SplFileInfo($file['name']);
            $file_ext = $info->getExtension();
    
            //===>>>    
            foreach  ($whitelist as  $item) {
                if(strtoupper($item) == strtoupper($file_ext)) $error = false; 
            }
            $count_image = $count_image + 1;
        }
        if($error){
            $errorMsg = 'Не верный формат картинки!';
        }
        else {
    
            for($i = 0;$i<$count_image;$i++){
                $info = new SplFileInfo($file_ary[$i]["name"]);
                $file_ext = $info->getExtension();
                $file_ary[$i]["name"] = "spring_".$data1."_".$i.".".$file_ext;
                move_uploaded_file($file_ary[$i]["tmp_name"], __ROOT__."\\resources\img\springs_pics\\".$file_ary[$i]["name"]);
                $sql = "INSERT into spring_perm.map_image2spring (image_path, spring_id)
                values('/resources/img/springs_pics/".$file_ary[$i]["name"]."',$data1);";

                try {
                    $data = executeSQL($sql);
                    $errorMsg = "Nope";
                } catch (Exception $e) {
                    return $e;
                }	   
            } 	
            return "1";  
        } 
    }
    else return "1";
}




?>