<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    $sql =  "SELECT id,name,url,stop_date,image_ext ".
            "FROM page ".
            "WHERE visible = 1 ".
            "ORDER BY id DESC";
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
        <title>頁面管理</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="product_manage">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="label label-success text-1">頁面管理</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>名稱</th>
                                    <th>網址</th>
                                    <th>截止時間</th>
                                    <th>Banner</th>
                                    <th><a href="./page_create.php"><span class="label label-warning text-1">新增</span></a></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all as $row) {?><tr>
                                <td><?php print $row[1];?></td>
                                <td><a href="<?php print $domain.$row[2];?>" target="_blank"><?php print $domain.$row[2];?></a></td>
                                <td><?php print $row[3];?></td>
                                <td><a class="result" href="../images/banner_<?php print $row[0];?>.<?php print $row[4];?>">
                                    <img alt="image" name="result" width="60" height="60"
                                         src="../images/banner_<?php print $row[0];?>.<?php print $row[4];?>">
                                    </a>
                                </td>
                                <td><a href="./page_update.php?id=<?php print $row[0];?>"><span class="label label-primary text-1">修改設定</span></a></td>
                                <td><span class="label label-danger text-1" onclick="return deleteIt('page','<?php print $row[0];?>')">刪除</span></td>
                                </tr><?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
