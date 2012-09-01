<?php   
    /* 
    Plugin Name: Frndzk Post From any mobile
    Plugin URI: http://www.bitto.us
    Description: Frndzk Post From Any Mobile Plugin enables you to add post to your blog from any mobile browser. no setup or configuration needed. Just install and you will be able to post from mobile. Plugin Page Link Will be at footer of your admin panel to get easy access. Plugin <a href="http://bitto.us/wp/">Demo</a>. plugin developed by <a href="http://bitto.us">Bitto Kazi</a>
    Author: Bitto Kazi
    Version: 1.0 
    Author URI: http://www.bitto.us
    */
function frndzkmobile_admin() {
echo"<br>";


if (isset($_GET['action'])){
if ( $_GET['action'] == "post" ) {
global $wpdb;
if ( "$_POST[post]" != "" ) {

$namepost = stripslashes($_REQUEST['post']);
$namepost = mysql_real_escape_string($namepost);


$contentpost = stripslashes($_REQUEST['postcontent']);
$contentpost = mysql_real_escape_string($contentpost);

$tagpost = stripslashes($_REQUEST['tag']);
$tagpost = mysql_real_escape_string($tagpost);

function genRandomString() {
    $length = 5;
    $characters = "0123456789";
    $string = '';    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
}

$frndzk = genRandomString();


$text = "$namepost";
$bitto = str_replace(" ", "-", $text);
$bitto = str_replace("_", "", $bitto);
$bitto = str_replace(".", "", $bitto);
$bitto = str_replace(",", "", $bitto);
$bitto = str_replace("?", "", $bitto);
$bitto = str_replace("!", "", $bitto);
$bitto = str_replace(":", "", $bitto);
$bitto = str_replace("/", "", $bitto);
$bitto = str_replace("(", "", $bitto);
$bitto = str_replace(")", "", $bitto);
$bitto = str_replace("#", "", $bitto);
$bitto = str_replace("'", "", $bitto);
$bitto = str_replace("^", "", $bitto);
$bittos = str_replace("*", "", $bitto);
$bittos = strtolower($bittos);

$postpermalink = "$bittos$frndzk";
$time=date('Y-m-d H:i:s');

global $wpdb;
mysql_query("INSERT INTO $wpdb->posts VALUES ('','$_REQUEST[postedby]','$time','$time','$contentpost','$namepost','','publish','open','open','','$postpermalink','','','$time','$time','','1','lol','0','post','','0')");


$cats = $wpdb->get_results("SELECT ID FROM $wpdb->posts
WHERE post_name = '$postpermalink'");
foreach ( $cats as $cat ) 
{
$home=home_url();
$guids="$home/?p=".$cat->ID."";
mysql_query("UPDATE $wpdb->posts SET guid = '$guids' WHERE post_name = '$postpermalink'");

$getcats = $wpdb->get_results("SELECT term_id FROM $wpdb->terms
WHERE name = '$_REQUEST[cat]'");
foreach ( $getcats as $getcat ) 
{
$catid="".$getcat->term_id."";
$postid="".$cat->ID."";


mysql_query("INSERT INTO $wpdb->term_relationships VALUES ('$postid','$catid','0')");

$councat = $wpdb->get_results("SELECT count FROM $wpdb->term_taxonomy
WHERE term_id = '$catid' AND taxonomy = 'category'");
foreach ( $councat as $counscat ) 
{

$getcouncat="$counscat->count";
$getcounscat=$getcouncat+1;
mysql_query("UPDATE $wpdb->term_taxonomy SET count = '$getcounscat' WHERE term_id = '$catid' AND taxonomy = 'category'");


}


}


}




$tag = strtolower($tagpost);


$tags = "$tag";
function extract_alphabetical_sequences($tags){
preg_match_all('/([a-zA-Z0-9]+)/', $tags, $match);
return $match[0];
}


$alpha_array=extract_alphabetical_sequences($tags);

foreach ($alpha_array as $itemtags) {
 
$find = "$itemtags";
$find = trim($find);

$slu = $wpdb->get_results("SELECT term_id FROM $wpdb->terms
WHERE slug LIKE'%$find%'");



if ( !$slu ) {



mysql_query("INSERT INTO $wpdb->terms VALUES ('','$find','$find','0')");

$tags = $wpdb->get_results("SELECT term_id FROM $wpdb->terms
WHERE slug LIKE'%$find%'");
foreach ( $tags as $tag ) 
{

$valu="$tag->term_id";

mysql_query("INSERT INTO $wpdb->term_taxonomy VALUES ('$valu','$valu','post_tag','','0','0')");

$coun = $wpdb->get_results("SELECT count FROM $wpdb->term_taxonomy
WHERE term_id = '$valu' AND taxonomy = 'post_tag'");
foreach ( $coun as $couns ) 
{
$getcoun="$couns->count";
$getcouns=$getcoun+1;
mysql_query("UPDATE $wpdb->term_taxonomy SET count = '$getcouns' WHERE term_id = $tag->term_id AND taxonomy = 'post_tag'");



mysql_query("INSERT INTO $wpdb->term_relationships VALUES ('$postid','$valu','0')");
}

}



}

else {
$tags = $wpdb->get_results("SELECT * FROM $wpdb->terms
WHERE slug = '$find'");
foreach ( $tags as $tag ) 
{

$valu="$tag->term_id";


$coun = $wpdb->get_results("SELECT * FROM $wpdb->term_taxonomy
WHERE term_id = '$valu' AND taxonomy = 'post_tag'");

foreach ( $coun as $couns ) 
{
$valud="$couns->term_id";
$getcoun="$couns->count";
$getcouns=$getcoun+1;
mysql_query("UPDATE $wpdb->term_taxonomy SET count = '$getcouns' WHERE term_id = '$valud' AND taxonomy = 'post_tag'");


mysql_query("INSERT INTO $wpdb->term_relationships VALUES ('$postid','$valud','0')");

}




//gggg
}

}
//finish else







//finish


}


echo "SuccessFully Added the Post ";
echo '<a href="options-general.php?page=Frndzk-Mobile-Post">Go Back</a><br><br><br>
Plugin Developed by <a href="http://www.bitto.us" target="_blank">Bitto Kazi</a>';

}

else {
echo"Postname Must not be Empty ";
echo '<a href="options-general.php?page=Frndzk-Mobile-Post">Go Back</a><br><br><br>
Plugin Developed by <a href="http://www.bitto.us" target="_blank">Bitto Kazi</a>';
}
}
}
else{
echo'<form action="options-general.php?page=Frndzk-Mobile-Post&action=post" method="post">
<input type=hidden name=postedby value="';
echo get_current_user_id();
echo'">
post name: <input type="text" name="post" /></br>
post content:</br> <textarea name="postcontent" cols="50" rows="20"></textarea></br>Tags : <input type="text" name="tag" /> divided by comma (,) <br>';

echo'select category: <select name="cat">';
global $wpdb;
$cats = $wpdb->get_results("SELECT term_id FROM $wpdb->term_taxonomy
WHERE taxonomy = 'category'");
foreach ( $cats as $cat ) 
{
$catsa = $wpdb->get_results("SELECT name FROM $wpdb->terms
WHERE term_id = $cat->term_id");
foreach ( $catsa as $catsaa ) 
{
$bit=$catsaa->name;
echo "<option>$bit</option>";
}
}
echo'</select><br><input type="submit" value="Publish Post"/></form><br><br>
Plugin Developed by <a href="http://www.bitto.us" target="_blank">Bitto Kazi</a>';
}








}
function frndzkmobile_admin_actions() {  
    add_options_page("Frndzk Mobile Post", "Frndzk Mobile Post", "manage_options","Frndzk-Mobile-Post","frndzkmobile_admin");  
}
add_action('admin_menu', 'frndzkmobile_admin_actions');





function remove_footer_admin () { echo 'Thank you for creating with <a href="http:// www.wordpress.org" target="_ blank">WordPress</a> | <a href="options-general.php?page=Frndzk-Mobile-Post">Add post using Frndzk mobile Post</a></p>'; } 

add_filter('admin_footer_text', 'remove_footer_admin');
?>