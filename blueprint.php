<?php
/**
 * Dynamic Blueprint
 */

/** Download the latest version of WordPress*/
ds_cli_exec( "wp core download" );

//** Install WordPress
ds_cli_exec( "wp core install --url=$siteName --title='Dynamic Blueprint' --admin_user=testadmin --admin_password=password --admin_email=pleaseupdate@$siteName" );

//** Change the tagline
ds_cli_exec( "wp option update blogdescription 'The sites tagline'" );

//** Change Permalink structure
ds_cli_exec( "wp rewrite structure '/%postname%' --quiet" );

//** Discourage search engines from indexing this site
ds_cli_exec( "wp option update blog_public 'on'" );

/** Download plugin from repository, unzip Image Widget, activate */
ds_cli_exec( "wp plugin install image-widget" );
ds_cli_exec( "wp plugin activate image-widget" );

//** Download & Activate Theme from Git
ds_cli_exec( "git clone https://github.com/Fruitfulcode/Fruitful.git wp-content/themes/fruitful/");
ds_cli_exec( "wp theme activate fruitful" );

//** Download & Activate Plugin from Git
ds_cli_exec( "git clone https://github.com/ServerPress/wpsitesync.git wp-content/plugins/wpsitesync/" );
ds_cli_exec( "wp plugin activate wpsitesync" );

//** Download & Activate Plugin from a private Git repo
//ds_cli_exec( "git clone https://{username}:{password}@github.com/ServerPress/sync.git wp-content/plugins/wpsitesync/" );
//ds_cli_exec( "wp plugin activate wpsitesync" );

/** Install & Activate Plugin from on the Computer - Use Path based on DS-CLI */
ds_cli_exec( "cp /Volumes/Data/Dropbox/WP_Pithy/2.\ BBeaver\ Stuff/1.\ Beaver\ Builder\ Master\ zips/bb-plugin-agency.zip ./; wp plugin install bb-plugin-agency.zip --activate; rm bb-plugin-agency.zip" );

//** Install Theme and Activate - Use Path based on DS-CLI
ds_cli_exec( "cp /Volumes/Data/Dropbox/WP_Pithy/2.\ BBeaver\ Stuff/1.\ Beaver\ Builder\ Master\ zips/bb-theme.zip ./; wp theme install bb-theme.zip; rm bb-theme.zip" );

//** Create child theme
ds_cli_exec( "wp scaffold child-theme bb-child --parent_theme=bb-theme --activate" );

//** Remove Default Themes (Except twentyseventeen)
ds_cli_exec( "wp theme delete twentyfifteen" );
ds_cli_exec( "wp theme delete twentysixteen" );

//** Remove Plugins
ds_cli_exec( "wp plugin delete akismet" );
ds_cli_exec( "wp plugin delete hello" );

//** Remove Default Post/Page
ds_cli_exec( "wp post delete 1 --force" ); // Hello World!
ds_cli_exec( "wp post delete 2 --force" ); // Sample Page

//** Make a new page for the homepage and blog page
ds_cli_exec( "wp post create --post_type=page --post_title='Home' --post_status='publish'" );
ds_cli_exec( "wp post create --post_type=page --post_title='News' --post_status='publish'" );

//** Make those two pages the default for Home and Blog
ds_cli_exec( "wp option update show_on_front 'page'" );
ds_cli_exec( "wp option update page_on_front '3'" );
ds_cli_exec( "wp option update page_for_posts '4'" );

//** Delete First Comment
ds_cli_exec( "wp comment delete 1" );

//** Delete all the default sidebar widgets
ds_cli_exec( "wp widget delete search-2 recent-posts-2 recent-comments-2 archives-2 categories-2 meta-2 --quiet" );

//** set the timezone
ds_cli_exec( "wp option update timezone_string 'America/Los_Angeles'" );

//** create the main menu and assign it to the primary menu slot
ds_cli_exec( "wp menu create 'Main Menu'" );
ds_cli_exec( "wp menu location assign main-menu primary" );
ds_cli_exec( "wp menu item add-post main-menu 3 --title='Home'" );
ds_cli_exec( "wp menu item add-post main-menu 4 --title='News'" );

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

//** Check if index.php unpacked okay
if ( is_file( "index.php" ) ) {

	//** Cleanup the empty folder, download, and this script.
	ds_cli_exec( "rm blueprint.php" );	

}
