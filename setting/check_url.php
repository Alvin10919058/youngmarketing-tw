<?php
    if(filter_has_var(INPUT_POST, 'url')){
        $url = filter_input(INPUT_POST, 'url');
        
        require_once './mysql_connect.php';
        $sql =  "SELECT COUNT(*) ".
                "FROM page ".
                "WHERE url = '$url' ";
        $result = mysqli_query($con, $sql);
        
        if(is_object($result)){
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
        }
        mysqli_close($con);
        
//        if($row[0] == 1){
//            print "網址已使用!";
//        }elseif($row[0] == 0){
//            print "網址無人使用!";
//        }
        print $row[0];
    }  else {
        header('Location: ./401.php');
    }
