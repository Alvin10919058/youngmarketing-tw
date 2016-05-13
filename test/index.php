<?php 

    $dbhost = 'localhost';
    $dbuser = 'youngfbc';
    $dbpass = '29SC_Lab49 SISU';
    $dbname = 'youngfbc_rcotc';
    
mysql_connect($dbhost, $dbuser, $dbpass) or die("cannot connect"); //
mysql_select_db($dbname)or die("cannot select DB");//我要從member這個資料庫撈資料
mysql_query("set names utf8");//設定utf8 中文字才不會出現亂碼
$data=mysql_query("SELECT * 
FROM  `test` ");//從member中選取全部(*)的資料

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modern Business - Start Bootstrap Template</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/main.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
         /*導覽列顏色*/
        .navbar-inverse {
            background-color: rgb(230, 97, 9);
            border-color: rgb(222, 111, 7);
        }
        /*導覽列字體顏色設定*/
        .navbar-inverse .navbar-brand {
            color: #FFFFFF;
        }
        .navbar-inverse .navbar-nav>li>a {
            color: #FFFFFF;
            } 
        /*字型設定*/   
        body{  
           
            font-family: Microsoft JhengHei;
            
            } 
        body{  
           
            font-family: Microsoft JhengHei;
            
            }
        .back-to-top {
            position: fixed;
            bottom: 2em;
            right: 0px;
            text-decoration: none;
            color: #000000;
            background-color: rgba(235, 235, 235, 0.80);
            font-size: 12px;
            padding: 1em;
            display: none;
            }

        .back-to-top:hover {    
            background-color: rgba(135, 135, 135, 0.50);
            }
        /* Responsive iFrame */
 
        .responsive-iframe-container {
            position: relative;
            padding-bottom: 76.25%;
            padding-top: 30px;
            height: 0;
            overflow: hidden;
        }
                 
        .responsive-iframe-container iframe,   
        .vresponsive-iframe-container object,  
        .vresponsive-iframe-container embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
  </style> 
</head>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <?php require_once './header.php';?>
    

    <!-- Header Carousel -->
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img class="img-responsive center-block" src="./images/banner1.jpg">
                <!--<div class="fill" style="background-image:url('https://goo.gl/QPiF6t');"></div>-->
                <div class="carousel-caption">
                    <h2>Caption 1</h2>
                </div>
            </div>
            <div class="item">
                <img class="img-responsive center-block" src="https://goo.gl/gIRbjE">
                <!--<div class="fill" style="background-image:url('https://goo.gl/5YhVFg');"></div>-->
                <div class="carousel-caption">
                    <h2>Caption 2</h2>
                </div>
            </div>
            <div class="item">
                 <img class="img-responsive center-block" src="https://goo.gl/CuOJ15">
                <!--<div class="fill" style="background-image:url('https://goo.gl/QPiF6t');"></div>-->
                <div class="carousel-caption">
                    <h2>Caption 3</h2>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
    </header>

    <!-- Page Content -->
    <div class="container">

        <!-- Marketing Icons Section -->
        <div class="row">
            <!--<div class="col-lg-12">
                <h1 class="page-header">
                    Welcome to Modern Business
                </h1>
            </div>-->
            <hr>
            
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><strong>關於洲美</strong></h4>
                    </div>
                    <div class="panel-body">
                        <p>創立時間：2013年4月2日<br>
                            授證日期：2013年6月11日<br>
                            創社社長：章金元CP Michael<br>
                            14-15年度社長：李榮隆Showcase<br>
                            15-16年度社長：黃素貞Jane</p>
                        <a href="#" class="btn btn-default">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><strong>認識洲美</strong></h4>
                    </div>
                    <div class="panel-body">
                        <p>台北洲美扶輪社<br>
                            (T): 2752-8655#16(M): 0972-701-003<br>
                            (E): choumeitaipei@gmail.com<br>
                            2014-15年度主題:光耀扶輪</p>
                        <a href="#" class="btn btn-default">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><strong>行事曆</strong></h4>
                    </div>
                    <div class="panel-body">
                        <!-- Responsive iFrame -->
                        <div class="responsive-iframe-container">
                            <iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=300&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=zh-tw.taiwan%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FTaipei" style=" border-width:0 " width="328" height="300" frameborder="0" scrolling="no">
                            
                            </iframe>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Section -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">洲美例會</h2>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/4ppSlX" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/xGVOrx" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/gvZEtg" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/2bqkp0" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/hbGzNX" alt="">
                </a>
            </div>
            <div class="col-md-4 col-sm-6">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-portfolio img-hover" src="https://goo.gl/mBQO1k" alt="">
                </a>
            </div>
        </div>
       


        <form action="http://youngmarketing-tw.youngfb.com/test/comments.php" method="post" id="commentform">

                <div class='row' align="center">
                    <div clas='col-xs-12 col-sm-12 col-md-12' style="font-size:25px">
                         What do you think?
                         </div>
                    <table>
                        <tr>
                            <td colspan="3">
                                <div class="commform-textarea">
                                    <textarea name="comment" id="comment" cols="70" rows="7" tabindex="1"></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="commform-author">
                                <p>Name <span>(required)</span></p>
                                <div>
                                    <input type="text" name="author" id="author" tabindex="2" />
                                </div>
                            </td>
                            <td class="commform-email">
                                <p>Email <span>(required)</span></p>
                                <div>
                                    <input type="text" name="email" id="email" tabindex="3" />
                                </div>
                            </td>
                            <td class="commform-url">
                                <p>Website</p>
                                <div>
                                    <input type="text" name="url" id="url" tabindex="4" />
                                </div>

                            </td>
                        </tr>
                    </table>
                
            </div>

                <div> <br></div>
                <!--<p class="comment_message"><small><strong>XHTML:</strong> You can use these tags: <code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </code></small></p>-->

                <div class="submit clear" align="center">
                    <input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
                    <p id="cancel-comment-reply"><a rel="nofollow" id="cancel-comment-reply-link" href="http://youngmarketing-tw.youngfb.com/test/index.php" style="display:none;">Click here to cancel reply.</a></p>
                </div>
                    
                <div><input type='hidden' name='comment_post_ID' value='2526' id='comment_post_ID' />
<input type='hidden' name='comment_parent' id='comment_parent' value='0' />
<p style="display: none;"><input type="hidden" id="akismet_comment_nonce" name="akismet_comment_nonce" value="5c343d4ccb" /></p><p style="display: none;"><input type="hidden" id="ak_js" name="ak_js" value="120"/></p></div>

            </form>





<hr>
         




        <!-- Call to Action Section -->
            <div class="row">
                <div class="col-md-6">
                   <div class="fb-page" data-href="https://www.facebook.com/choumeitaipei.org" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                        <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="https://www.facebook.com/choumeitaipei.org">
                                <a href="https://www.facebook.com/choumeitaipei.org">台北洲美扶輪社Rotary Club Of Taipei Choumei</a>
                        </blockquote>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel-body"style="   padding: 0px;">
                    <div class="responsive-iframe-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m8!1m3!1d225.92826555273305!2d121.55117242079737!3d25.03908776319468!3m2!1i1024!2i768!4f13.1!4m6!3e6!4m0!4m3!3m2!1d25.038873!2d121.55156!5e0!3m2!1szh-TW!2stw!4v1439879614648" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    </div>
                </div>
            </div>
      


                <hr>





        
        <?php require_once './footer.php';?>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>
