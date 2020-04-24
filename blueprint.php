<?php
/**
 * Dynamic Blueprint
 * Version: 1.1.1
 */
 
 $admin_user="testadmin";
 $password="password";
 $admin_email="testadmin@$siteName";

/** Download the latest version of WordPress*/
ds_cli_exec( "wp core download" );

//** Install WordPress
ds_cli_exec( "wp core install --url=$siteName --title='Dynamic Blueprint' --admin_user=$admin_user --admin_password=$password --admin_email=$admin_email" );

//** Change the tagline
ds_cli_exec( "wp option update blogdescription 'The sites tagline'" );

//** Don't e-mail me when anyone posts a comment.
ds_cli_exec( "wp option update default_comment_status 0" );

//** Don't e-mail me when a comment is held for moderation.
ds_cli_exec( "wp option update moderation_notify 0" );

//** Disallow comments (can be overridden with individual posts)
ds_cli_exec( "wp option update default_comment_status 'closed'" );

//** Change Permalink structure
ds_cli_exec( "wp rewrite structure '/%postname%/'" );

//** Discourage search engines from indexing this site
ds_cli_exec( "wp option update blog_public 'on'" );

//** Download plugin from repository, unzip WPSiteSync for Content, activate
ds_cli_exec( "wp plugin install wpsitesynccontent" );
ds_cli_exec( "wp plugin activate wpsitesynccontent" );

//** Download & Activate Theme from WordPress repository
ds_cli_exec( "wp theme install astra --activate" );

//** Download & Activate Theme from Git
ds_cli_exec( "git clone https://github.com/Fruitfulcode/Fruitful.git wp-content/themes/fruitful/");
//ds_cli_exec( "wp theme activate fruitful" );

//** Download & Activate Plugin from Git
ds_cli_exec( "git clone https://github.com/awesomemotive/all-in-one-seo-pack.git wp-content/plugins/all-in-one-seo-pack/" );
ds_cli_exec( "wp plugin activate all-in-one-seo-pack" );

//** Download & Activate Plugin from a private Git repo
//ds_cli_exec( "git clone https://{username}:{password}@github.com/ServerPress/sync.git wp-content/plugins/wpsitesync/" );
//ds_cli_exec( "wp plugin activate wpsitesync" );

/** Install & Activate Plugin located on the Computer - Use Path based on DS-CLI */
/* Mac example */
//ds_cli_exec( "cp /Volumes/Data/Premium_Plugins/bb-plugin-agency.zip ./; wp plugin install bb-plugin-agency.zip --activate; rm bb-plugin-agency.zip" );
/* Windows example */
//ds_cli_exec( "cp c:/Premium_Plugins/bb-plugin-agency.zip ./; wp plugin install bb-plugin-agency.zip --activate; rm bb-plugin-agency.zip" );

//** Install & Activate Theme located on the Computer - Use Path based on DS-CLI
/* Mac example */
//ds_cli_exec( "cp /Volumes/Data/Premium_Themes/bb-theme.zip ./; wp theme install bb-theme.zip; rm bb-theme.zip" );
/* Windows example */
//ds_cli_exec( "cp c:/Premium_Themes/bb-theme.zip ./; wp theme install bb-theme.zip; rm bb-theme.zip" );

//** Create child theme
$child_theme_name = "astra-child";
ds_cli_exec( "wp scaffold child-theme $child_theme_name --parent_theme=astra --activate" );
ds_cli_exec( "cp screenshot.png wp-content/themes/$child_theme_name/");

//** Remove Default Themes (Except twentytwenty for debugging)
ds_cli_exec( "wp theme delete twentynineteen" );
ds_cli_exec( "wp theme delete twentyseventeen" );

//** Remove Default Plugins
ds_cli_exec( "wp plugin delete akismet" );
ds_cli_exec( "wp plugin delete hello" );

//** Remove Default Post/Page
ds_cli_exec( "wp post delete 1 --force" ); // Hello World!
ds_cli_exec( "wp post delete 2 --force" ); // Sample Page

//** Make a new page for the homepage and blog page
ds_cli_exec( "wp post create --post_type=page --post_title='Home' --post_status='publish' --post_author=1 --menu_order=1" ); // Home page
ds_cli_exec( "wp post create --post_type=page --post_title='News' --post_status='publish' --post_author=1 --menu_order=2" ); // Blog page 
ds_cli_exec( "wp post create --post_type=page --post_title='Contact' --post_status='publish' --post_content='This is my Contact Page' --post_author=1 --menu_order=4" ); //Contact page

