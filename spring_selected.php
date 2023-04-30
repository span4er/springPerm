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
        if (isset($_GET['spring']))
        {   
            $spring_id = $_GET['spring'];
            $spring_data = get_one_spring($spring_id);      
            $comments_data = get_all_comments($spring_id);     
            $spring_pics_data = get_one_spring_pic($spring_id); 
        }
        if(isset($_POST['comment_text']) && strlen($_POST['comment_text']) > 0)
        {
            $comment_text = $_POST['comment_text'];
            $spring_id = $_POST['comment_spring_id'];
            $user_id = $_POST['comment_user_id'];

            unset($_POST['comment_text']);
            upload_comments($user_id, $spring_id, $comment_text);
            header("Location:/spring_selected.php?spring=".$spring_id);
        }
        

        ?>
<div class ="container">
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
            <?=$spring_data[0]['spring_description']; ?>
    </div>
    <?php 
    if (isset($_GET['spring']))
    { 
    ?>
        <div class ="comment_block">
        
        <form class ="comment_box"  action="spring_selected.php?spring=<?= $spring_id?>" method="post">
        <?php 
        if (isset($_SESSION['user']) == true){
            ?>
            <textarea id="com_text" placeholder = "Напишите свой комментарий"  class = "comment_text" name="comment_text" type="text" maxlength=300 rows = 7  required></textarea>
            <input type="hidden" name="comment_user_id" value="<?=$_SESSION['user']?>">
            <input type="hidden" name="comment_spring_id" value="<?=$spring_id?>">
            <input name="submit" class="comment_send" type="submit" value = "Отправить">
        <?php 
        } 
        else {?>
            <textarea id="com_text" placeholder = "Чтобы оставить комментарий войдите или зарегистрируйтесь." readonly  class = "comment_text_blocked" name="commentData_text" type="text" maxlength=300 rows = 7></textarea>
            <?php }?>
        </form> 
        <?php 
    }?>

    <?php if($comments_data) {foreach ($comments_data as $row){
        $user_data = get_user_data($row['user_login']);
        ?>
    <div class = "user_comments">
        <div class = "user_left">
                <img class = "user_pic" src = "<?="/resources/img/user_pics/".$user_data[0]['user_pic_name'];
                ?>">
             <a class = "user_comment_name"><?=$row['user_login']?></a>
             <a class = "user_comment_date"><?=$row['created_dttm']?></a>
        </div>
        <div class = "comment_text">           
            <!-- <p><?=$row['comment_text']?></p> -->
            <textarea readonly class = "user_text" rows = 6><?=$row['comment_text']?></textarea>
        </div>
    </div>
    <?php }}?>
    
</div>
</div>
</body>
</html>
