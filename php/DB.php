<?php
require_once 'config.php';
require_once 'str_includes.php';


/**
 * @throws Exception
 */
function executeSQL(string $query){
    global $hostname, $username, $DB_pass, $database; //импортируем внутрь функции из config

    $connection = mysqli_connect($hostname, $username, $DB_pass, $database);


    if ($connection === false) {
        throw new Exception("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error(), -1);
    }
    mysqli_set_charset($connection, "utf8");


    $DB_Data = mysqli_query($connection, $query);

    if ($DB_Data === false) {
        throw new Exception("Произошла ошибка при выполнении запроса", -2);
    }
    if (!str_includes($query, "SELECT")) return true;

    $DB_arr = mysqli_fetch_all($DB_Data, MYSQLI_ASSOC);


    return $DB_arr;
}
