<?php
session_start();
if(!$_SESSION['user'])
    header("Location:/admin/admin.php?denied=true");

