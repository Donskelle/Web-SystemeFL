<?php
/**
 * @package DokuMummy
 * @version 1.6
 */
/*
Plugin Name: DokuMummy
Plugin URI: https://drive.google.com/drive/folders/0B34vVqNVPvvefk1lbVNpZEZkRXlJMXFYVVpTa1pOd3hGbVlLZ29qWFNERnBXejd4aWczTUU
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: DokuMummy
Version: 1.6
*/
if ( is_admin() ) {
	include('admin_addFunctionSelection.php');
}
else {
	include('controller.php');
}

