<?php

/*
Plugin Name:  Twitter User Profile
Plugin URI: http://abiro.com/w/web-apps/twitter-user-profile/
Description: Displays any Twitter user's profile on your sidebar
Version: 0.2.1
Author: Anders Borg
Author URI: http://www.abiro.com
*/

/*
License: GPL
Compatibility: WordPress 3.x.

Installation:
Install it as a normal plugin. Then go to Appearance / Widgets to place it on your sidebar.
You can configure its look and behavior as well.
 */

/*  Copyright Anders Borg - http://abiro.com

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

add_action('widgets_init', 'atp_register' );

function atp_register()
{
    register_widget('atp_class');
}

class atp_class extends WP_Widget
{
    function __construct()
    {  
        $widget_ops = array(
            'description' => __("Displays any Twitter user's profile on your sidebar.")
        );
        
        parent::__construct('atp_twitteruserprofile', __('Twitter User Profile'), $widget_ops);  
    }

    function form($instance) 
    {
        // Default values
        
        $defaults = array(
            'title' => 'Twitter',
            'user' => 'twitter',
            'rpp' => 10,
            'interval' => 30,
            'width' => 0,
            'height' => 240,
            'shellbackground' => '#ffffff',
            'shellcolor' => '#000000',
            'tweetsbackground' => '#ffffff',
            'tweetscolor' => '#000000',
            'tweetslinks' => '#202020',
            'scrollbar' => true,
            'loop' => true,
            'live' => true,
            'behavior' => 'all'
        );        
        
        $instance = wp_parse_args((array)$instance, $defaults);        
        
        // Get preferences

        $title = $instance['title'];
        $user = $instance['user'];
        $rpp = $instance['rpp'];
        $interval = $instance['interval'];    
        $width = $instance['width'];    
        $height = $instance['height'];    
        $shellbackground = $instance['shellbackground'];    
        $shellcolor = $instance['shellcolor'];    
        $tweetsbackground = $instance['tweetsbackground'];    
        $tweetscolor = $instance['tweetscolor'];        
        $tweetslinks = $instance['tweetslinks'];    
        $scrollbar = $instance['scrollbar'];
        $loop = $instance['loop'];
        $live = $instance['live'];
        $behavior = $instance['behavior'];        

        // Build form

        print __("Title") . ": <input name=" . $this->get_field_name('title') . " type=text value='" . esc_attr($title) . "' size=20 /><br/>";
        print __("Username") . ": <input name=" . $this->get_field_name('user') . " type=text value='" . esc_attr($user) . "' size=20 /><br/>";
        print __("Live") . ": <input name=" . $this->get_field_name('live') . " type=checkbox value=true " . ($live ? 'checked' : '') . " /><br/>";
        print __("Scrollbar") . ": <input name=" . $this->get_field_name('scrollbar') . " type=checkbox value=true " . ($scrollbar ? 'checked' : '') . " /><br/>\n"; 

        print __("Behavior") . ": <select size=1 name=" . $this->get_field_name('behavior') . "><br/>\n";
        print "<option " . ($behavior == 'default' ? 'selected' : '') . " value=default >" . __('timed interval') . "</option><br/>\n";
        print "<option " . ($behavior == 'all' ? 'selected' : '') . " value=all >" . __('load all') . "</option><br/>\n";
        print "</select><br/>\n";

        print __("Loop") . ": <input name=" . $this->get_field_name('loop') . " type=checkbox value=true " . ($loop ? 'checked' : '') . " /><br/>\n";
        print __("Interval") . ": <input name=" . $this->get_field_name('interval') . " type=text value='" . esc_attr($interval) . "' size=10 /><br/>\n";    
        print __("# of tweets") . ": <input name=" . $this->get_field_name('rpp') . " type=text value='" . esc_attr($rpp) . "' size=10 /><br/>\n";
        print __("Shell background") . ": <input name=" . $this->get_field_name('shellbackground') . " type=text value='" . esc_attr($shellbackground) . "' size=10 /><br/>\n";    
        print __("Shell color") . ": <input name=" . $this->get_field_name('shellcolor') . " type=text value='" . esc_attr($shellcolor) . "' size=10 /><br/>\n";    
        print __("Tweets background") . ": <input name=" . $this->get_field_name('tweetsbackground') . " type=text value='" . esc_attr($tweetsbackground) . "' size=10 /><br/>\n";    
        print __("Tweets color") . ": <input name=" . $this->get_field_name('tweetscolor') . " type=text value='" . esc_attr($tweetscolor) . "' size=10 /><br/>\n";        
        print __("Tweets links") . ": <input name=" . $this->get_field_name('tweetslinks') . " type=text value='" . esc_attr($tweetslinks) . "' size=10 /><br/>\n";    
        print __("Width") . " (0=" . __('auto') . "): <input name=" . $this->get_field_name('width') . " type=text value='" . esc_attr($width) . "' size=10 /><br/>\n";
        print __("Height") . ": <input name=" . $this->get_field_name('height') . " type=text value='" . esc_attr($height) . "' size=10 /><br/>\n";         
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;     
        $instance['title'] = strip_tags($new_instance['title']);        
        $instance['user'] = strip_tags($new_instance['user']);
        $instance['rpp'] = strip_tags($new_instance['rpp']);
        $instance['interval'] = strip_tags($new_instance['interval']);    
        $instance['width'] = strip_tags($new_instance['width']);    
        $instance['height'] = strip_tags($new_instance['height']);    
        $instance['shellbackground'] = strip_tags($new_instance['shellbackground']);    
        $instance['shellcolor'] = strip_tags($new_instance['shellcolor']);    
        $instance['tweetsbackground'] = strip_tags($new_instance['tweetsbackground']);    
        $instance['tweetscolor'] = strip_tags($new_instance['tweetscolor']);        
        $instance['tweetslinks'] = strip_tags($new_instance['tweetslinks']);    
        $instance['scrollbar'] = strip_tags($new_instance['scrollbar']);
        $instance['loop'] = strip_tags($new_instance['loop']);
        $instance['live'] = strip_tags($new_instance['live']);
        $instance['behavior'] = strip_tags($new_instance['behavior']);   
        
        return $instance;
    }
    
    function widget($args, $instance)
    {        
        extract($args);
       
        $title = $instance['title'];
        $user = $instance['user'];
        $rpp = intval($instance['rpp']);
        $interval = intval($instance['interval']) * 1000;    
        $width = intval($instance['width']);    
        $height = intval($instance['height']);    
        $shellbackground = $instance['shellbackground'];    
        $shellcolor = $instance['shellcolor'];    
        $tweetsbackground = $instance['tweetsbackground'];    
        $tweetscolor = $instance['tweetscolor'];        
        $tweetslinks = $instance['tweetslinks'];    
        $scrollbar = $this->boolstr($instance['scrollbar']);
        $loop = $this->boolstr($instance['loop']);
        $live = $this->boolstr($instance['live']);
        $behavior = $instance['behavior'];

        print $before_widget;        

        if (!empty($title)) 
            print $before_title . $title . $after_title;         
        
        if ($width == 0) 
            $width = "'auto'";

        print "<div class='twitteruserprofile'>";
        print "<script src='http://widgets.twimg.com/j/2/widget.js'></script>\n";
        print "<script>\n";
        print "new TWTR.Widget({\n";
        print "  version: 2,\n";
        print "  type: 'profile',\n";
        print "  rpp: $rpp,\n";
        print "  interval: $interval,\n";
        print "  width: $width,\n";
        print "  height: $height,\n";
        print "  theme: {\n";
        print "    shell: {\n";
        print "      background: '$shellbackground',\n";
        print "      color: '$shellcolor'\n";
        print "    },\n";
        print "    tweets: {\n";
        print "      background: '$tweetsbackground',\n";
        print "      color: '$tweetscolor',\n";
        print "      links: '$tweetslinks'\n";
        print "    }\n";
        print "  },\n";
        print "  features: {\n";
        print "    scrollbar: $scrollbar,\n";
        print "    loop: $loop,\n";
        print "    live: $live,\n";
        print "    behavior: '$behavior'";
        print "  }\n";
        print "}).render().setUser('$user').start();\n";
        print "</script>\n";    
        print "</div>";

        print $after_widget;    
    }    

    function boolstr($value)
    {
        return $value ? 'true' : 'false';
    }      
}
