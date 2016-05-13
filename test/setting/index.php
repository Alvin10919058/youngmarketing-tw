<?php
    session_start();
    require_once './mysql_connect.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>官網後台管理</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <?php if(isset($_SESSION['UID']) && $_SESSION['UserType'] == 1){?>
                <div class="row">
                    <div class="col-xs-12 text-center show-content-3 text-2">
                        請與管理者聯繫取得編輯權限!
                    </div>
                </div>
                <?php }?>
                
                <?php if(isset($_SESSION['UID']) && $_SESSION['UserType'] != 1){?>
                <div class="row">
                    <div class="col-xs-12 text-center show-content-3 text-1">
                        <a href="../fblikevoting/">Facebook Like Voting</a>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </body>
</html>
