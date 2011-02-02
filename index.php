<?php
/*
Plugin Name: Twitter
Plugin URI: http://iantearle.com
Description: Update your Twitter status from within Dreamscape home page.
Author: Ian Tearle
Version: 1.0
Author URI: http://www.iantearle.com/
*/
include_once('xml2array.php');
include_once('twitter.lib.php');
if(!class_exists('SimpleXMLObject')){include_once('simplexml.class.php');}

$twitterUsername = getOption('twitter_username');
$twitterPassword = getOption('twitter_password');

$twitterRss = new TwitterRss($twitterUsername, $twitterPassword);
$curTwitter = new twitter($twitterUsername, $twitterPassword);


$friends = $twitterRss->showUser("xml",$twitterUsername);
$xml=new XML2Array();
$arr=$xml->parse($friends);
$followers=$arr['user']['followers_count']['value'];

if (isset($_POST['twitter_stat'])) {
	$twitter_status = $_POST['twitter_stat'];
	if (strlen($twitter_status) > 0) {
		if( $curTwitter->setStatus($twitter_status) == true)
			printOut(SUCCESS, 'Thanks, your Twitter status has been updated.');
		else
			printOut(FAILURE, 'Twitter is unavailable at this time.');
	} else
		printOut(FAILURE, 'You cannot post blank messages to Twitter.');
	global $output;
}

ozone_action('main_home','twitter_status_updater');

function twitter_status_updater()
{
global $followers;
?>
<!-- /*   Twitter Status Update   //===============================*/ -->
<div id="twitter_status">
	<div id="twitter_status_head">
		<h1><?php echo getOption('twitter_username'); ?></h1>
		<h2>Followers</h2>
	</div>
	<div id="twitter-status-content">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="post">
			<textarea name="twitter_stat" cols="40" class="formfields infields" id="twitter_stat" style="height:50px;" maxlength="140"></textarea>
			<span><?php echo $followers;  ?></span><br />
			<input type="submit" name="submit" id="submit" value="Update"/>
		</form>
		<div id="twitter-status-latest">
			<p><?php  ?></p>
		</div>
	</div>
</div>
<?
}

ozone_action('preferences_menu','twitter_config_menu');

function twitter_config_menu()
{
?>
	<!-- /*   Twitter Staus Settings Menu   //===============================*/ -->
    <h3 class="stretchToggle" title="twitter"><a href="#twitter"><span>Twitter. What are you doing?</span></a></h3>
    <div class="stretch" id="twitter">
    <label for="twitter_username">Username</label>
    <input type="text" name="twitter_username" id="twitter_username" value="<?php echo getOption('twitter_username'); ?>">
	<?php tooltip('Twitter Username', 'Enter your Twitter username or email address'); ?>
     <label for="twitter_password">Twitter Password</label>
    <input type="password" name="twitter_password" id="twitter_password" value="<?php echo getOption('twitter_password'); ?>">
     </div>
	 <?php
}

ozone_action('admin_header','twitter_styles');

function twitter_styles()
{
?>
<style type="text/css">
/*
------------------------------------------------------------
Twitter Status Updater
============================================================
*/
#twitter_status{
padding: 0;
margin:0 10px 1em 10px;
float:left;
clear:both;
width:450px;
}
#twitter_status #twitter_status_head{
background:url(http://expansecms.org/plugin/twitter/twitter-head.png) repeat-x;
height:22px;
-moz-border-radius-topleft:10px;
-moz-border-radius-topright: 10px;
-webkit-border-top-left-radius: 10px;
-webkit-border-top-right-radius: 10px;
border-top-left-radius: 10px;
border-top-right-radius: 10px;
padding:10px;
}
#twitter_status #twitter_status_head h1{
color:#000;
margin:0 0 1em;
padding: 0 0 0.2em 25px;
background:url(http://expansecms.org/plugin/twitter/twitter-logo.png) no-repeat;
width:295px;
float:left;
color:#fff;
font-size:16px;
font-weight:bold;
}
#twitter_status #twitter_status_head h2{
float:left;
text-align: right;
color:#98c7e7;
font-size:12px;
width:100px;
}
#twitter_status #twitter-status-content{
background-color:#1c252a;
-moz-border-radius-bottomleft:10px;
-moz-border-radius-bottomright: 10px;
-webkit-border-bottom-left-radius: 10px;
-webkit-border-bottom-right-radius: 10px;
border-bottom-left-radius: 10px;
border-bottom-right-radius: 10px;
padding:0 10px 10px 10px;
}
#twitter_status #twitter-status-content textarea.infields{
width: 320px!important;
float:left!important;
}
#twitter_status #twitter-status-content span{
float: left;
font-size:30px;
color:#fff;
padding:30px 0 0 0;
margin:0 0 0 10px;
text-align:right;
width:80px;
}
#twitter_status #twitter-status-latest{
clear: both;
}
#twitter_status #twitter-status-latest p{
color:#86b6d8;
font-size:11px;
}
</style>
<?php
}
?>