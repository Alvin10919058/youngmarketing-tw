<?php
    if(isset($_SESSION['UID'])){
        header("Location: ./");
    }
    
    session_start();
    
    require_once './mysql_connect.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/HttpClients/FacebookHttpable.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/HttpClients/FacebookCurl.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/HttpClients/FacebookCurlHttpClient.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/Entities/AccessToken.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/Entities/SignedRequest.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookSession.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookRedirectLoginHelper.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookRequest.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookResponse.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookSDKException.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookRequestException.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookOtherException.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/FacebookAuthorizationException.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/GraphObject.php';
    require_once '../../cdn/vendor/facebook/php-sdk-v4/src/Facebook/GraphSessionInfo.php';

    use Facebook\HttpClients\FacebookHttpable;
    use Facebook\HttpClients\FacebookCurl;
    use Facebook\HttpClients\FacebookCurlHttpClient;

    use Facebook\Entities\AccessToken;
    use Facebook\Entities\SignedRequest;

    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookResponse;
    use Facebook\FacebookSDKException;
    use Facebook\FacebookRequestException;
    use Facebook\FacebookOtherException;
    use Facebook\FacebookAuthorizationException;
    use Facebook\GraphObject;
    use Facebook\GraphSessionInfo;

    FacebookSession::setDefaultApplication('591899370939286','b704b8509c78bef509d51680558e4b04');
    $helper = new FacebookRedirectLoginHelper( 'http://youngfb.com/youngweb/setting/login_fb.php' );
    
    if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
        // create new session from saved access_token
        $session = new FacebookSession( $_SESSION['fb_token'] );
        // validate the access_token to make sure it's still valid
        try {
            if ( !$session->validate() ) {
            $session = null;
            }
        } catch ( Exception $e ) {
            $session = null;
        }
    }
    
    if ( !isset( $session ) || $session === null ) {
        // no session exists
        try {
            $session = $helper->getSessionFromRedirect();
        } catch( FacebookRequestException $ex ) {
            print_r( $ex );
        } catch( Exception $ex ) {
            print_r( $ex );
        }
    }

    if ( isset( $session ) ) {
        // save the session
        $_SESSION['fb_token'] = $session->getToken();
        // create a session using saved token or the new one we generated at login
        $session = new FacebookSession( $session->getToken() );
        
        $userobj = (new FacebookRequest(
            $session, 'GET', '/me'
        ))->execute()->getGraphObject();
        $id = $userobj->getProperty('id');
        $email = $userobj->getProperty('email');
        $name = $userobj->getProperty('name');

        $sql = "SELECT asu_id,type FROM account WHERE asu_id = '$id'";
        $result = mysqli_query($con, $sql);
        if(is_object($result)){
            $row = mysqli_fetch_row($result);
            mysqli_free_result($result);
        }
        
        //get last ip
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
           $lastip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
           $lastip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
           $lastip = $_SERVER['REMOTE_ADDR'];
        }

        if($id == $row[0]){//old user
            //update
            $sql_old_acc = 
                "UPDATE account ".
                "SET email='$email',name='$name',lastip='$lastip' ".
                "WHERE asu_id = '$id'";
            mysqli_query($con, $sql_old_acc);
            $_SESSION['UID'] = $id;
            $_SESSION['UserType'] = $row[1];
            header("Location: ./");
        }else{//new user
            //create
            $sql_new_acc = 
                "INSERT INTO account (asu_id,email,name,lastip) ".
                "VALUES('$id','$email','$name','$lastip')";
            mysqli_query($con, $sql_new_acc);
            //set session
            $result_n = mysqli_query($con, $sql);//use sql@line 41 instead of sql_n
            if(is_object($result_n)){
                $row_n=mysqli_fetch_row($result_n);
                mysqli_free_result($result_n);
            }
            //print_r($row_n);
            $_SESSION['UID'] = $id;
            $_SESSION['UserType'] = $row_n[1];
            header("Location: ./");
        }
    }else { //without fb session
        $scope = array('email');
        $url = "Location: ".$helper->getLoginUrl($scope);
        header($url);
    }
    mysqli_close($con);
?>