<?php

/*

	oh lawds
	
	statiblog config file

*/

// where do i put the finished files and crap
$output_dir = 'output/';

// some info about your blog
$blog_title = 'fuck advocacy';
$blog_url = 'http://fuckadvocacy.com/';
$blog_author = 'cyle gage';
$blog_email = 'cylegage@gmail.com';
$blog_description = 'Just another blog.';

// how many posts on the index?
$posts_on_index = 7;

// the relative (or absolute) locations of your template files
$head_file = 'templates/head.html';
$header_file = 'templates/header.html';
$footer_file = 'templates/footer.html';

// the relative (or absolute) location of a folder you'd like just plain old copied to the output dir
$other_dir = 'other/';

// database settings, jeepers
$mysql_host = '';
$mysql_user = '';
$mysql_pass = '';
$mysql_db = '';

// ok, you can stop editing beyond here

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

?>