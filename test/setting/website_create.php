<?php
    session_start();
    
    if(isset($_SESSION['UID']) && ($_SESSION['UserType'] >= 2)){
        require_once './mysql_connect.php';
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(filter_has_var(INPUT_POST, 'link') &&
       filter_has_var(INPUT_POST, 'stmt')){
        $link = filter_input(INPUT_POST, 'link');
        $stmt = filter_input(INPUT_POST, 'stmt');

        $sql =  "INSERT INTO website (link,stmt) ".
                "VALUES('$link','$stmt')";
        mysqli_query($con, $sql);
        $id = mysqli_insert_id($con);

        if(isset($_FILES["websitefile"])){
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $WEB_ROOT = realpath(dirname(__FILE__));
            $DS = DIRECTORY_SEPARATOR;
            if ($_FILES["websitefile"]["error"] == 0) {
                $temp = explode(".", $_FILES["websitefile"]["name"]);
                $extension = end($temp);
                if (($_FILES["websitefile"]["size"] < 5242880) && in_array($extension, $allowedExts) && 
                (($_FILES["websitefile"]["type"] == "image/gif") || 
                 ($_FILES["websitefile"]["type"] == "image/jpeg") || 
                 ($_FILES["websitefile"]["type"] == "image/jpg") || 
                 ($_FILES["websitefile"]["type"] == "image/pjpeg") || 
                 ($_FILES["websitefile"]["type"] == "image/png") || 
                 ($_FILES["websitefile"]["type"] == "image/x-png"))){

                    if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."website_".$id.".".$extension)){
                        unlink($WEB_ROOT.$DS."..".$DS."images".$DS."website_".$id.".".$extension);
                    }
                    move_uploaded_file($_FILES["websitefile"]["tmp_name"],
                            $WEB_ROOT.$DS."..".$DS."images".$DS."website_".$id.".".$extension);

                    $sql =  "UPDATE website ".
                            "SET image ='website_".$id."."."$extension' ".
                            "WHERE id = '$id'";
                    mysqli_query($con, $sql);
                }
            }
        }
        header("Location: ./website_manage.php");
    }
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>行銷案例-社群電商新增</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="website_create">
                    <form class="form-horizontal" role="form" enctype="multipart/form-data"
                          action="website_create.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">行銷案例-社群電商新增</span>
                        </div>
                        <div class="form-group">
                            <label for="Name" class="col-sm-2 control-label">圖片</label>
                            <div class="col-sm-10">
                                <input type="file" name="websitefile" id="websitefile">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Name" class="col-sm-2 control-label">連結</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="link" placeholder="http://exapmple.com"
                                       name="link" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Name" class="col-sm-2 control-label">敘述</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="stmt" name="stmt" rows="10"></textarea>
                            </div>
                        </div>
                        <div id="next_step" class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="submitbtn" class="btn btn-primary">確定</button>
                                <a href="./website_manage.php" class="btn btn-danger btn-right">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
