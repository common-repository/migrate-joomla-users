<?php
/*
Plugin Name: Migrate Joomla Users
Description: A plugin to migrate users from Joomla to WordPress.
Author: Ramlal Solanki
Author URI: https://about.me/ramlal
Version: 1.0
*/
//to add custom page in admin section
add_action('admin_menu', 'migrate_joomla_users_plugin');
function migrate_joomla_users_plugin(){
	$plugins_url=plugin_dir_url( __FILE__ ) . 'images/jtow.jpg' ;
	add_menu_page( 'Migrate Joomla Users', 'Migrate Joomla Users', 'manage_options', 'migrate-joomla-users-plugin', 'migrate_joomla_users_init', $plugins_url );
}
function migrate_joomla_users_init(){
	require plugin_dir_path( __FILE__ ) . 'migrate_joomla_users.php';
}
?>