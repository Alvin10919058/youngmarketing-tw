<?php
    session_start();
    
    if(isset($_SESSION['UID']) && ($_SESSION['UserType'] >= 2)){
        require_once './mysql_connect.php';
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }

    $sql =  "SELECT id,image,link,stmt ".
            "FROM ad ".
            "WHERE visible = 1 ".
            "ORDER BY id DESC";
    $result = mysqli_query($con, $sql);

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
        <title>行銷案例-臉書廣告管理</title>
        <?php require_once('./include_lib.php');?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="ad_manage">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="label label-success text-1">行銷案例-臉書廣告管理</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>編號</th>
                                    <th>圖片</th>
                                    <th>連結</th>
                                    <th>說明</th>
                                    <th><a href="./ad_create.php"><span class="label label-warning text-1">新增</span></a></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all as $row) {?><tr>
                                <td><?php print $row[0];?></td>
                                <td><a class="result" href='../images/<?php print $row[1];?>'>
                                    <img alt="image" name="result" width="60" height="60"
                                         src="../images/<?php print $row[1];?>">
                                    </a>
                                </td>
                                <td><a href="<?php print $row[2];?>" target="_blank">Link</a></td>
                                <td><?php print nl2br($row[3]);?></td>
                                <td><a href="./ad_update.php?id=<?php print $row[0];?>"><span class="label label-primary text-1">修改</span></a></td>
                                <td><span class="label label-danger text-1" onclick="return deleteIt('ad','<?php print $row[0]; ?>')">刪除</span></td>
                                </tr><?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
