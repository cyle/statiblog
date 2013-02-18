# THE STATIBLOG

Short for STATIC BLOG, lol.

## you're serious? a "STATIC" blog?

Yes, I'm serious. And yes, it's hilarious. As web developers, we went from static content sites to dynamic sites to dynamic sites that compile to static sites back to dynamic sites and now we're going back to static sites.

So this is my take on a build-able static site blog thing whatever. This is inspired by a few I've seen online, like Don Melton's [Magneto](https://github.com/donmelton/magneto).

## This currently works best for...

- web developers who aren't afraid of the shell
- blogs with one author
- blogs that need only three types of pages: an index page, an archive page, and a blog post page
- blogs that need ridiculously high performance (since you'll only be serving static files)

## Requirements

- PHP 5.3+
- MySQL 5.0+

## How it works

You have ONE DATABASE... called whatever... and INSIDE... is ONE TABLE: posts. That's it. See the [create_db.sql](create_db.sql) file for info, but it's fairly simple.

Rename config.sample.php to config.php. The config.php file contains all of your standard options. Blog name. Number of posts on the index page. Database connection settings.

The build.php file does all the heavy work. It does all of the steps in one script. It...

1. deletes everything currently in the output directory
1. loads your header, footer, and page head templates
1. builds each individual blog post page
1. builds the index page while it's doing that
1. also builds the RSS feed XML while it's doing that
1. writes out the individual blog pages, the index page, and the rss XML to static HTML files
1. copies all the stuff in the "other" directory (css files, images, static content) to the output directory

Then it's done. In the "output" directory, you'll have a complete site.

The helpers.php file has some nice helper functions.

## Note about templating

Theming this thing is stupid easy, I think. I've included most of the config options and lol.css for my fuck advocacy blog redesign, which this statiblog is probably going to replace.

Obviously before you use this on your site you'll want to go through and edit out my crap from the template files.

## Features needed

- a friendlier way of adding posts to the posts database, right now it's manual
- further separation of templates from the build script