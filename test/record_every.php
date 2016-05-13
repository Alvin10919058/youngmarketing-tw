<?php
    require_once './setting/mysql_connect.php';
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
    if(count($all)%4 !== 0){
        $all[] = [];
    }

    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:url" content="<?php print "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>">
        <meta property="og:title" content="象漾行銷">
        <meta property="og:description" content="象漾行銷成立於2013年7月，我們擅用於社群行銷及消費者洞察，精確產出策略與創意，在適當時機點促動消費者購買及改變行為，精準促動以幫助客戶行銷品牌與產品。">
        <meta property="og:site_name" content="象漾行銷官網">
        <meta property="og:image" content="http://www.youngfb.com/images/og_img.png">
        <meta property="fb:app_id" content="591899370939286">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="zh_TW">
        <title>每次例會記錄</title>
        <link rel="shortcut icon" type="image/png" href="images/favicon.png">
        <?php require_once './include_lib.php';?>
        
    </head>
    <body>
    <?php require_once './header.php';?>
        <!--<div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.2";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
            </script>-->



        <div id="fb-root"></div>
            <script>(function(d, s, id) { 
                    var js, fjs = d.getElementsByTagName(s)[0];  
                    if (d.getElementById(id)) return;  
                    js = d.createElement(s); js.id = id;  
                    js.src = "//connect.facebook.net/zh_HK/sdk.js#xfbml=1&version=v2.3";  
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
            </script>

            
        
        <div class="body-wrapper body-type-1">

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>.</bar>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>.</bar>
            </div>
        </div>

            <div class="container-fluid">
                <?php foreach ($all as $i=>$row) {?>
                    <?php if($i%3 == 0){?>
                        <?php if(!$row){?>
                        
                    </div>
                        <?php break;}?>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="fb-post" data-href="<?php print $row[2];?>" data-width="350"></div> 
                            <p class="lead"><?php print nl2br($row[3]);?></p>
                        </div> 
                    <?php }else if($i%3 == 1){?>
                        <?php if(!$row){?>
                        
                    </div>
                        <?php break;}?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="fb-post" data-href="<?php print $row[2];?>" data-width="350"></div> 
                            <p class="lead"><?php print nl2br($row[3]);?></p>
                        </div>
                    <?php }else if($i%3 == 2){?>
                        <?php if(!$row){?>
                        
                    </div>
                        <?php break;}?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="fb-post" data-href="<?php print $row[2];?>" data-width="350"></div> 
                            <p class="lead"><?php print nl2br($row[3]);?></p>
                        </div>
                    </div>
                    <hr>
                    <?php }?>
                <?php }?>
                
            </div>
        </div>
        <?php require_once './footer.php';?>
        
    </body>
</html>