# Create post with content from given file
ds_cli_exec( "wp post create ./about-page-content.txt --post_type=page --post_title='About' --post_status='publish' --post_author=1 --menu_order=3" ); //About page 

//** Make the created pages the default for Home and Blog
ds_cli_exec( "wp option update show_on_front 'page'" );
ds_cli_exec( "wp option update page_on_front '4'" );
ds_cli_exec( "wp option update page_for_posts '5'" );

//** Delete First Comment
ds_cli_exec( "wp comment delete 1" );

//** Delete all the default sidebar widgets
ds_cli_exec( "wp widget delete search-2 recent-posts-2 recent-comments-2 archives-2 categories-2 meta-2 --quiet" );

//** Set the timezone
ds_cli_exec( "wp option update timezone_string 'America/Los_Angeles'" );

//** Create the main menu and assign it to the primary menu slot
ds_cli_exec( "wp menu create 'Main Menu'" );
ds_cli_exec( "wp menu location assign main-menu primary" );
ds_cli_exec( "wp menu item add-post main-menu 4 --title='Home'" );
ds_cli_exec( "wp menu item add-post main-menu 5 --title='News'" );
ds_cli_exec( "wp menu item add-post main-menu 6 --title='Contact'" );
ds_cli_exec( "wp menu item add-post main-menu 7 --title='About'" );
ds_cli_exec( "wp menu item add-custom main-menu Dashboard $siteName/wp-admin" );

//** Create a local Git Repo
ds_cli_exec( "git init");

/** To Deploy to github, you will need an account, and setup a token at https://github.com/settings/tokens/new (used below)
 *  You will also need a SSH key setup from within DesktopServer.  Do this by creating another site and opening DSCLI
 *  then run ssh-keygen.exe
 *  Accept the defaults for the prompts, and then cat the .pub file from the directory it specifies.
 *  Take that information and login into your Github Account, and go to account settings.
 *  https://github.com/settings/keys  add your SSH key on this page with a note to help you remember.
 *  */

//** Change to your github user and token
$git_user = "user";
$git_token = "token";

//** Set default user and token from github
ds_cli_exec( "git config --global github.user $git_user" );
ds_cli_exec( "git config --global github.token $git_token" );

//** Create Github Repo 
ds_cli_exec( "curl -u $git_user:$git_token https://api.github.com/user/repos -d '{ \"name\": \"$siteName\" }'" );

//** Add remote origin to git
ds_cli_exec( "git remote add origin git@github.com:$git_user/$siteName.git" );

//** Add files to local Repo
ds_cli_exec( "git add wp-content");

//** Do inital Commit
ds_cli_exec( "git commit -m 'Intial Commit'");

//** Send Commit to Github
ds_cli_exec( "git push -u origin master" );

//** Create a directory & get image from Github based on DS-CLI
//	ds_cli_exec( "mkdir ./media" );
//	ds_cli_exec( "wget https://jawordpressorg.github.io/wapuu/wapuu-original/wapuu-original.svg -O ./media/wapuu.svg" );

//** Add a Editor User
ds_cli_exec( "wp user create bob bob@example.com --role=editor" );

//** Import dummy content for posts / WordPress Imported plugin is required
ds_cli_exec( "wp plugin install wordpress-importer --activate");
ds_cli_exec( "wp import ./wordpress-wxr-example.xml --authors=create");

//** Deactivate and remove WordPress Imported plugin is required
ds_cli_exec( "wp plugin uninstall wordpress-importer --deactivate");

//** Create dummy content for posts
//ds_cli_exec( "curl http://loripsum.net/api/4 | wp post generate --post_content --count=10");

//** Import image from remote site and assign to post 1 as a featured image
//ds_cli_exec( "wp media import https://s.w.org/style/images/wp-header-logo.png --post_id=1 --featured_image");

//** Check if index.php unpacked okay
if ( is_file( "index.php" ) ) {

	//** Cleanup the files in this script.
	ds_cli_exec( "rm blueprint.php" );	
	ds_cli_exec( "rm index.htm" );
	ds_cli_exec( "rm about-page-content.txt" );
	ds_cli_exec( "rm wordpress-wxr-example.xml" );
	ds_cli_exec( "rm screenshot.png" );
}
