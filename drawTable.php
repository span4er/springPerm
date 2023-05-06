<?php

function draw_table($DB_arr,$table){

    // try {
    //     $DB_arr = executeSQL($query);
    // } catch (Exception $e) {
    //     $DB_arr = [];
    // }
$header = $DB_arr[0];

$htmlString = "";
$htmlString = "<th class='title2'></th>";
$counterHead = 0;
    foreach ($header as $key => $value) {
        if($counterHead == 0)
        {
            $htmlString .= "<input name=\"main_col\" type=\"hidden\" value=\"$key\">";
            $counterHead = $counterHead +1;
        }
    $htmlString .=  "<th class='title2'>";

    $htmlString .=  $key;

    $htmlString .=  "</th>";
}

 foreach ($DB_arr as $row){
    $htmlString .= "<tr class = \"all_table_tr\">";         
    $counter = 0;
    foreach ($row as $elem)
        {
            if($counter == 0){
                $htmlString.="<td><input type=\"checkbox\" name = \"delete[]\" value = $elem></td>";
                
            }
            $htmlString .= "<td class =\"td_text\">$elem</td>";     
            $counter = $counter +1;
        }              
    $htmlString .= "</tr>";
 }
$htmlString .= "</table>";
$htmlString .= "<input name=\"table\" type=\"hidden\" value=\"$table\">";
return $htmlString;
}