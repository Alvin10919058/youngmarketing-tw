
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">
                    Setting
                </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 4)){?>
                        
                        <li><a href='account_manage.php'>帳號管理</a></li>
                        <li><a href='system_option.php'>系統設定</a></li>
                        <?php }?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 2)){?>
                        
                        <li><a href='page_manage.php'>頁面管理</a></li>
                        <li><a href='product_manage.php'>產品管理</a></li>
                        <?php }?>
                        
                        <?php if(isset($_SESSION['UID_'.$suffix]) && ($_SESSION['UserType_'.$suffix] >= 1)){?>
                        
                        <li><a>歡迎 <?php print $_SESSION['UserName_'.$suffix]."登入";?></a></li>
                        <li><a href='./logout.php'>Logout</a></li>
                        <?php }?>
                        
                        <?php if(!isset($_SESSION['UID_'.$suffix])){?>
                        <li>
                            <form class="navbar-form form-inline pull-right form-2" role="form" action="login.php" method="post">
                                <div class="form-group">
                                  <label for="un">帳號</label>
                                  <input type="text" class="form-control" id="un" placeholder="Username" name="un" required="">
                                </div>
                                <div class="form-group">
                                  <label for="pw">密碼</label>
                                  <input type="password" class="form-control" id="pw" placeholder="Password" name="pw" required="">
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </li>
                        <?php }?>
                        
                    </ul>
                    
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
