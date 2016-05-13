<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 4)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }

    $sql =  "SELECT id,username,level ".
            "FROM account ".
            "WHERE visible = 1 ".
            "ORDER BY level DESC";
    $result = mysqli_query($con, $sql);
    
    $all = array();
    if(is_object($result)){
        while ($row = mysqli_fetch_array($result)) {
            $all[] = $row;
        }
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
        <title>帳號管理</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body><?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="account_manage">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="label label-success text-1">帳號管理</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>編號</th>
                                    <th>帳號</th>
                                    <th>權限</th>
                                    <th><a href="./account_create.php"><span class="label label-warning text-1">新增</span></a></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all as $row) {
                                    if($row[2]/4 >= 1){ $type="管理員";}
                                    else if($row[2]/2 >= 1){ $type="編輯";}
                                    else { $type="未知";}?><tr>
                                <td><?php print $row[0];?></td>
                                <td><?php print $row[1];?></td>
                                <td><?php print $type;?></td>
                                <td><a href="./account_update.php?id=<?php print $row[0];?>"><span class="label label-primary text-1">修改</span></a></td>
                                <td><span class="label label-danger text-1" onclick="return deleteIt('account','<?php print $row[0]; ?>')">刪除</span></td>
                                </tr><?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>