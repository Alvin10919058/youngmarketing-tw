<?php
    session_start();
    
    if(isset($_SESSION['UID']) && ($_SESSION['UserType'] >= 2)){
        require_once './mysql_connect.php';
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }

    if(filter_has_var(INPUT_GET, 'id')){
        $id = filter_input(INPUT_GET, 'id');
        
        $sql_del =  "UPDATE act ".
                    "SET visible = 0 ".
                    "WHERE id = '$id'";
        mysqli_query($con, $sql_del);
    }
    header("Location: ./act_manage.php");

    mysqli_close($con);
?>
