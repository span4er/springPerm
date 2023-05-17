<!DOCTYPE html>
<html>
<head>
    <link reL=stylesheet HREF="/resources/styles/main.css">
    <link reL=stylesheet HREF="/resources/styles/normalize.css">
<title> Главная страница </title>
<?php require_once 'UI/header.php'; ?>

</head>
<body>
<?php     
        require_once 'php/configuration.php';
        require_once __ROOT__.'\php\get_springs.php';
        require_once __ROOT__.'\php\alert.php';
        require_once __ROOT__.'\php\comments.php';
        require_once __ROOT__.'\php\userHandler.php';

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $errorMsg = "";
        $spring_data = array(array(
            "spring_name" => "Не выбран",
            "spring_description" => "Выберите родник на карте",
        ));

        if(!isset($_SESSION)) 
        { 
            session_start(); 
        } 

        $comments_data =null;
        $spring_pics_data = null;
        $user_role = null;

        if (isset($_SESSION['user']) == true){
            $user_role = get_user_role($_SESSION['user']);
            $user_role = $user_role[0]['user_role_id'];
        }

        if (isset($_GET['spring']))
        {   
            $spring_id = $_GET['spring'];
            $spring_data = get_one_spring($spring_id,1);      
            $spring_pics_data = get_one_spring_pic($spring_id, 1); 
        }

        if (isset($_POST['ver_to']) && $_POST['ver_to'] == "1")
        {
           // echo "1";
            $errorMsg = verify_spring($spring_id);
            if($errorMsg == "Nope") 
                header("Location:/spring_selected.php?spring=".$spring_id);
        }
        if (isset($_POST['del_to']) && $_POST['del_to'] == "1")
        {
            //echo "1";
            $errorMsg = decline_spring($spring_id);
            if($errorMsg == "Nope") 
                header("Location:/springs_verify.php");
        }

        ?>
<div class ="container">
    
<?php if(($user_role == '99' ||  $user_role == '100') && isset($spring_id)){ ?>
    <form id = "to_verify" action='/spring_selected_verify.php?spring=<?=$spring_id?>' method='post'>      
    <input id = "ver" name ="ver_to" value = "0" type = "hidden">
    <input id = "del" name ="del_to" value = "0" type = "hidden">
</form>
<div style = "text-align:center">
    <button class ="verify_button_spring" onclick="return proverka();">УТВЕРДИТЬ РОДНИК</button>
    <script type="text/javascript">
    function proverka() {
        if (confirm("Подтвердить публикацию родника?")) {        
            let element_ver = document.getElementById('ver'); 
            element_ver.value = "1";
            
            let form = document.getElementById('to_verify');
            form.submit();
        } else {
            return false;
        }
    }
</script>
<button style="margin-top:2%;color:red;" class ="verify_button_spring" onclick="return proverka1();">ОТКЛОНИТЬ РОДНИК</button>
    <script type="text/javascript">
    function proverka1() {
        if (confirm("Удалить информацию о роднике?")) {        
            let element_ver = document.getElementById('del'); 
            element_ver.value = "1";
            
            let form = document.getElementById('to_verify');
            form.submit();
        } else {
            return false;
        }
    }
</script>
</div>

<?php
            if ($errorMsg !== "" && !preg_match("%session_start%",$errorMsg)){
                ?>
                <div class='login__error'>
                    ! <?=$errorMsg?><br>
                </div>
                <?php
            }
        ?>
    <h1  class = "spring_head"><?=$spring_data[0]['spring_name'] ?></h1>
    <div class="slideshow-container">
        <ul class ="holdit">
        <li class ="prev">
            <a onclick="plusSlides(-1)">&#10094;</a>
        </li>
        <li class = "middle">
        <?php if($spring_pics_data) {foreach ($spring_pics_data as $row){?>

            <div class = "mySlides fade">
                <img class ="slide_pictur" src ="<?=$row['image_path']?>">
            </div> 
        <?php 
        }}
        else {?>
         <div class = "mySlides fade">
                <img class ="slide_pictur" src ="/resources/img/springs_pics/spring_default.jpg">
            </div> 
            <?php }?>
        </li>
        <li class = "next">
            <a onclick="plusSlides(1)">&#10095;</a>
        </li>
         </ul>
    </div>
  
    <script src="/resources/js/slides.js"></script>
    
   
    <div style="text-indent: 40px;" class = "intro">
            <p><?=$spring_data[0]['spring_description']; ?></p>

    </div>
    <div class = "drinking_how">
            <p style="color:red;text-align:center"><?=$spring_data[0]['quality_name']; ?></p>
    </div>
    
    
</div>        
    <?php }else {?>
        <p class = "spring_head" style ="color:red" >Нет доступа</p>
        <?php } ?>
</div>
</body>
</html>
