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

        if(isset($_GET['toDelete']) && isset($_POST['id']) && $user_role == '99'){
            $id = $_POST['id'];
            unset($_POST['id']);
            $is_deleted = delete_comment($id);            
            if($is_deleted = '1') header("Location:/spring_selected.php?spring=".$spring_id); 
            $errorMsg = $is_deleted;
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
            <p><?=$spring_data[0]['spring_description']; ?></p>

    </div>
    <div class = "drinking_how">
            <p style="color:red;text-align:center"><?=$spring_data[0]['quality_name']; ?></p>
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
     <?php
            if ($errorMsg !== "" && !preg_match("%session_start%",$errorMsg)){
                ?>
                <div class='login__error'>
                    ! <?=$errorMsg?><br>
                </div>
                <?php
            }
        ?>
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
        <?php if($user_role == '99'){?>
            <form class="hide-submit" action='/spring_selected.php?spring=<?= $spring_id?>&toDelete=1' method='post'>      
            <input type='hidden' name='id' value="<?=intval($row['comment_id']) ?>" />
            <input type='submit' value='Удалить'>
            <div class = "comment_right">
                <label>
            <input type="submit" />
            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="100px" height="100px"><path d="M 21 2 C 19.354545 2 18 3.3545455 18 5 L 18 7 L 10.154297 7 A 1.0001 1.0001 0 0 0 9.984375 6.9863281 A 1.0001 1.0001 0 0 0 9.8398438 7 L 8 7 A 1.0001 1.0001 0 1 0 8 9 L 9 9 L 9 45 C 9 46.645455 10.354545 48 12 48 L 38 48 C 39.645455 48 41 46.645455 41 45 L 41 9 L 42 9 A 1.0001 1.0001 0 1 0 42 7 L 40.167969 7 A 1.0001 1.0001 0 0 0 39.841797 7 L 32 7 L 32 5 C 32 3.3545455 30.645455 2 29 2 L 21 2 z M 21 4 L 29 4 C 29.554545 4 30 4.4454545 30 5 L 30 7 L 20 7 L 20 5 C 20 4.4454545 20.445455 4 21 4 z M 11 9 L 18.832031 9 A 1.0001 1.0001 0 0 0 19.158203 9 L 30.832031 9 A 1.0001 1.0001 0 0 0 31.158203 9 L 39 9 L 39 45 C 39 45.554545 38.554545 46 38 46 L 12 46 C 11.445455 46 11 45.554545 11 45 L 11 9 z M 18.984375 13.986328 A 1.0001 1.0001 0 0 0 18 15 L 18 40 A 1.0001 1.0001 0 1 0 20 40 L 20 15 A 1.0001 1.0001 0 0 0 18.984375 13.986328 z M 24.984375 13.986328 A 1.0001 1.0001 0 0 0 24 15 L 24 40 A 1.0001 1.0001 0 1 0 26 40 L 26 15 A 1.0001 1.0001 0 0 0 24.984375 13.986328 z M 30.984375 13.986328 A 1.0001 1.0001 0 0 0 30 15 L 30 40 A 1.0001 1.0001 0 1 0 32 40 L 32 15 A 1.0001 1.0001 0 0 0 30.984375 13.986328 z"/></svg>
                </label>    
            </div>
        </form>
        <?php } ?>
        
    </div>
    <?php }}?>
    
</div>
</div>
</body>
</html>
