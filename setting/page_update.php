<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(isset($_POST["submitbtn"]) &&
       filter_has_var(INPUT_POST, 'id')){
        $id = filter_input(INPUT_POST, 'id');
        $name = filter_input(INPUT_POST, 'name');
        $url = filter_input(INPUT_POST, 'url');
        $stop_date = filter_input(INPUT_POST, 'stop_date');
        $content_post = filter_input(INPUT_POST, 'content');
        $pages_url = filter_input(INPUT_POST, 'pages_url');
        $yt_loca = filter_input(INPUT_POST, 'yt_loca');
        $yt_id = filter_input(INPUT_POST, 'yt_id');
        $line_url_enable = filter_input(INPUT_POST, 'line_url_enable');
        $line_url = filter_input(INPUT_POST, 'line_url');
        $unload_msg_enable = filter_input(INPUT_POST, 'unload_msg_enable');
        $unload_msg = filter_input(INPUT_POST, 'unload_msg');
        $remainder = filter_input(INPUT_POST, 'remainder');
        $remainder_ff = filter_input(INPUT_POST, 'remainder_ff');
        $remainder_fs = filter_input(INPUT_POST, 'remainder_fs');
        $remainder_color = filter_input(INPUT_POST, 'remainder_color');
        $cd_head = filter_input(INPUT_POST, 'cd_head');
        $cd_tail = filter_input(INPUT_POST, 'cd_tail');
        $cd_ff = filter_input(INPUT_POST, 'cd_ff');
        $cd_fs = filter_input(INPUT_POST, 'cd_fs');
        $cd_color = filter_input(INPUT_POST, 'cd_color');
        $fbds = htmlspecialchars($_POST['fbds'],ENT_QUOTES,'UTF-8');
        $gform = filter_input(INPUT_POST, 'gform');
        
        $content = explode(",", $content_post);
        //delete old relation
        $sql_del = "DELETE ".
                   "FROM page_have_product ".
                   "WHERE page_id = ".$id;
        mysqli_query($con, $sql_del);
        //create new relation
        foreach ($content as $i=>$ctnt){
            $sql_crt = "INSERT INTO page_have_product (page_id,product_id,sequence) ".
                       "VALUES($id,$ctnt,$i)";
            mysqli_query($con, $sql_crt);
        }
        
        $sql =  "UPDATE page ".
                "SET name = '$name',url = '$url',stop_date = '$stop_date',pages_url = '$pages_url', ".
                "yt_loca = '$yt_loca',yt_id = '$yt_id',line_url_enable = '$line_url_enable',line_url = '$line_url', ".
                "unload_msg_enable = '$unload_msg_enable',unload_msg = '$unload_msg', ".
                "remainder = '$remainder',remainder_ff = '$remainder_ff',remainder_fs = '$remainder_fs', ".
                "remainder_color = '$remainder_color',cd_head = '$cd_head',cd_tail = '$cd_tail', ".
                "cd_ff = '$cd_ff',cd_fs = '$cd_fs',cd_color = '$cd_color',fbds = '$fbds',gform = '$gform', ".
                "update_date = '".date("Y/m/d H:i:s")."',update_person = '".$_SESSION['UserName_'.$suffix]."'".
                "WHERE id = '$id'";
        mysqli_query($con, $sql);
        
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $WEB_ROOT = realpath(dirname(__FILE__));
        $DS = DIRECTORY_SEPARATOR;
        $sql_get_ext =  "SELECT image_ext ".
                        "FROM page ".
                        "WHERE id = '$id'";
        $result_get_ext = mysqli_query($con, $sql_get_ext);
        if(is_object($result_get_ext)){
            $row = mysqli_fetch_row($result_get_ext);
            mysqli_free_result($result_get_ext);
        }
        $image_ext = $row[0];
        
        $rm = filter_input(INPUT_POST, 'rm_bannerfile');
        //delete image
        if($rm == 1){
            if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$image_ext)){
                unlink($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$image_ext);
            }
            $image_ext = '';
        }
        //create or update image
        if ($_FILES["bannerfile"]["error"] == 0) {
            $temp = explode(".", $_FILES["bannerfile"]["name"]);
            $extension = end($temp);
            if (($_FILES["bannerfile"]["size"] < 5242880) && in_array($extension, $allowedExts) && 
            (($_FILES["bannerfile"]["type"] == "image/gif") || 
             ($_FILES["bannerfile"]["type"] == "image/jpeg") || 
             ($_FILES["bannerfile"]["type"] == "image/jpg") || 
             ($_FILES["bannerfile"]["type"] == "image/pjpeg") || 
             ($_FILES["bannerfile"]["type"] == "image/png") || 
             ($_FILES["bannerfile"]["type"] == "image/x-png"))){

                if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$image_ext)){
                    unlink($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$image_ext);
                }
                move_uploaded_file($_FILES["bannerfile"]["tmp_name"],
                        $WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$extension);
                $image_ext = $extension;
            }
        }
        
        $sql =  "UPDATE page ".
                "SET image_ext ='$image_ext' ".
                "WHERE id = '$id'";
        mysqli_query($con, $sql);
        header("Location: ./page_manage.php");
    }
    
    if(filter_has_var(INPUT_POST, 'id')){
        $id = filter_input(INPUT_POST, 'id');
    }else if(filter_has_var(INPUT_GET, 'id')){
        $id = filter_input(INPUT_GET, 'id');
    }else{
        $id = '0';
        header("Location: ./page_manage.php");
    }
    
    $product=array();
    $sql_product =  "SELECT id,name ".
                    "FROM product ".
                    "WHERE visible = 1";
    $result_product = mysqli_query($con, $sql_product);
    if(is_object($result_product)){
        while ($row = mysqli_fetch_array($result_product)) {
            $product[] = $row;
        }
        mysqli_free_result($result_product);
    }
    $sql_page =  "SELECT id,name,url,stop_date,image_ext,pages_url,yt_loca,yt_id,".
                 "line_url_enable,line_url,unload_msg_enable,unload_msg,".
                 "remainder,remainder_ff,remainder_fs,remainder_color,cd_head,cd_tail,cd_ff,cd_fs,".
                 "cd_color,fbds,create_date,create_person,update_date,update_person,gform ".
                 "FROM page ".
                 "WHERE id = '$id'";
    $result_page = mysqli_query($con, $sql_page);
    if(is_object($result_page)){
        $row = mysqli_fetch_row($result_page);
        mysqli_free_result($result_page);
    }
    $id = $row[0];
    $name = $row[1];
    $url = $row[2];
    $stop_date = $row[3];
    $image_ext = $row[4];
    $pages_url = $row[5];
    $yt_loca = $row[6];
    $yt_id = $row[7];
    $line_url_enable = $row[8];
    $line_url = $row[9];
    $unload_msg_enable = $row[10];
    $unload_msg = $row[11];
    $remainder = $row[12];
    $remainder_ff = $row[13];
    $remainder_fs = $row[14];
    $remainder_color = $row[15];
    $cd_head = $row[16];
    $cd_tail = $row[17];
    $cd_ff = $row[18];
    $cd_fs = $row[19];
    $cd_color = $row[20];
    $fbds = $row[21];
    $create_date = $row[22];
    $create_person = $row[23];
    $update_date = $row[24];
    $update_person = $row[25];
    $gform = $row[26];
    
    $all = array();
    $sql_get_content = "SELECT product_id ".
                       "FROM page_have_product ".
                       "WHERE page_id=".$id." ".
                       "ORDER BY sequence";
    $result_get_content = mysqli_query($con, $sql_get_content);
    if(is_object($result_get_content)){
        while ($row = mysqli_fetch_array($result_get_content)) {
            $all[] = $row[0];
        }
        mysqli_free_result($result_get_content);
    }
    $content = implode("|", $all);
    
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>頁面修改</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="product_update">
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" 
                          action="page_update.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">頁面修改</span>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label"><span class="red">*</span>名稱</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="名稱" required=""
                                       name="name" value="<?php print $name;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="col-sm-2 control-label"><span class="red">*</span>網址</label>
                            <div class="col-sm-3 text-right host">http://ceabys.com.tw/</div>
                            <div class="col-sm-5 path">
                                <input type="text" class="form-control" id="url" placeholder="網址" required=""
                                       name="url" value="<?php print $url;?>">
                            </div>
                            <div class="col-sm-2">
                                <span id="check_url"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stop_date" class="col-sm-2 control-label"><span class="red">*</span>截止時間</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="stop_date" placeholder="截止時間" required=""
                                       name="stop_date" value="<?php print $stop_date;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bannerfile" class="col-sm-2 control-label">Banner圖片</label>
                            <div class="col-sm-8">
                                <input type="file" class="upload" name="bannerfile" id="bannerfile">
                            </div>
                            <input type="hidden" id="rm_bannerfile" name="rm_bannerfile" value="0">
                            <div class="col-sm-2">
                                <div class="btn btn-danger text-center rm" id="bannerfile">刪除圖片</div>
                            </div>
                            <div class="col-sm-10 col-sm-offset-2">
                                <img class="img-responsive" id="bannerfile" alt="preview">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">頁面內容</label>
                            <div class="col-sm-10">
                                <select class="form-control chosen" id="content" multiple>
                                    <?php foreach ($product as $row) {?>
                                        <option value="<?php print $row[0];?>"><?php print $row[1];?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pages_url" class="col-sm-2 control-label"><span class="red">*</span>粉絲團網址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="pages_url" placeholder="粉絲團網址" required=""
                                       name="pages_url" value="<?php print $pages_url;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yt_loca" class="col-sm-2 control-label">youtube影片位置</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="yt_loca" name="yt_loca" required="" title="請選擇...">
                                    <option value="0" <?php if($yt_loca==0){print "selected";} ?>>關閉</option>
                                    <option value="1" <?php if($yt_loca==1){print "selected";} ?>>頁頂</option>
                                    <option value="2" <?php if($yt_loca==2){print "selected";} ?>>頁尾</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yt_id" class="col-sm-2 control-label">youtubed影片id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="yt_id" placeholder="youtubed影片id"
                                       name="yt_id" value="<?php print $yt_id;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gform" class="col-sm-2 control-label"><span class="red">*</span>Google表單</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="gform" placeholder="ex:" required=""
                                       name="gform" value="<?php print $gform;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="line_url" class="col-sm-2 control-label">LineID網址</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="line_url_enable" data-width="100%" name="line_url_enable" title="請選擇...">
                                    <option value="0"<?php if($line_url_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($line_url_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="line_url" placeholder="LineID網址" 
                                       name="line_url" value="<?php print $line_url;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="unload_msg" class="col-sm-2 control-label">視窗關閉前顯示訊息</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="unload_msg_enable" data-width="100%" name="unload_msg_enable" title="請選擇...">
                                    <option value="0"<?php if($unload_msg_enable==0){print "selected";} ?>>關閉</option>
                                    <option value="1"<?php if($unload_msg_enable==1){print "selected";} ?>>開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="unload_msg" placeholder="視窗關閉前顯示訊息" 
                                       name="unload_msg" value="<?php print $unload_msg;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="remainder" placeholder="剩餘組數" required=""
                                       name="remainder" value="<?php print $remainder;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_ff" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數字型</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="remainder_ff" name="remainder_ff" required="" title="請選擇...">
                                    <option></option>
                                    <option value="1" <?php if($remainder_ff==1){print "selected";} ?>>微軟正黑體</option>
                                    <option value="2" <?php if($remainder_ff==2){print "selected";} ?>>標楷體</option>
                                    <option value="3" <?php if($remainder_ff==3){print "selected";} ?>>新細明體</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_fs" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數大小</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="remainder_fs" placeholder="剩餘組數大小" required=""
                                       name="remainder_fs" value="<?php print $remainder_fs;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_color" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數顏色</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control picker" id="remainder_color" placeholder="ex:#f60000" required=""
                                       name="remainder_color" value="<?php print $remainder_color;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_head" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器起始文字</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_head" placeholder="倒數計時器起始文字" required=""
                                       name="cd_head" value="<?php print $cd_head;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_tail" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器結尾文字</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_tail" placeholder="倒數計時器結尾文字" required=""
                                       name="cd_tail" value="<?php print $cd_tail;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_ff" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器字型</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="cd_ff" name="cd_ff" required="" title="請選擇...">
                                    <option></option>
                                    <option value="1" <?php if($remainder_ff==1){print "selected";} ?>>微軟正黑體</option>
                                    <option value="2" <?php if($remainder_ff==2){print "selected";} ?>>標楷體</option>
                                    <option value="3" <?php if($remainder_ff==3){print "selected";} ?>>新細明體</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_fs" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器大小</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_fs" placeholder="倒數計時器大小" required=""
                                       name="cd_fs" value="<?php print $cd_fs;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_color" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器顏色</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control picker" id="cd_color" placeholder="ex:#f60000" required=""
                                       name="cd_color" value="<?php print $cd_color;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fbds" class="col-sm-2 control-label">fb廣告追蹤碼</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="fbds" name="fbds" rows="10"><?php print $fbds;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create_date" class="col-sm-2 control-label">建檔日期</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="create_date" readonly=""
                                       name="create_date" value="<?php print $create_date;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create_person" class="col-sm-2 control-label">建檔人員</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="create_person" readonly=""
                                       name="create_person" value="<?php print $create_person;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_date" class="col-sm-2 control-label">修改日期</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="update_date" readonly=""
                                       name="update_date" value="<?php print $update_date;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_person" class="col-sm-2 control-label">修改人員</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="update_person" readonly=""
                                       name="update_person" value="<?php print $update_person;?>">
                            </div>
                        </div>
                        <input type="hidden" name="content" id="content_submit">
                        <input type="hidden" name="id" value="<?php print $id;?>">
                        <div id="next_step" class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="submitbtn" class="btn btn-primary" onclick="return goSubmit()">確定</button>
                                <a href="./page_manage.php" class="btn btn-danger btn-right">取消</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $('select[multiple].chosen').chosen();
            $(function(){
                $('#content').setSelectionOrder('<?php print $content;?>'.split('|'), true);
                $('#stop_date').datetimepicker({
                    format: 'YYYY/MM/DD HH:mm:ss',
                    locale: 'zh-tw',
                    sideBySide: true,
                    showTodayButton: true,
                    showClear: true,
                    keepOpen: true,
                    minDate: moment("<?php print date("Y/m/d");?>","YYYY/MM/DD")
                });
                $('.picker').colpick({
                    layout:'hex',
                    colorScheme:'dark',
                    submitText:'確定',
                    onChange:function(hsb,hex,rgb,el,bySetColor) {
                            $(el).css('background-color','#'+hex);
                            if(!bySetColor) $(el).val('#'+hex);
                    }
                }).keyup(function(){
                    $(this).colpickSetColor(this.value);
                });
            });
            $('#url').change(function() {
                $.post( "check_url.php",{ url: $(this).val() }, function( data ) {
                    if(data !== '0'){
                        $("#check_url").html("網址已使用!").removeClass('green').addClass('red');
                    }else{
                        $("#check_url").html("網址無人使用!").removeClass('red').addClass('green');
                    }
                });
            });
            <?php if($image_ext != ''){?>
            $('img#bannerfile').attr("src","../images/banner_<?php print $id;?>.<?php print $image_ext;?>");
            <?php }else{?>
            $('img#bannerfile').hide();
            $('.rm[id=bannerfile]').hide();
            <?php }?>
            
            $('.upload').change(showPreviewImage);
            function showPreviewImage() {
                var inputFiles = this.files;
                if(inputFiles === undefined || inputFiles.length === 0) return;
                var inputFile = inputFiles[0];

                var reader = new FileReader();
                reader.onload = function(event) {
                    $('img#bannerfile').show();
                    $('.rm[id=bannerfile]').show();
                    $('img#bannerfile').attr("src", event.target.result);
                };
                reader.onerror = function(event) {
                    alert("ERROR: " + event.target.error.code);
                };
                reader.readAsDataURL(inputFile);
            }
            
            $('.rm').click(rmPreviewImage);
            function rmPreviewImage() {
                $('img#bannerfile').hide();
                $('.rm[id=bannerfile]').hide();
                $('#rm_bannerfile').val('1');
                $('.upload[id=bannerfile]').val('');
            }
            
            function goSubmit(){
                $('#content_submit').val($('#content').getSelectionOrder());
                return true;
            }
        </script>
    </body>
</html>
