<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(isset($_POST["submitbtn"])){
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
        
        $sql =  "INSERT INTO page (name,url,stop_date,pages_url,yt_loca,yt_id,line_url_enable,line_url,".
                "unload_msg_enable,unload_msg,remainder,remainder_ff,remainder_fs,remainder_color,cd_head,cd_tail,".
                "cd_ff,cd_fs,cd_color,fbds,gform,create_date,create_person,update_date,update_person) ".
                "VALUES('$name','$url','$stop_date','$pages_url','$yt_loca','$yt_id','$line_url_enable','$line_url',".
                "'$unload_msg_enable','$unload_msg','$remainder','$remainder_ff','$remainder_fs','$remainder_color','$cd_head','$cd_tail',".
                "'$cd_ff','$cd_fs','$cd_color','$fbds','$gform','".date("Y-m-d H:i:s")."','".$_SESSION['UserName_'.$suffix].
                "','".date("Y-m-d H:i:s")."','".$_SESSION['UserName_'.$suffix]."') ";
        mysqli_query($con, $sql);
        $id = mysqli_insert_id($con);
        
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $WEB_ROOT = realpath(dirname(__FILE__));
        $DS = DIRECTORY_SEPARATOR;
        
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

                if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$extension)){
                    unlink($WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$extension);
                }
                move_uploaded_file($_FILES["bannerfile"]["tmp_name"],
                        $WEB_ROOT.$DS."..".$DS."images".$DS."banner_".$id.".".$extension);
                
                $sql =  "UPDATE page ".
                        "SET image_ext ='$extension' ".
                        "WHERE id = '$id'";
                mysqli_query($con, $sql);
            }
        }
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
    
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>頁面新增</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="product_update">
                    <form class="form-horizontal" role="form" enctype="multipart/form-data" 
                          action="page_create.php" method="post">
                        <div class="form-group-lg">
                            <span class="label label-success text-1">頁面新增</span>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label"><span class="red">*</span>名稱</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="名稱" required=""
                                       name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="col-sm-2 control-label"><span class="red">*</span>網址</label>
                            <div class="col-sm-3 text-right host">http://ceabys.com.tw/</div>
                            <div class="col-sm-5 path">
                                <input type="text" class="form-control" id="url" placeholder="網址" required=""
                                       name="url">
                            </div>
                            <div class="col-sm-2">
                                <span id="check_url"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stop_date" class="col-sm-2 control-label"><span class="red">*</span>截止時間</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="stop_date" placeholder="截止時間" required=""
                                       name="stop_date" value="<?php print date("Y/m/d", strtotime(" +1 months"));?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bannerfile" class="col-sm-2 control-label">Banner圖片</label>
                            <div class="col-sm-8">
                                <input type="file" class="upload" name="bannerfile" id="bannerfile">
                            </div>
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
                                       name="pages_url" value="<?php print $default_fbpage;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yt_loca" class="col-sm-2 control-label">youtube影片位置</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="yt_loca" name="yt_loca" title="請選擇...">
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">頁頂</option>
                                    <option value="2">頁尾</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="yt_id" class="col-sm-2 control-label">youtubed影片id</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="yt_id" placeholder="youtubed影片id"
                                       name="yt_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gform" class="col-sm-2 control-label"><span class="red">*</span>Google表單</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control picker" id="gform" placeholder="ex:" required=""
                                       name="gform">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="line_url" class="col-sm-2 control-label">LineID網址</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="line_url_enable" data-width="100%" name="line_url_enable" title="請選擇...">
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="line_url" placeholder="LineID網址" name="line_url">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="unload_msg" class="col-sm-2 control-label">視窗關閉前顯示訊息</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="unload_msg_enable" data-width="100%" name="unload_msg_enable" title="請選擇...">
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="unload_msg" placeholder="視窗關閉前顯示訊息" name="unload_msg">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="remainder" placeholder="剩餘組數" required=""
                                       name="remainder" value="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_ff" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數字型</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="remainder_ff" name="remainder_ff" title="請選擇...">
                                    <option></option>
                                    <option value="1">微軟正黑體</option>
                                    <option value="2">標楷體</option>
                                    <option value="3">新細明體</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_fs" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數大小</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="remainder_fs" placeholder="剩餘組數大小" required=""
                                       name="remainder_fs" value="32">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remainder_color" class="col-sm-2 control-label"><span class="red">*</span>剩餘組數顏色</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control picker" id="remainder_color" placeholder="ex:#f60000" required=""
                                       name="remainder_color" value="#f60000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_head" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器起始文字</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_head" placeholder="倒數計時器起始文字" required=""
                                       name="cd_head" value="本檔優惠還剩">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_tail" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器結尾文字</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_tail" placeholder="倒數計時器結尾文字" required=""
                                       name="cd_tail" value="結束!">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_ff" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器字型</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="cd_ff" name="cd_ff" title="請選擇...">
                                    <option></option>
                                    <option value="1">微軟正黑體</option>
                                    <option value="2">標楷體</option>
                                    <option value="3">新細明體</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_fs" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器大小</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="cd_fs" placeholder="倒數計時器大小" required=""
                                       name="cd_fs" value="32">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cd_color" class="col-sm-2 control-label"><span class="red">*</span>倒數計時器顏色</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control picker" id="cd_color" placeholder="ex:#f60000" required=""
                                       name="cd_color" value="#f60000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fbds" class="col-sm-2 control-label">fb廣告追蹤碼</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="fbds" name="fbds" rows="10"></textarea>
                            </div>
                        </div>
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
            $('img#bannerfile').hide();
            $('.rm[id=bannerfile]').hide();
            
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
                $('.upload[id=bannerfile]').val('');
            }
            
            function goSubmit(){
                $('#content_submit').val($('#content').getSelectionOrder());
                return true;
            }
        </script>
    </body>
</html>
