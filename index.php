<!DOCTYPE html>
<html>
<head>
<!--<link reL=stylesheet HREF="css/main.css">-->
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">

<title> Главная страница </title>
</head>
<body>
<?php require_once 'UI/header.php'; ?>
<?php
        require_once 'php/configuration.php';
        require_once 'php/alert.php';   

        if (isset($_GET['registered']))
        {
                unset($_GET['registered']);
                $alert_message = "Пользователь ".$_SESSION['user']." успешно зарегистрирован.";
                function_alert($alert_message);
        }
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $htmlString1 = "";
        ?>
<div class ="container">
<h1  class = "spring_head">Родники пермского края</h1>
    <img class = "main_pictur" src = "/resources/img/indexImag.jpg">
    <div style="text-indent: 40px;" class = "intro">
        <p>Данный информационный ресурс предоставляет информацию о расположении и качестве воды в родниках Пермского края.<br></p>
             <p>Ресурс предоставляет возможность пользователям самостоятельно добавлять информацию о родниках.</p>
</div>
</div>
</body>
</html>
