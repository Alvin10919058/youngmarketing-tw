<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 4)){
        require_once '../../cdn/password_compat/lib/password.php';
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }

    if(isset($_POST["submitbtn"]) &&
       filter_has_var(INPUT_POST, 'id') &&
       filter_has_var(INPUT_POST, 'username') &&
       filter_has_var(INPUT_POST, 'password') &&
       filter_has_var(INPUT_POST, 'level')){
        $id = filter_input(INPUT_POST, 'id');
        $username = filter_input(INPUT_POST, 'username');
        $level = filter_input(INPUT_POST, 'level');
        $pwd = filter_input(INPUT_POST, 'password');
        
        if($pwd !== ""){
            $hash = password_hash($pwd, PASSWORD_BCRYPT);
            $sql_set = ",password = '$hash'";
        }else{
            $sql_set = " ";
        }
        
        $sql =  "UPDATE account ".
                "SET username = '$username',level = '$level'".$sql_set.
                "WHERE id = '$id'";
        mysqli_query($con, $sql);
        
        header("Location: ./account_manage.php");
    }
    
    if(filter_has_var(INPUT_POST, 'id')){
        $id = filter_input(INPUT_POST, 'id');
    }else if(filter_has_var(INPUT_GET, 'id')){
        $id = filter_input(INPUT_GET, 'id');
    }else{
        header("Location: ./account_manage.php");
    }
    $sql =  "SELECT username,level ".
            "FROM account ".
            "WHERE id = '$id'";
    $result = mysqli_query($con, $sql);
    if(is_object($result)){
        $row = mysqli_fetch_row($result);
        mysqli_free_result($result);
    }

    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>帳號修改</title>
        <?php require_once('./include_lib.php');?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="account_update">
                    <form class="form-horizontal" role="form" action="account_update.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">帳號修改</span><br><br>
                        </div>
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">帳號</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" placeholder="英文或數字"  required=""
                                     name="username" value="<?php print $row[0];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" placeholder="英文或數字"  placeholder="若不修改請留空白"
                                       name="password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">權限</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="level" name="level" required="" title="請選擇...">
                                    <option></option>
                                    <option value="4" <?php if($row[1]==4){print "selected";} ?>>管理員</option>
                                    <option value="2" <?php if($row[1]==2){print "selected";} ?>>編輯</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php print $id;?>">
                        <div id="next_step" class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="submitbtn" class="btn btn-primary">確定</button>
                                <a href="./account_manage.php" class="btn btn-danger btn-right">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>