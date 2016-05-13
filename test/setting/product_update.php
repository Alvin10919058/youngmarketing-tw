<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(isset($_POST["submitbtn"]) &&
       filter_has_var(INPUT_POST, 'id') &&
       filter_has_var(INPUT_POST, 'name') &&
       filter_has_var(INPUT_POST, 'yt_enable') &&
       filter_has_var(INPUT_POST, 'yt') &&
       filter_has_var(INPUT_POST, 'head_enable') &&
       filter_has_var(INPUT_POST, 'head') &&
       filter_has_var(INPUT_POST, 'title_enable') &&
       filter_has_var(INPUT_POST, 'title') &&
       filter_has_var(INPUT_POST, 'sub_title_enable') &&
       filter_has_var(INPUT_POST, 'sub_title') &&
       filter_has_var(INPUT_POST, 'option_sum')){
        $id = filter_input(INPUT_POST, 'id');
        $name = filter_input(INPUT_POST, 'name');
        $yt_enable = filter_input(INPUT_POST, 'yt_enable');
        $yt = filter_input(INPUT_POST, 'yt');
        $head_enable = filter_input(INPUT_POST, 'head_enable');
        $head = filter_input(INPUT_POST, 'head');
        $title_enable = filter_input(INPUT_POST, 'title_enable');
        $title = filter_input(INPUT_POST, 'title');
        $sub_title_enable = filter_input(INPUT_POST, 'sub_title_enable');
        $sub_title = filter_input(INPUT_POST, 'sub_title');
        $option_sum = filter_input(INPUT_POST, 'option_sum');
        $opt = array();
        for($i=0;$i<$option_sum*2;$i++){
            $opt[] = filter_input(INPUT_POST, 'opt'.($i+1));
        }
        $option_str = implode("|", $opt);
        
        $sql_get_ext =  "SELECT image_ext ".
                        "FROM product ".
                        "WHERE id = '$id'";
        $result_get_ext = mysqli_query($con, $sql_get_ext);
        if(is_object($result_get_ext)){
            $row = mysqli_fetch_row($result_get_ext);
            mysqli_free_result($result_get_ext);
        }
        if($row[0] !== ''){
            $image_ext = explode("|", $row[0]);
        }
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $WEB_ROOT = realpath(dirname(__FILE__));
        $DS = DIRECTORY_SEPARATOR;
        
        for($i=0;$i<10;$i++){
            $pf = "productfile_".($i+1);
            $rm = filter_input(INPUT_POST, 'rm_'.$pf);
            //delete image
            if($rm == 1){
                if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$image_ext[$i])){
                    unlink($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$image_ext[$i]);
                }
                $image_ext[$i] = '';
            }
            //create or update image
            if ($_FILES[$pf]["error"] == 0) {
                $temp = explode(".", $_FILES[$pf]["name"]);
                $extension = end($temp);
                if (($_FILES[$pf]["size"] < 5242880) && in_array($extension, $allowedExts) && 
                (($_FILES[$pf]["type"] == "image/gif") || 
                 ($_FILES[$pf]["type"] == "image/jpeg") || 
                 ($_FILES[$pf]["type"] == "image/jpg") || 
                 ($_FILES[$pf]["type"] == "image/pjpeg") || 
                 ($_FILES[$pf]["type"] == "image/png") || 
                 ($_FILES[$pf]["type"] == "image/x-png"))){
                    if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$image_ext[$i])){
                        unlink($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$image_ext[$i]);
                    }
                    move_uploaded_file($_FILES[$pf]["tmp_name"],
                            $WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$extension);
                    $image_ext[$i] = $extension;
                }
            }
        }
        $image_ext = implode("|", $image_ext);
        
        $sql =  "UPDATE product ".
                "SET name = '$name',image_ext = '$image_ext',head_enable = '$head_enable',head = '$head',".
                "yt_enable = '$yt_enable',yt = '$yt',title_enable = '$title_enable',title = '$title',".
                "sub_title_enable = '$sub_title_enable',sub_title = '$sub_title',".
                "option_sum = '$option_sum',option_str = '$option_str' ".
                "WHERE id = '$id'";
        mysqli_query($con, $sql);
        
        header("Location: ./product_manage.php");
    }
    
    if(filter_has_var(INPUT_POST, 'id')){
        $id = filter_input(INPUT_POST, 'id');
    }else if(filter_has_var(INPUT_GET, 'id')){
        $id = filter_input(INPUT_GET, 'id');
    }else{
        header("Location: ./product_manage.php");
    }
    $sql =  "SELECT name,image_ext,yt_enable,yt,head_enable,head,".
            "title_enable,title,sub_title_enable,sub_title,option_sum,option_str ".
            "FROM product ".
            "WHERE id = '$id'";
    $result = mysqli_query($con, $sql);
    if(is_object($result)){
        $row = mysqli_fetch_row($result);
        mysqli_free_result($result);
    }
    $name = $row[0];
    if($row[1] !== ''){
        $image_ext = explode("|", $row[1]);
    }
    $yt_enable = $row[2];
    $yt = $row[3];
    $head_enable = $row[4];
    $head = $row[5];
    $title_enable = $row[6];
    $title = $row[7];
    $sub_title_enable = $row[8];
    $sub_title = $row[9];
    $option_sum = $row[10];
    $option = array();
    if($row[11] !== ''){
        $option = explode("|", $row[11]);
    }
    
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>產品修改</title>
        <?php require_once('./include_lib.php');?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="account_update">
                    <form class="form-horizontal" role="form"  enctype="multipart/form-data" 
                          action="product_update.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">產品修改</span><br><br>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">產品代號</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="產品代號" required=""
                                     name="name" value="<?php print $name;?>">
                            </div>
                        </div>
                        <?php for($i=0;$i<10;$i++){?>
                        <div class="form-group">
                            <label for="productfile_<?php print $i+1;?>" class="col-sm-2 control-label">圖片<?php print $i+1;?></label>
                            <div class="col-sm-8">
                                <input type="file" class="upload" name="productfile_<?php print $i+1;?>" id="productfile_<?php print $i+1;?>">
                            </div>
                            <input type="hidden" id="rm_productfile_<?php print $i+1;?>" name="rm_productfile_<?php print $i+1;?>" value="0">
                            <div class="col-sm-2">
                                <div class="btn btn-danger text-center rm" id="productfile_<?php print $i+1;?>">刪除圖片</div>
                            </div>
                            <div class="col-sm-10 col-sm-offset-2">
                                <img class="img-responsive" id="productfile_<?php print $i+1;?>" alt="preview">
                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                            <label for="yt" class="col-sm-2 control-label">Youtube影片</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="yt_enable" data-width="100%" name="yt_enable" title="請選擇...">
                                    <option value="0"<?php if($yt_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($yt_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="yt" placeholder="Youtube影片ID" name="yt" value="<?php print $yt;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="head" class="col-sm-2 control-label">標題</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="head_enable" data-width="100%" name="head_enable" title="請選擇...">
                                    <option value="0"<?php if($head_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($head_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="head" name="head" rows="2"><?php print $head;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">內容</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="title_enable" data-width="100%" name="title_enable" title="請選擇...">
                                    <option value="0"<?php if($title_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($title_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="title" name="title" rows="2"><?php print $title;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sub_title" class="col-sm-2 control-label">說明</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="sub_title_enable" data-width="100%" name="sub_title_enable" title="請選擇...">
                                    <option value="0"<?php if($sub_title_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($sub_title_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="sub_title" name="sub_title" rows="2"><?php print $sub_title;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="option_sum" class="col-sm-2 control-label">選項數量</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="option_sum" name="option_sum" title="請選擇...">
                                    <?php for($i=0;$i<=20;$i++){?>
                                    <option value="<?php print $i?>"<?php if($option_sum==$i){print "selected";} ?>><?php print $i?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive table-1">
                            <table class="table table-hover" id="opt">
                                <thead>
                                    <tr>
                                        <th>選項內容(<span class="red">限文字及數字</span>)</th>
                                        <th>金額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($option as $i=>$opt) {
                                    if($i%2 == 0){?>
                                    <tr>
                                    <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" required=""
                                                 name="opt<?php print $i+1;?>" value="<?php print $opt;?>">
                                        </div>
                                    </div>
                                    </td>
                                    <?php }else{?>
                                    <td>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" required=""
                                                 name="opt<?php print $i+1;?>" value="<?php print $opt;?>">
                                        </div>
                                    </div>
                                    </td>
                                    </tr>
                                    <?php }?>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="opt_sum" value="<?php print $option_sum;?>">
                        <input type="hidden" name="id" value="<?php print $id;?>">
                        <div id="next_step" class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="submitbtn" class="btn btn-primary">確定</button>
                                <a href="./product_manage.php" class="btn btn-danger btn-right">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var opt_sum = $('#opt_sum').val();
            $('#option_sum').change(function() {
                var c = $(this).val() - opt_sum;
                if(c > 0){
                    for(i=0;i<c;i++){
                        $("#opt > tbody").append('<tr><td><input type="text" class="form-control" required="" name="opt'+(opt_sum*2+i*2+1)+'"></td><td><input type="text" class="form-control" required="" name="opt'+(opt_sum*2+i*2+2)+'"></td></tr>');
                    }
                }else{
                    for(i=0;i>c;i--){
                        $("#opt tr:last").remove();
                    }
                }
                opt_sum = $(this).val();
            });
            
            <?php $allowedExts = array("gif", "jpeg", "jpg", "png");?>
            <?php for($i=0;$i<10;$i++){?>
            <?php if(in_array($image_ext[$i], $allowedExts)){?>
                
            $('img#productfile_<?php print $i+1;?>').attr("src","../images/product_<?php print $id;?>_<?php print $i+1;?>.<?php print $image_ext[$i];?>");
            <?php }else{?>
                
            $('img#productfile_<?php print $i+1;?>').hide();
            $('.rm[id=productfile_<?php print $i+1;?>]').hide();
            <?php }?>
            <?php }?>
            
            $('.upload').change(showPreviewImage);
            function showPreviewImage() {
                var inputFiles = this.files;
                if(inputFiles === undefined || inputFiles.length === 0) return;
                var inputFile = inputFiles[0];
                var preview = 'img#'+$(this).attr('id');
                var remove = '.rm[id='+$(this).attr('id')+']'

                var reader = new FileReader();
                reader.onload = function(event) {
                    $(preview).show();
                    $(remove).show();
                    $(preview).attr("src", event.target.result);
                };
                reader.onerror = function(event) {
                    alert("ERROR: " + event.target.error.code);
                };
                reader.readAsDataURL(inputFile);
            }
            
            $('.rm').click(rmPreviewImage);
            function rmPreviewImage() {
                var preview = 'img#'+$(this).attr('id');
                var remove = '.rm[id='+$(this).attr('id')+']';
                var rm_tag = '#rm_'+$(this).attr('id');
                var upload = '.upload[id='+$(this).attr('id')+']';
                
                $(preview).hide();
                $(remove).hide();
                $(rm_tag).val('1');
                $(upload).val('');
            }
        </script>
    </body>
</html>