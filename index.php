<?php
session_start();
// obligatoire
require('vendor/autoload.php');
use Tvart\Facebook\Config;
use Tvart\Facebook\AppInfo;
use Tvart\Facebook\Formater;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\CurlHttpClient;
use Facebook\GraphUser;
use Facebook\GraphAlbum;

$appID       = ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') ? AppInfo::appID()       : Config::appID();
$appSecret   = ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') ? AppInfo::appSecret()   : Config::appSecret();
$redirectUrl = ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') ? AppInfo::redirectUrl() : Config::redirectUrl();


FacebookSession::setDefaultApplication($appID,$appSecret);
$helper = new FacebookRedirectLoginHelper($redirectUrl);

// Add `use Facebook\FacebookCanvasLoginHelper;` to top of file
//$helper = new FacebookCanvasLoginHelper();

if(isset($_GET['logout'])) {
    unset($_SESSION['fb_token']);
}

// enregistrement du token facebook
if(isset($_SESSION) && isset($_SESSION['fb_token'])) {
    $session = new FacebookSession($_SESSION['fb_token']);
    try {
        if(!$session->validate()) $session = null;
    } catch (Exception $e) {
        $session = null;
    }
} else {
    try {
        $session = $helper->getSessionFromRedirect();
    } catch( FacebookRequestException $fre ) {
        echo $fre->getMessage(); // Facebook exception
    } catch( Exception $e ) {
        echo $e->getMessage(); // Other exception
    }
}
$logged = true;
if($session){
    //getSessionFromRedirect
    $_SESSION['fb_token'] = $session->getToken();
    $session = new FacebookSession( $session->getToken() );
}

/* ------- GET USER PROFIL ----------------*/
$user_graph = false;
if($session){
    $user_graph = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className())->asArray();
}

