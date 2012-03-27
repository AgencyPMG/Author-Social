<?php    
/*
Plugin Name: Author Social
Plugin URI: https://github.com/chrisguitarguy/Author-Social
Description: An easy way to add some nice social icons to your Author archives and profiles.
Version: 1.0
Text Domain: author-social
Domain Path: /lang
Author: Christopher Davis
Author URI: http://christopherdavis.me
License: GPL2
    
    Copyright 2012 Christopher Davis

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

define( 'CD_AUTH_SOC_PATH', plugin_dir_path( __FILE__ ) );
define( 'CD_AUTH_SOC_URL', plugin_dir_url( __FILE__ ) );
define( 'CD_AUTH_SOC_NAME', plugin_basename( __FILE__ ) );

if( is_admin() )
{
    require_once( CD_AUTH_SOC_PATH . 'inc/admin.php' );
}
else
{
    require_once( CD_AUTH_SOC_PATH . 'inc/front.php' );
}
