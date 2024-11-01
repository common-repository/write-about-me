<?php
/*
Plugin Name: Write About Me
Plugin URI: http://www.harleyquine.com/php-scripts/write-about-me-plugin/
Description: Puts a blogging suggestion on the Add New Post page based on RSS feeds that you provide.
Version: 1.0
Author: Harley
Author URI: http://www.harleyquine.com
*/

/*  Copyright 2009  Harley Quine  (email : harley@harleyquine.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
include_once(ABSPATH . WPINC . '/rss.php');

function writeaboutme_install(){
   $writeaboutme_values[0] = "http://newsrss.bbc.co.uk/rss/newsonline_world_edition/latest_published_stories/rss.xml";
   $writeaboutme_values[1] = "http://www.harleyquine.com/feed/";
   $writeaboutme_values[2] = "http://newsrss.bbc.co.uk/rss/newsonline_world_edition/entertainment/rss.xml";
   $writeaboutme_values[3] = "http://wordpress.org/development/feed/";
   $writeaboutme_values[4] = "http://newsrss.bbc.co.uk/rss/newsonline_world_edition/uk_news/scotland/north_east/rss.xml";

   update_option("writeaboutme", $writeaboutme_values);
}

function writeaboutme_admin_menu() {
   add_submenu_page('options-general.php', 'Write About Me', 'Write About Me', 'manage_options', 'write-about-me-admin', 'writeaboutme_admin');
   }

function writeaboutme_admin(){

   if(isset($_POST['update_writeaboutme_options'])) {
   $writeaboutme_values[0] = $_POST['user_feed1'];
   $writeaboutme_values[1] = $_POST['user_feed2'];
   $writeaboutme_values[2] = $_POST['user_feed3'];
   $writeaboutme_values[3] = $_POST['user_feed4'];
   $writeaboutme_values[4] = $_POST['user_feed5'];

   update_option("writeaboutme", $writeaboutme_values);
   }

   $writeaboutme_values = get_option('writeaboutme');
   ?>
   <div class=wrap>
   <form method="post">
      <h2>Write About Me</h2>
      <p>Enter up to five RSS feed URL's below. These feeds will be used to offer suggestions on what to blog about. The feeds could be from news sources close to home, movie/entertainment news, top blogs.. it all depends on what kind of blog you have/want ;).</p>
<table class="form-table">

   <tr valign="top"><th scope="row">RSS Feed URL - 1</th>
   <td><input type="text" name="user_feed1" value="<?php echo $writeaboutme_values[0]; ?>"><br />Enter a RSS feed URL.</td>
   </tr>

   <tr valign="top"><th scope="row">RSS Feed URL - 2</th>
   <td><input type="text" name="user_feed2" value="<?php echo $writeaboutme_values[1]; ?>"><br />Enter a RSS feed URL.</td>
   </tr>

   <tr valign="top"><th scope="row">RSS Feed URL - 3</th>
   <td><input type="text" name="user_feed3" value="<?php echo $writeaboutme_values[2]; ?>"><br />Enter a RSS feed URL.</td>
   </tr>

   <tr valign="top"><th scope="row">RSS Feed URL - 4</th>
   <td><input type="text" name="user_feed4" value="<?php echo $writeaboutme_values[3]; ?>"><br />Enter a RSS feed URL.</td>
   </tr>

   <tr valign="top"><th scope="row">RSS Feed URL - 5</th>
   <td><input type="text" name="user_feed5" value="<?php echo $writeaboutme_values[4]; ?>"><br />Enter a RSS feed URL.</td>
   </tr>
   </table>

   <input type="hidden" name="update_writeaboutme_options" value="1">
   <p class="submit"><input type="submit" name="info_update" value="Save Changes" /></p>
   </form>

   <div class="wrap">
   <p>If you've found this script useful why not <a href="http://www.harleyquine.com">donate to the cause</a>? If you're having trouble setting up or using the script then contact me for <a href="http://www.harleyquine.com/support/">free support</a> or visit the <a href="http://www.harleyquine.com/php-scripts/write-about-me-plugin/">plugin page</a> for more information.</p>
   </div>
<?php
}

function random_number(){
return rand(0, 4);
}

function writeaboutme_meta() {
   global $post;
   $writeaboutme_values = get_option('writeaboutme');
   $whichrss = random_number();
   while(empty($writeaboutme_values[$whichrss])) { $whichrss = random_number(); }
   $rss = fetch_rss($writeaboutme_values[$whichrss]);
   $maxitems = 5;
   $items = array_slice($rss->items, 0, $maxitems);
   ?>
<ul>
<?php if (empty($items)) echo '<li>No items</li>';
else
foreach ( $items as $item ) : ?>
<li><a href='<?php echo $item['link']; ?>'
title='<?php echo $item['title']; ?>' target='_blank'>
<?php echo $item['title']; ?>
</a><br/><?php echo $item['description']; ?></li>
<?php endforeach; ?>
</ul>
<?php
}

function writeaboutme_meta_box() {
      add_meta_box('writeaboutme','Write About...','writeaboutme_meta','post');
      add_meta_box('writeaboutme','Write About...','writeaboutme_meta','page');
}

add_action('admin_menu', 'writeaboutme_meta_box');
add_action('admin_menu', 'writeaboutme_admin_menu');
add_action('activate_write-about-me.php','writeaboutme_install');
