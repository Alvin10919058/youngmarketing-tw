<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix])){
        header("Location: ./");
    }else{
        require_once '../../cdn/password_compat/lib/password.php';
        
        if(filter_has_var(INPUT_POST, 'un') &&
           filter_has_var(INPUT_POST, 'pw')){
            $un = mysqli_real_escape_string($con,filter_input(INPUT_POST, 'un'));
            $pw = mysqli_real_escape_string($con,filter_input(INPUT_POST, 'pw'));

            $sql =  "SELECT id,username,password,level ".
                    "FROM account ".
                    "WHERE username = '$un'";
            
            $result = mysqli_query($con, $sql);
            if(is_object($result)){
                $row = mysqli_fetch_row($result);
                mysqli_free_result($result);
            }
            if(is_array($row)){
                if (password_verify($pw, $row[2])) {
                    /* Valid */
                    $_SESSION['UID_'.$suffix] = $row[0];
                    $_SESSION['UserName_'.$suffix] = $row[1];
                    $_SESSION['UserType_'.$suffix] = $row[3];
                    header("Location: ./");
                } else {
                    /* Invalid */
                    print "<script>alert('請確認帳號密碼.');window.history.back();</script>";
                }
            }else{
                print "<script>alert('請確認帳號密碼.');window.history.back();</script>";
            }
         }
    }
?>