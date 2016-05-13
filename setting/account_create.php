<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 4)){
        require_once '../../cdn/password_compat/lib/password.php';
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(filter_has_var(INPUT_POST, 'username') &&
       filter_has_var(INPUT_POST, 'password') &&
       filter_has_var(INPUT_POST, 'level')){
        $username = filter_input(INPUT_POST, 'username');
        $pwd = filter_input(INPUT_POST, 'password');
        $level = filter_input(INPUT_POST, 'level');
        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        
        $sql =  "INSERT INTO account (username,password,level) ".
                "VALUES('$username','$hash','$level')";
        mysqli_query($con, $sql);
        
        header("Location: ./account_manage.php");
    }
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>帳號新增</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="product_create">
                    <form class="form-horizontal" role="form" action="account_create.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">帳號新增</span>
                        </div>
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">帳號</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" placeholder="帳號" required=""
                                       name="username" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">密碼</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" placeholder="密碼" required=""
                                       name="password" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">權限</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="level" name="level" required="" title="請選擇...">
                                    <option></option>
                                    <option value="4">管理員</option>
                                    <option value="2">編輯</option>
                                </select>
                            </div>
                        </div>
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
