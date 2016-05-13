<?php
    session_start();
    require_once './mysql_connect.php';
    
    if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){
        
    }else{
        header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized",true);
        header('Location: ./401.php');
    }
    
    if(isset($_POST["submitbtn"]) &&
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
        for($i=0;$i<$option_sum*2;$i++){
            $opt[] = filter_input(INPUT_POST, 'opt'.($i+1));
        }
        $option_str = implode("|", $opt);
        
        $sql_ins =  "INSERT INTO product (name,yt_enable,yt,head_enable,head,".
                    "title_enable,title,sub_title_enable,sub_title,option_sum,option_str) ".
                    "VALUES('$name','$yt_enable','$yt','$head_enable','$head',".
                    "'$title_enable','$title','$sub_title_enable','$sub_title','$option_sum','$option_str') ";
        mysqli_query($con, $sql_ins);
        $id = mysqli_insert_id($con);
        
        $new_ext = array("","","","","","","","","","");
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $WEB_ROOT = realpath(dirname(__FILE__));
        $DS = DIRECTORY_SEPARATOR;
        
        for($i=0;$i<10;$i++){
            $pf = "productfile_".($i+1);
            $rm = filter_input(INPUT_POST, 'rm_'.$pf);
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
                    if(file_exists($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$extension)){
                        unlink($WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$extension);
                    }
                    move_uploaded_file($_FILES[$pf]["tmp_name"],
                            $WEB_ROOT.$DS."..".$DS."images".$DS."product_".$id."_".($i+1).".".$extension);
                    $new_ext[$i] = $extension;
                }
            }
        }
        $image_ext = implode("|", $new_ext);
        
        $sql_upd =  "UPDATE product ".
                "SET image_ext = '$image_ext'".
                "WHERE id = '$id'";
        mysqli_query($con, $sql_upd);
        
        header("Location: ./product_manage.php");
    }
    
    mysqli_close($con);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>產品新增</title>
        <?php require_once './include_lib.php';?>
    </head>
    <body>
        <?php require_once './header.php';?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <div class="show-content-1" id="product_create">
                    <form class="form-horizontal" role="form"  enctype="multipart/form-data" 
                          action="product_create.php" method="post">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">產品代號</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="產品代號" required=""
                                     name="name">
                            </div>
                        </div>
                        <?php for($i=0;$i<10;$i++){?>
                        <div class="form-group">
                            <label for="productfile_<?php print $i+1;?>" class="col-sm-2 control-label">圖片<?php print $i+1;?></label>
                            <div class="col-sm-8">
                                <input type="file" class="upload" name="productfile_<?php print $i+1;?>" id="productfile_<?php print $i+1;?>">
                            </div>
                            <div class="col-sm-2">
                                <div class="btn btn-danger text-center rm" id="productfile_<?php print $i+1;?>">刪除圖片</div>
                            </div>
                            <div class="col-sm-10 col-sm-offset-2">
                                <img class="img-responsive preview" id="productfile_<?php print $i+1;?>" alt="preview">
                            </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                            <label for="yt" class="col-sm-2 control-label">Youtube影片</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="yt_enable" data-width="100%" name="yt_enable" title="請選擇...">
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
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
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="head" name="head" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">內容</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="title_enable" data-width="100%" name="title_enable" title="請選擇...">
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="title" name="title" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sub_title" class="col-sm-2 control-label">說明</label>
                            <div class="col-sm-2">
                                <select class="selectpicker" id="sub_title_enable" data-width="100%" name="sub_title_enable" title="請選擇...">
                                    <option></option>
                                    <option value="0">關閉</option>
                                    <option value="1">開啟</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="sub_title" name="sub_title" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="option_sum" class="col-sm-2 control-label">選項數量</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" id="option_sum" name="option_sum" title="請選擇...">
                                    <?php for($i=0;$i<=20;$i++){?>
                                    <option value="<?php print $i?>"><?php print $i?></option>
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
                                    
                                </tbody>
                            </table>
                        </div>
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
            var opt_sum = 0;
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
            
            $('.preview').hide();
            $('.rm').hide();
            
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
                var upload = '.upload[id='+$(this).attr('id')+']';
                
                $(preview).hide();
                $(remove).hide();
                $(upload).val('');
            }
        </script>
    </body>
</html>
