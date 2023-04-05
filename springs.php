<!DOCTYPE html>
<html>
<head>
<!--<link reL=stylesheet HREF="css/main.css">-->
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">

<title>Родники</title>
</head>
<body>
<?php require_once 'UI/header.php'; ?>
<div class ="container">
    <div class = "springs_container">
        <div class ="springs_box_left">
            <div class = "springs_search">
            <form class ="search_box"  action="springs.php" method="post">
                            <input id="spring_nme" placeholder = "Поиск" class = "spring_name" name="springData_name" type="text">
                            <input name="submit" class="search_button" type="submit" value = "Найти">
                    </form>      
            </div>
        <table class = "springs_box1">
            <thead class = "springs_head">
                <tr>              
                    <th>Родник</th>
                </tr>
            </thead>
            <tr>
                <td>Огненный поток</td>
            </tr>
        </table>
</div>
        <div class = "springs_box2">
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ac23980264113b77ac4418a28a68717c7cbdc074c09288c44801ff6693bca55b4&amp;
            width=100%&amp;height=500&amp;lang=ru_RU&amp;scroll=true"></script>
        </div>
    </div>
</div>
</body>
</html>