$photos = [
    ["url" => "./public/imgs/abs1.jpeg", "msg" => '<a class="button" href="index.php?switch_tab_content=switch_tab_3&todo=1" ><i class="fa fa-image"></i>Upload it</a>'],
    ["url" => "./public/imgs/abs2.jpeg", "msg" => '<a class="button" href="index.php?switch_tab_content=switch_tab_3&todo=2" ><i class="fa fa-image"></i>Upload it</a>'],
    ["url" => "./public/imgs/abs3.jpeg", "msg" => '<a class="button" href="index.php?switch_tab_content=switch_tab_3&todo=3" ><i class="fa fa-image"></i>Upload it</a>'],
];
$tab_content = $_REQUEST['switch_tab_content'];
$todo = $_REQUEST['todo'];
switch($tab_content){
    /* ------- UPLOAD PHOTOS ----------------*/
    case 'switch_tab_3' :
        if($todo > 0){
            try {
                $response = (new FacebookRequest(
                    $session, 'POST', '/me/photos', array(
                        'source' => new CURLFile($photos[$todo-1]["url"], 'image/jpeg', "test.jpg"),
                        'message' => "I've just uploaded this photo using tvart's fb app framework"
                    )
                ))->execute()->getGraphObject(GraphAlbum::className());
                $photos[$todo-1]["msg"] = "Image Posted <br/>with id: " . $response->getProperty('id');
                //echo "Posted with id: " . $response->getId();
            } catch(FacebookRequestException $e) {
                $photos[$todo-1]["msg"] =  "Exception occured, code: " . $e->getCode();
                $photos[$todo-1]["msg"] .= " with message: " . $e->getMessage();
            }catch( Exception $e){
                $photos[$todo-1]["msg"] =  "Exception occured, code: " . $e->getCode();
                $photos[$todo-1]["msg"] .= " with message: " . $e->getMessage();
            }
        }
        break;
    /* ------- POST LINK ----------------*/
    case 'switch_tab_4' :
        try {
            $response = (new FacebookRequest(
                $session, 'POST', '/me/feed', array(
                    'link' => 'http://fr.wikipedia.org/wiki/%C3%89pict%C3%A8te',
                    'message' => 'This guy is a genius!'
                )
            ))->execute()->getGraphObject();
            echo "Posted with id: " . $response->getProperty('id');
        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
    default :
        break;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Lost in memories - debug</title>
    <link rel="stylesheet" type="text/css" href="./public/css/reset-min.css">
    <link rel="stylesheet" type="text/css" href="./public/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://code.google.com/p/google-code-prettify/source/browse/trunk/src/prettify.css    ">
    <link rel="stylesheet" type="text/css" href="./public/css/style.css">
    <script src="./public/js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="./public/js/script.js" type="text/javascript"></script>
</head>
<body>
<div class="wrapper">
    <div class="maincontent">

        <div class="logo"><img src="./public/imgs/birds.jpeg" width="379" height="60" alt="Lostinmemories logo"></div>

        <ul class="tabs">
            <li><a href="#tab1" onclick="switch_tab_1(); return false;">About</a></li>
            <li><a href="#tab2" onclick="switch_tab_2(); return false;">User Profil</a></li>
            <li><a href="#tab3" onclick="switch_tab_3(0); return false;">Publish Photo</a></li>
            <li><a href="#tab4" onclick="switch_tab_4(); return false;">Publish Feed</a></li>
        </ul>

        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <div class="post">
                    <h3><a href="#">A Facebook HTML/CSS Template</a></h3>
                    <span class="postInfo">by <a href="#">tvart</a> on May 22nd 2015</span>
                    <p>
                        Create your Facebook Apps easily. Re-use and adapt this template for your different projects<br/>
                        This template is fully inspired from this <a href="http://webdesign.tutsplus.com/articles/design-and-code-an-integrated-facebook-app-html-css--webdesign-4378">article</a>
                        You are strongly encouraged to read this article for more explanations
                    </p>
                    <a class="more" href="#">Read More</a>
                    <span class="line"></span>
                    <a href="#">12 Likes 14 Comments Share</a>
                    <span class="line"></span>
                </div><!--End Blog Post-->

                <div class="post">
                    <h3><a href="#">What you'll find inside</a></h3>
                    <span class="postInfo">by <a href="#">tvart</a> on May 25nd 2015</span>
                    <p>
                        To build strong facebook application you need to use the sdk of your choice.
                        This app use <a href="https://developers.facebook.com/docs/reference/php/4.0.0">facebook/php-sdk-v4</a><br/>
                        In the different parts of this app you are going to be introduced to some basic usage of this SDK<br/>
                        Of course to go far into the details you're invited to read the doc and never stop to practice.
                    </p>
                    <a class="more" href="#">Read More</a>
                    <span class="line"></span>
                    <a href="#">6 Likes 3 Comments 4 Share</a>
                    <span class="line"></span>
                </div><!--End Blog Post-->

            </div><!--End Tab 1-->

            <div id="tab2" class="tab_content display_none">
                <h3>Get User Infos</h3>
                <p>
                    The query to get any information from the Facebook Graph is as follow :
                <ol>
                    <il>Instantiante a FacebookRequest Object, passing in param your FacebookSession, the method, classname</il>
                    <il>Execute your statement</il>
                    <il>Indicate that you want a result like GraphObject ( you can cast the class in the param )</il>
                    <il>Convert the result in array</il>
                </ol>
                </p>
                <p>
                    <code>
                            $graph = (
                                new FacebookRequest($session, 'GET', '/me')
                            )
                            ->execute()
                            ->getGraphObject(GraphUser::className())
                            ->asArray();
                    </code>
                </p>
                <p>
                    If you are loggeg with your account on facebook, you can see bellow the result of the query above
                    <?php
                        if($user_graph){
                            echo "<code>";
                            var_dump($user_graph);
                            echo "</code>";
                        }
                    ?>
                </p>
            </div><!--End Tab 2 -->

            <div id="tab3" class="tab_content display_none">
                <h3>Post Photos</h3>
                <p>
                    <?php
                        if(!empty($photos)){
                    ?>
                <table>
                    <tr>
                        <th style="padding: 12px;">Image 1</th>
                        <th style="padding: 12px;">Image 2</th>
                        <th style="padding: 12px;">Image 3</th>
                    </tr>
                    <tr>
                        <td style="padding: 12px;"><img src="<?php echo $photos[0]["url"]; ?>"></td>
                        <td style="padding: 12px;"><img src="<?php echo $photos[1]["url"]; ?>"></td>
                        <td style="padding: 12px;"><img src="<?php echo $photos[2]["url"]; ?>"></td>
                    </tr>
                    <tr>
                        <td style="padding: 12px;"><?php echo (!$session)? "You have to be logged in" : $photos[0]["msg"]; ?></td>
                        <td style="padding: 12px;"><?php echo (!$session)? "You have to be logged in" : $photos[1]["msg"]; ?></td>
                        <td style="padding: 12px;"><?php echo (!$session)? "You have to be logged in" : $photos[2]["msg"]; ?></td>
                    </tr>
                </table>
                <?php
                    }else{
                    echo "oops!";
                }
                ?>
                </p>
            </div><!--End Tab 3 -->

            <div id="tab4" class="tab_content display_none">
                <h3>Post Feeds</h3>
                <p>
                    Sometimes it happens you want to feed automatically your wall.<br/>
                    You are in this case when you are a professional, and you need to<br/>
                    axe your communication within your facebook page.<br/>
                </p>
                <p>
                    Then imagine you have your own blog, where articles are published frequently.<br/>
                    And as you are lazy you don't want to connect to your facebook page<br/>
                    every time a new article is ready to publish it in your wall.
                </p>
                <p>
                    So, what you gonna do? It's easy! You gonna use this app<br/>
                    And what will do this app?
                    <ul>
                        <il>Create a FacebookRequest Object</il>
                        <il>Passing to this object following params:</il>
                        <il>
                            <ol>
                                <li>Your FacebookSession object</li>
                                <li>POST //This is the method of your request</li>
                                <li>Graph model : /me/feed </li>
                                <li>An array with keys : link & message</li>
                            </ol>
                        </il>
                    </ul>
                    <?php if(!$session){
                        echo "<strong>Login to try it!</strong>";
                    }else{
                        echo '<br/><a class="button" href="index.php?switch_tab_content=switch_tab_4&todo=1" ><i class="fa fa-upload"></i> <strong>Feed your wall</strong></a>';
                    }?>
                </p>
            </div><!--End Tab 4 -->
        </div><!--End Tab Container -->

    </div><!--End Main Content-->

    <div class="sidebar">
        <form action="" method="get">
            <input name="search" class="search" placeholder="Nothing to find there..">
        </form>
        <div>
            <?php
            if ( isset( $session ) ) {

                $user_name = ($user_graph)? $user_graph['name']." " : "";
                $user_photo = "http://graph.facebook.com/{$user_graph['id']}/picture";
                echo '<img src="'.$user_photo.'" align="center" /> ';
                echo 'Welcome '.$user_name.' ';
                echo '<a class="button" href="' . $helper->getLogoutUrl( $session, $redirectUrl.'?logout=true' ) . '"><i class="fa fa-sign-out"></i>Logout</a>';
            }else{
                echo '<a class="button" href="' . $helper->getLoginUrl( ['email', 'public_profile','publish_actions','user_friends' ] ) . '"><i class="fa fa-sign-in"></i>Login</a>';
            }
            ?>
        </div>
        <!--<div id="fb-root"></div>
        <script src="http://connect.facebook.net/en_US/all.js#appId=<?php echo $appID?>&amp;xfbml=1"></script>
        <fb:like href="http://apps.facebook.com/fbtuttts"layout="button_count" width="75" show_faces="true" action="like" font="lucida grande"></fb:like>-->
        <!--<a class="button right" href="#"><span class="buttonimage left"></span>Logout</a>-->
        <div class="tabHeader">Categories</div>
        <ul>
            <li><a href="#">About</a></li>
            <li><a href="#">User Profil</a></li>
            <li><a href="#">Publish photo</a></li>
            <li><a href="#">Publish feed</a></li>
        </ul>
        <div class="tabHeader">Links</div>
        <a class="button" href="https://github.com/TVart/Fbapp" target="_blank"><i class="fa fa-github"></i> Fork me </a><br/><br/>
        <a class="button" href="https://twitter.com/tvartOfficial" target="_blank"><i class="fa fa-twitter"></i> Follow me </a><br/><br/>
        <a class="button" href="http://webdesign.tutsplus.com/articles/design-and-code-an-integrated-facebook-app-html-css--webdesign-4378" target="_blank"><i class="fa fa-file"></i> Read me </a>
    </div><!--End Sidebar-->
</div><!--End Wrapper -->
</body>
</html>