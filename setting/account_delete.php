<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 4)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(filter_has_var(INPUT_GET, 'id')){
        $id = filter_input(INPUT_GET, 'id');
        
        $sql_del =  "UPDATE account ".
                    "SET visible = 0 ".
                    "WHERE id = '$id'";
        mysqli_query($con, $sql_del);
    }
    header("Location: ./account_manage.php");

    mysqli_close($con);
?>
