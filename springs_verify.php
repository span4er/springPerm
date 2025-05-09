

<!DOCTYPE html>
<html>
<head>
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/auth.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">

<title>Родники</title>
</head>
<body>
<?php require_once 'UI/header.php'; ?>
<?php
    require_once 'php/configuration.php';
    require_once __ROOT__.'\php\get_springs.php';
    require_once __ROOT__.'\php\alert.php';
    require_once __ROOT__.'\php\userHandler.php';

    $spring_data = get_springs_to_verify();
    // Поверка, есть ли GET запрос
    if (isset($_GET['pageno'])) {
        // Если да то переменной $pageno присваиваем его
        $pageno = $_GET['pageno'];
    } else { // Иначе
        // Присваиваем $pageno один
        $pageno = 1;
    }
    $user_role = -1;

    if (isset($_SESSION['user']) == true){
        $user_role = get_user_role($_SESSION['user']);     
        $user_role = $user_role[0]['user_role_id']; 
    }


 
// Назначаем количество данных на одной странице
$size_page = 5;
// Вычисляем с какого объекта начать выводить
$offset = ($pageno-1) * $size_page;
    
    ?>
<div class ="container">
<?php if($user_role == '99' || $user_role == '100'){ ?>
<h1  class = "spring_head">Родники для утверждения</h1>
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
            <?php
                $result = get_count_springs(1);
                if($result[0]['count_springs'] > 0){
                $total_rows = $result[0]['count_springs'];
                $total_pages = ceil($total_rows / $size_page);
                $res_data = get_outset_springs($offset, $size_page,1);
                foreach ($res_data as $row){
                    ?>
                     <tr class = "spring_tab">
                        <td >
                        <a  href="/spring_selected_verify.php?spring=<?=$row['spring_id']?>" ><?= $row['spring_name']?></a>
                    </td>
                    </tr>
                    <?php 
                }
            }
            else {?><tr class = "spring_tab">
            <td ><a>Родников для утверждения нет</a>      
            </td>
                    </tr>     
                    <?php }?>         
        </table>
        <ul class="spring_pagination">
            <li><a href="?pageno=1">Первая ст.</a></li>
            <li class="<?php 
                if($pageno <= 1){                
                    echo 'disabled'; 
                }                
                ?>">
                <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Предыдущая ст.</a>
            </li>
            <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Следующая ст.</a>
            </li>
            <li><a href="?pageno=<?php echo $total_pages; ?>">Последняя ст.</a></li>
        </ul> 
</div>
        <div class = "springs_box2">
            <div id="map" style="width: 100%; height:600px"></div>
            <script src="https://api-maps.yandex.ru/2.1/?lang=ru-RU"  type="text/javascript"></script>
            <script type="text/javascript">
               ymaps.ready(init);
                function init() {
                    var myMap = new ymaps.Map("map", {
                        center: [<?php echo $spring_data[0]['spring_point']; ?>],
                        zoom: 20
                    }, {
                    });
                
                    var myCollection = new ymaps.GeoObjectCollection(); 
                
                    <?php foreach ($spring_data as $row): ?>
                    var myPlacemark = new ymaps.Placemark([
                        <?php echo $row['spring_point']; ?>
                    ], {
                        balloonContentHeader: '<?= "<a class = \"spring_title_map\"  href=\""."/spring_selected_verify.php?spring=".$row['spring_id']."\" >".$row['spring_name']."</a>" ?>',
                    }, {
                        preset: 'islands#icon',
                        iconColor: '#0000ff',
                        iconLayout: 'default#image',
                        //iconImageHref: '<?= __ROOT__."\\resources\\img\\spring.png"?>',
                        iconImageHref: '<?= "/resources/img/spring.png"?>',
                        iconImageSize: [80, 80],
                        iconImageOffset: [-15, -44]
                    });
                    myCollection.add(myPlacemark);
                    <?php endforeach; ?>
                
                    myMap.geoObjects.add(myCollection);
                    
                    myMap.setBounds(myCollection.getBounds(),{checkZoomRange:true, zoomMargin:9});
                }
            </script>
        </div>
        <?php }
        else {?>
        <p class = "spring_head" style ="color:red" >Нет доступа</p>
        <?php } ?>
    </div>
</div>
</body>
</html>


