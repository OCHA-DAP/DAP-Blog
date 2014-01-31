<?php
/*
Plugin Name: Font Awesome 4 Menus
Plugin URI: http://www.newnine.com/plugins/font-awesome-4-menus
Description: Join the retina/responsive revolution by easily adding Font Awesome 4.0.3 icons to your WordPress menus and anywhere else on your site! No programming necessary.
Version: 4.0.3.0
Author: New Nine Media
Author URI: http://www.newnine.com
License: GPLv2 or later
*/

/*
    Copyright 2013  NEW NINE MEDIA, L.P.  (tel : +1-800-288-9699)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class FontAwesomeFour {

    function admin_notice(){
        global $current_user, $pagenow;
        if( 'kill-n9m-font-awesome-4-notice' == $_REQUEST[ 'action' ] ){
            update_user_meta( $current_user->data->ID, 'n9m-font-awesome-4-notice-hide', 1 );
        }
        $shownotice = get_user_meta( $current_user->data->ID, 'n9m-font-awesome-4-notice-hide', true );
        if( 'plugins.php' == $pagenow && !$shownotice ){
            print ' <div class="updated">
                        <div style="float: right;"><a href="?action=kill-n9m-font-awesome-4-notice" style="color: #e6db55; display: block; padding: 8px;">&#10008;</a></div>
                        <p>Thank you for installing Font Awesome Menus 4 by <a href="http://www.newnine.com">New Nine</a>! Want to see what else we&#8217;re up to? Subscribe below to our infrequent updates. You can unsubscribe at any time.</p>
                        <form action="http://newnine.us2.list-manage.com/subscribe/post?u=067bab5a6984981f003cf003d&amp;id=1b25a2aee6" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
                            <p><input type="text" name="FNAME" placeholder="First Name" value="'.( !empty( $current_user->first_name ) ? $current_user->first_name : '' ).'"> <input type="text" name="LNAME" placeholder="Last Name" value="'.( !empty( $current_user->last_name ) ? $current_user->last_name : '' ).'"> <input type="text" name="EMAIL" placeholder="Email address" required value="'.$current_user->user_email.'"> <input type="hidden" id="group_1" name="group[14489][1]" value="1"> <input type="submit" name="subscribe" value="Join" class="button action"></p>
                        </form>
                    </div>';
        }
    }

    function menu( $nav ){
        $menu_item = preg_replace_callback(
            '/(<li[^>]+class=")([^"]+)("?[^>]+>[^>]+>)([^<]+)<\/a>/',
            array( $this, 'replace' ),
            $nav
        );
        return $menu_item;
    }
    
    function replace( $a ){
        $start = $a[ 1 ];
        $classes = $a[ 2 ];
        $rest = $a[ 3 ];
        $text = $a[ 4 ];
        $before = true;
        
        $class_array = explode( ' ', $classes );
        $fontawesome_classes = array();
        foreach( $class_array as $key => $val ){
            if( 'fa' == substr( $val, 0, 2 ) ){
                if( 'fa' == $val ){
                    unset( $class_array[ $key ] );
                } elseif( 'fa-after' == $val ){
                    $before = false;
                    unset( $class_array[ $key ] );
                } else {
                    $fontawesome_classes[] = $val;
                    unset( $class_array[ $key ] );
                }
            }
        }
        
        if( !empty( $fontawesome_classes ) ){
            $fontawesome_classes[] = 'fa';
            if( $before ){
                $newtext = '<i class="'.implode( ' ', $fontawesome_classes ).'"></i><span class="fontawesome-text"> '.$text.'</span>';
            } else {
                $newtext = '<span class="fontawesome-text">'.$text.' </span><i class="'.implode( ' ', $fontawesome_classes ).'"></i>';
            }
        } else {
            $newtext = $text;
        }
        
        $item = $start.implode( ' ', $class_array ).$rest.$newtext.'</a>';
        return $item;
    }
    
    function shortcode_icon( $atts ){
        extract( shortcode_atts( array(
            'class' => '',
        ), $atts ) );
        if( !empty( $class ) ){
            $fa_exists = false;
            $class_array = explode( ' ', $class );
            foreach( $class_array as $c ){
                if( 'fa' == $c ){
                    $fa_exists = true;
                }
            }
            if( !$fa_exists ){
                array_unshift( $class_array, 'fa' );
            }
            return '<i class="'.implode( ' ', $class_array ).'"></i>';
        }
    }
    
    function shortcode_stack( $atts, $content = null ){
        extract( shortcode_atts( array(
            'class' => '',
        ), $atts ) );
        if( empty( $class ) ){
            $class_array = array( 'fa-stack' );
        } else {
            $fa_stack_exists = false;
            $class_array = explode( ' ', $class );
            foreach( $class_array as $c ){
                if( 'fa-stack' == $c ){
                    $fa_stack_exists = true;
                }
            }
            if( !$fa_stack_exists ){
                array_unshift( $class_array, 'fa-stack' );
            }
        }
        return '<span class="'.implode( ' ', $class_array ).'">'.do_shortcode( $content ).'</span>';
    }

    function wp_enqueue_scripts(){
        wp_register_style( 'font-awesome-four', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), '4.0.3', 'all' );
        wp_enqueue_style( 'font-awesome-four' );
    }
    
    
    function __construct(){
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
        add_filter( 'wp_nav_menu' , array( $this, 'menu' ), 10, 2 );
        add_shortcode( 'fa', array( $this, 'shortcode_icon' ) );
        add_shortcode( 'fa-stack', array( $this, 'shortcode_stack' ) );
    }
}
$n9m_font_awesome_four = new FontAwesomeFour();
?>
