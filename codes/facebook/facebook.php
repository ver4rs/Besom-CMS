<?php
/*
  Name: Facebook Page Likes count
	Version : 1
	Author: Linesh Jose
	Url: http://lineshjose.com
	Email: lineshjose@gmail.com
	Donate:  http://bit.ly/donate-linesh
	github: https://github.com/lineshjose
	Copyright: Copyright (c) 2012 LineshJose.com
	
	Note: This script is free; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by 
		the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This script is distributed in the hope 
		that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
		See the  GNU General Public License for more details.

-----------------------------------------------------------

	This php function returns your Facebook Page Likes count
	
	$value: your Facebook page username or ID
	
	Usage: 
			<p>Likes: <strong><?php echo fb_count('LineshJoseDotCom');?></strong></p> // User name
			<p>Likes: <strong><?php echo fb_count('114877608587606');?></strong></p> // Profile ID 
	
*/

/*
function fb_count($value='') 
{ 
	 if($value){
		 $url='http://api.facebook.com/method/fql.query?query=SELECT fan_count FROM page WHERE';
		 if(is_numeric($value)) { $qry=' page_id="'.$value.'"';} //If value is a page ID
		 else {$qry=' username="'.$value.'"';} //If value is not a ID. 
		 $xml = @simplexml_load_file($url.$qry) or die ("invalid operation");
		 $fb_count = $xml->page->fan_count;
		 return $fb_count;
	}else{
		return '0';
	}
}

echo fb_count('171394896253471');
*/


function fb_page_fan ($page_id) {
	//$page_id = "171394896253471";
    $xml = @simplexml_load_file("http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=".$page_id."") or die ("a lot");
    $fans = $xml->page->fan_count;
    return $fans;
}    

//echo fb_page_fan('171394896253471');


/*
function twitter_followers_count ($twitter_username) {
	$url="http://twitter.com/users/show.xml?screen_name=". $twitter_username;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	$xml = new SimpleXMLElement($data);
	$tw_fol_count = $xml->followers_count;
	if ($tw_fol_count == false) { echo 'Error'; }
	else { echo number_format($tw_fol_count); }
}

echo twitter_followers_count('ver4rs');
*/


/*
function getTwitterFollowers($screenName = 'ver4rs')
{
    // some variables
    $consumerKey = 'bzYSYeaDh6q7sVHvgKA4aw';
    $consumerSecret = 'LJjVPWTg9FpcD75xqlNcwOA1ZVjLythPta4ZQQFQ';
    $token = get_option('cfTwitterToken');
 
    // get follower count from cache
    $numberOfFollowers = get_transient('cfTwitterFollowers');
 
    // cache version does not exist or expired
    if (false === $numberOfFollowers) {
        // getting new auth bearer only if we don't have one
        if(!$token) {
            // preparing credentials
            $credentials = $consumerKey . ':' . $consumerSecret;
            $toSend = base64_encode($credentials);
 
            // http post arguments
            $args = array(
                'method' => 'POST',
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => 'Basic ' . $toSend,
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                ),
                'body' => array( 'grant_type' => 'client_credentials' )
            );
 
            add_filter('https_ssl_verify', '__return_false');
            $response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
 
            $keys = json_decode(wp_remote_retrieve_body($response));
 
            if($keys) {
                // saving token to wp_options table
                update_option('cfTwitterToken', $keys->access_token);
                $token = $keys->access_token;
            }
        }
        // we have bearer token wether we obtained it from API or from options
        $args = array(
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array(
                'Authorization' => "Bearer $token"
            )
        );
 
        add_filter('https_ssl_verify', '__return_false');
        $api_url = "https://api.twitter.com/1.1/users/show.json?screen_name=$screenName";
        $response = wp_remote_get($api_url, $args);
 
        if (!is_wp_error($response)) {
            $followers = json_decode(wp_remote_retrieve_body($response));
            $numberOfFollowers = $followers->followers_count;
        } else {
            // get old value and break
            $numberOfFollowers = get_option('cfNumberOfFollowers');
            // uncomment below to debug
            //die($response->get_error_message());
        }
 
        // cache for an hour
        set_transient('cfTwitterFollowers', $numberOfFollowers, 1*60*60);
        update_option('cfNumberOfFollowers', $numberOfFollowers);
    }
 
    return $numberOfFollowers;
}
echo getTwitterFollowers('vybertvar');
*/

/*
$data = json_decode(file_get_contents('https://api.twitter.com/1/users/lookup.json?screen_name=vybertvar'), true);
echo $data[0]['followers_count'];
*/
/*
function rss_count ($rss_user) {
$rssurl="https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=". $rss_user;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $rssurl);
$stored = curl_exec($ch);
curl_close($ch);
$grid = new SimpleXMLElement($stored);
$rsscount = $grid->feed->entry['circulation']+0;
return number_format($rsscount);
}

function rss_count_run($feed) {
	$rss_subs = rss_count($feed);
	$rss_option = "rss_sub_value";
	$rss_subscount = get_option($rss_option);
	if (is_null($rss_subs)) { return $rss_subscount; }
	else {update_option($rss_option, $rss_subs); return $rss_subs;}
}

function rss_sub_value($feed) {
	echo rss_count_run($feed);
}

echo rss_sub_value('ver4rs');
*/
/*
function twitter_followers_count ($twitter_username) {
$url="http://twitter.com/users/show.xml?screen_name=". $twitter_username;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
$data = curl_exec($ch);
curl_close($ch);
$xml = new SimpleXMLElement($data);
$tw_fol_count = $xml->followers_count;
if ($tw_fol_count == false) { echo 'Error'; }
else { echo number_format($tw_fol_count); }
}

*/
/*
require_once('TwitterAPIExchange.php'); //get it from https://github.com/J7mbo/twitter-api-php

/** Set access tokens here - see: https://dev.twitter.com/apps/ **//*
$settings = array(
'oauth_access_token' => "1109624635-0WXZLrP0EViUuf5LXzvvUY0mWC71YgJ8Ro0MqD9",
'oauth_access_token_secret' => "7m5tKtD1AIasA9xWkuOuw6nWEgAdP1QT5UTqxUd5AQ",
'consumer_key' => "bzYSYeaDh6q7sVHvgKA4aw",
'consumer_secret' => "LJjVPWTg9FpcD75xqlNcwOA1ZVjLythPta4ZQQFQ"
);

$ta_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=REPLACE_ME';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$follow_count=$twitter->setGetfield($getfield)
->buildOauth($ta_url, $requestMethod)
->performRequest();
$data = json_decode($follow_count, true);
$followers_count=$data[0]['user']['followers_count'];
echo $followers_count;
*/
?>