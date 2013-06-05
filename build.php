<?php

require_once('config.php'); // load that sweet, sweet config file
require_once('helpers.php'); // everybody needs a little help, sometimes

// wipe what's in the output dir
delete_recursive($output_dir);

// load templates
$head_file_contents = file_get_contents($head_file);
$header_file_contents = file_get_contents($header_file);
$footer_file_contents = file_get_contents($footer_file);

$posts_by_month = array(); // this is where we'll keep the post info for the archive

// build the index while we go
$index_file_contents = '';
$posts_so_far = 0;

$index_file_contents .= str_replace('{page_title}', $blog_title, $head_file_contents)."\n";
$index_file_contents .= $header_file_contents."\n";
$index_file_contents .= '<div id="posts">'."\n";

// build the RSS feed while we go
$rss_base_xml = '<rss version="2.0">
<channel>
<title>'.$blog_title.'</title>
<link>'.$blog_url.'</link>
<description>'.$blog_description.'</description>
<language>en-us</language>
<pubDate>'.date('r').'</pubDate>
<lastBuildDate>'.date('r').'</lastBuildDate>
<generator>Cylesoft Statiblog</generator>
</channel>
</rss>';
$rss_base_xml = str_replace("\n", '', $rss_base_xml);
$rss_xml = new SimpleXMLExtended($rss_base_xml);

// ok, let's build the individual post files
$get_posts = $mysqli->query('SELECT * FROM posts ORDER BY publishdate DESC');
while ($post_row = $get_posts->fetch_assoc()) {
	
	// what filename will this have?
	$post_file_name = strtolower(trim($post_row['shortname'])).'.html';
	
	// clear this bitch out
	$post_file_contents = '';
	$post_content_section = '';
	
	// build the content section
	$post_content_section .= '<div class="post">'."\n";
	$post_content_section .= '<h3><a href="'.$post_file_name.'">'.$post_row['title'].'</a></h3>'."\n";
	$post_content_section .= '<p class="post-dateby"><span class="the-date">'.date('m/d/Y', $post_row['publishdate']).'</span> by <span class="the-author">'.$blog_author.'</span></p>'."\n";
	$post_content_section .= '<div class="post-content">'."\n";
	$post_content_section .= mb_convert_encoding($post_row['postcontent'], 'utf8')."\n";
	$post_content_section .= '</div>'."\n";
	$post_content_section .= '</div>'."\n";
	
	// compile the damn page
	$post_file_contents .= str_replace('{page_title}', $post_row['title'] . ' - ' . $blog_title, $head_file_contents)."\n";
	$post_file_contents .= $header_file_contents."\n";
	$post_file_contents .= $post_content_section."\n";
	$post_file_contents .= $footer_file_contents."\n";
	
	// save this bitch
	$post_file = fopen($output_dir . $post_file_name, 'w');
	fwrite($post_file, $post_file_contents);
	fclose($post_file);
	
	// ok -- add it to the index page + rss feed, if it's within the amount of posts allowed
	if ($posts_so_far < $posts_on_index) {
		// rss stuff
		$post_item_xml = $rss_xml->channel->addChild('item');
		$post_item_xml->addChild('title', htmlentities($post_row['title']));
		$post_item_xml->addChild('link', $blog_url.$post_file_name);
		$post_item_xml->addChild('guid', $blog_url.$post_file_name);
		$post_item_xml->addChild('pubDate', date('r', $post_row['publishdate']));
		$desc_node = $post_item_xml->addChild('description');
		if (preg_match('/<!--more-->/', $post_content_section) == 1) {
			// if there's a MORE bullshit thing, chop off the rest of the content for the index page
			$new_content_section = preg_replace('/<!--more-->(.*)<\/p>(\s+)<\/div>/s', '<p><a href="'.$post_file_name.'">read more...</a></p></div>', $post_content_section);
			$index_file_contents .= $new_content_section;
			$desc_node->addCData(mb_convert_encoding($new_content_section, 'ascii'));
		} else {
			$index_file_contents .= $post_content_section;
			$desc_node->addCData(mb_convert_encoding($post_content_section, 'ascii'));
		}
	}
	
	// ok -- post meta data -- for the archive
	$post_month_key = date('m/1/Y', $post_row['publishdate']);
	if (!isset($posts_by_month[$post_month_key])) {
		$posts_by_month[$post_month_key] = array();
	}
	$posts_by_month[$post_month_key][] = array( 'title' => $post_row['title'], 'file' => $post_file_name, 'date' => $post_row['publishdate'] );
	
	$posts_so_far++;
}

// finish + save index page
$index_file_contents .= '</div>'."\n"; // end that #posts div
$index_file_contents .= $footer_file_contents;
$index_file = fopen($output_dir . 'index.html', 'w');
fwrite($index_file, $index_file_contents);
fclose($index_file);


// make archive page
$archive_page_contents = '';
$archive_page_contents .= str_replace('{page_title}', $blog_title.' - archive', $head_file_contents)."\n";
$archive_page_contents .= $header_file_contents."\n";
$archive_page_contents .= '<div id="months">'."\n";
foreach ($posts_by_month as $month_key => $posts_monthly) {
	$archive_page_contents .= '<h3>'.date('F Y', strtotime($month_key)).'</h3>'."\n";
	foreach ($posts_monthly as $post_slug) {
		$archive_page_contents .= '<p class="archive-post-slug"><span class="archive-post-title"><a href="'.$post_slug['file'].'">'.$post_slug['title'].'</a></span>, '.date('m/d/Y', $post_slug['date']).'</p>'."\n";
	}
}
$archive_page_contents .= '</div>'."\n"; // end that #months div
$archive_page_contents .= $footer_file_contents;

// save the archive page
$archive_file = fopen($output_dir . 'archive.html', 'w');
fwrite($archive_file, $archive_page_contents);
fclose($archive_file);


// copy everything in bonus directory to the outpit directory
copy_recursive($other_dir, $output_dir);



// finish RSS feed
$rss_file_contents = $rss_xml->asXML();
$rss_file = fopen($output_dir . 'rss.xml', 'w');
fwrite($rss_file, $rss_file_contents);
fclose($rss_file);


// oh, done

?>