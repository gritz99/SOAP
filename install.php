<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>ARG Install</title>
		<style type="text/css">
			body { background-color:#BBB; }
			#column { width:888px; margin:24px auto; padding:24px; background-color:white; }
			li { margin-bottom:0.75em; }
			a, a:visited {color:#44F;}
			.error { color:#C00; }
		</style>
	</head>

	<body>
		<div id="column">

<?php

// patterns to scrub out of the data dump
$ARG_SERVER_NAME = 'www\.randybelcher\.com';
$ARG_SERVER_DIR = '\/soap';
$ARG_SERVER_PATH = '\/home7\/randybel\/public_html'. $ARG_SERVER_DIR;


$VARS = $_POST;
if (!$VARS['mode']) $VARS['mode'] = 'test';


//------------------------------------------------
function db_connect($mode, $db_vars) {
	if ($mode == 'open') {
		$dbo = new mysqli($db_vars['host'], $db_vars['user'], $db_vars['pass'], $db_vars['name']);
		if ($dbo->connect_error or mysqli_connect_error()) return false;
		else return $dbo;
	}
	if ($mode == 'close') {
		return $db_vars['dbo']->close();
	}
}


//------------------------------------------------
// copied from PHP manual for rmdir
function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object);
				else unlink($dir."/".$object); 
			} 
		} 
		reset($objects); 
		rmdir($dir); 
	} 
}



//========================================================================
//========================================================================



// first check to see if something is installed here
if ($VARS['mode'] == 'test') {
		
	// check to see if wp-config already file exists
	if (is_readable('./wp-config.php')) {
		$config = file_get_contents('./wp-config.php', true);
		$config = preg_replace('/\n|\r/',' ', $config);

		$teststring = '/define\(\'DB_NAME\', \'(\w+)\'\);\s+\/\*\* MySQL database username \*\/\s+define\(\'DB_USER\', \'(\w+)\'\);\s+\/\*\* MySQL database password \*\/\s+define\(\'DB_PASSWORD\', \'(\w+)\'\);\s+\/\*\* MySQL hostname \*\/\s+define\(\'DB_HOST\', \'(\w+)\'\);/';

		preg_match($teststring, $config, $matches);
		
		// send error if data in wp-config file defines a database connection
		if ($matches[3]) $dbo = db_connect('open', array('host'=>$matches[4], 'user'=>$matches[2], 'pass'=>$matches[3], 'name'=>$matches[1]));
		if ($dbo) {
			print '<h4 class="error">There already appears to be a blog installed in this location.</h4>';
			$VARS['mode'] = 'error';
		}
		// if this file does not define a database connection, delete it
		else {
			unlink('./wp-config.php');
			$VARS['mode'] = 'archive_test';
		}
	}
	else $VARS['mode'] = 'archive_test';


	// now check for the ZIP file and install it
	if ($VARS['mode'] == 'archive_test') {
		$error = '';
		if (is_readable('arg.zip')) {
			exec('unzip arg.zip') or $error = 'Could not unpack the archived ZIP file';
		}
		else $error = 'Could not read the archived ZIP file';
		if ($error) print '<h4 class="error">'. $error. '</h4>';
		else {
			$VARS['mode'] = 'file_test';
			rrmdir('__MACOSX');
			unlink('arg.zip');
		}
	}
	

	if ($VARS['mode'] == 'file_test') {
		// now test to see that all config files are in place
		if (!is_readable('./wp-config-sample.php')) print '<h4 class="error">Unable to read the configuration template.</h4>';
		elseif (!is_readable('./wp-arg-db.sql')) print '<h4 class="error">Unable to read the database file.</h4>';
		else $VARS['mode'] = 'form1';
		if ($VARS['mode'] != 'error' and $VARS['mode'] != 'form1')
			print '<p class="error">Please makes sure that all install files are in place.</p>';
	}

	if ($dbh) db_connect('close', array('dbo'=>$dbh));

}


//------------------------------------------------
if ($VARS['mode'] == 'enter DB info') {
	//=========== test database connection ==============
	$dbo = db_connect('open', array('host'=>$VARS['host'], 'user'=>$VARS['user'], 'pass'=>$VARS['pass'], 'name'=>$VARS['name']));
	if ($dbo) {
		//=========== create config file ==============
		$config = file_get_contents('./wp-config-sample.php', true);
		$config = preg_replace('/database_name_here/', $VARS['name'], $config);
		$config = preg_replace('/database_username_here/', $VARS['user'], $config);
		$config = preg_replace('/database_password_here/', $VARS['pass'], $config);
		$config = preg_replace('/database_host_name_here/', $VARS['host'], $config);
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($x = 1; $x < 9; $x++) {
			$randstring = '';
			for ($i = 0; $i < 64; $i++) $randstring .= $characters[rand(0, strlen($characters) - 1)];
			$config = preg_replace('/put your unique phrase here '. $x. '/', $randstring, $config);
		}
		file_put_contents('./wp-config.php', $config);
		$VARS['mode'] = 'form2';
		db_connect('close', array('dbo'=>$dbo));
		//=========== edit config file for BuddyPress Community ==============
		$BPPC_file = './wp-content/plugins/buddypress-private-community-config/mm-buddypress-private-community-config.php';
		$VARS['local_path'] = preg_replace('/\//', '', dirname($_SERVER['PHP_SELF']));
		$config = file_get_contents($BPPC_file, true);
		$config = preg_replace('/QQXXQQ/', $VARS['local_path'], $config);
		file_put_contents($BPPC_file, $config);
	}
	else {
		print '<h4 class="error">Unable to connect to database with information supplied.</h4>';
		print '<p class="error">Please check the information and try again.</p>';
		$VARS['mode'] = 'form1';
	}
}


//------------------------------------------------
if ($VARS['mode'] == 'enter Admin info') {
	// upload data from SQL dump file into database
	// first, scrub the files for old paths and URLs
	if (preg_match('/\S/', $VARS['uname']) and preg_match('/^\S+\@\S+$/', $VARS['uemail'])) {
		$VARS['uname'] = preg_replace('/\s/', '', $VARS['uname']);
		// create database from file
		$db = file_get_contents('./wp-arg-db.sql', true);
		$db = preg_replace('/; ?(\r|\n)/', ';QQXXQQ', $db);
		$db = preg_replace('/\s+/', ' ', $db);
		$VARS['local_dir'] = $_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']);
		$VARS['local_server'] = $_SERVER['HTTP_HOST'];
		$VARS['local_root'] = $_SERVER['DOCUMENT_ROOT']. dirname($_SERVER['PHP_SELF']);
		$tokens = array();
		// search for Wordpress root in tokenized instances
		preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_NAME. $ARG_SERVER_DIR. '[^;]*)"/', $db, $matches);
		while ($matches[0] != '') {
			$str = preg_replace('/'. $ARG_SERVER_NAME. $ARG_SERVER_DIR. '/', $VARS['local_dir'], $matches[2]);
			$replace = 's:'. strlen($str). ':"'. $str. '"';
			array_push($tokens, $matches[0]. ' :: '. $replace);
			$db = preg_replace('/'. preg_quote($matches[0], '/'). '/', 's:'. strlen($str). ':"'. $str. '"', $db);
			preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_NAME. $ARG_SERVER_DIR. '[^;]*)"/', $db, $matches);
		}
		// now replace all other instances
		$db = preg_replace('/'. $ARG_SERVER_NAME. $ARG_SERVER_DIR. '/', $VARS['local_dir'], $db);
		// search for server name in tokenized instances
		preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_NAME. '[^;]*)"/', $db, $matches);
		while ($matches[0] != '') {
			$str = preg_replace('/'. $ARG_SERVER_NAME. '/', $VARS['local_server'], $matches[2]);
			$replace = 's:'. strlen($str). ':"'. $str. '"';
			array_push($tokens, $matches[0]. ' :: '. $replace);
			$db = preg_replace('/'. preg_quote($matches[0], '/'). '/', 's:'. strlen($str). ':"'. $str. '"', $db);
			preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_NAME. '[^;]*)"/', $db, $matches);
		} 
		// now check for tokenized instances of server root
		preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_PATH. '[^;]*)"/', $db, $matches);
		while ($matches[0] != '') {
			$str = preg_replace('/'. $ARG_SERVER_PATH. '/', $VARS['local_root'], $matches[2]);
			$replace = 's:'. strlen($str). ':"'. $str. '"';
			array_push($tokens, $matches[0]. ' :: '. $replace);
			$db = preg_replace('/'. preg_quote($matches[0], '/'). '/', 's:'. strlen($str). ':"'. $str. '"', $db);
			preg_match('/s:(\d+):"([^;]*'. $ARG_SERVER_PATH. '[^;]*)"/', $db, $matches);
		} 
		// now replace all other instances
		$db = preg_replace('/'. $ARG_SERVER_PATH. '/', $VARS['local_root'], $db);

		$queries = split('QQXXQQ', $db);
		$dbo = db_connect('open', array('host'=>$VARS['host'], 'user'=>$VARS['user'], 'pass'=>$VARS['pass'], 'name'=>$VARS['name']));
		foreach ($queries as $query) if ($query != '') $dbo->query($query);
		// write admin user into the database
		// quote strings. . .
		$dbo->query("UPDATE wp_users SET user_login='". $dbo->real_escape_string($VARS['uname']). "',  user_nicename='". $dbo->real_escape_string($VARS['uname']). "', user_email='". $dbo->real_escape_string($VARS['uemail']). "', user_registered=NOW(), display_name='". $dbo->real_escape_string($VARS['uname']). "' WHERE ID=1;");
		$dbo->query("UPDATE `wp_options` SET option_value='". $dbo->real_escape_string($VARS['uemail']). "' WHERE option_name='admin_email';");

		// print out the reply form
		print '<h4>ARG should now be installed.</h4>';
		print '<p>There are a few more steps that you will need to complete in order for you to access your game.</p>';
		print '<ul><li>Go to the <a href="http://'. $VARS['local_dir']. '/wp-admin/" target="_blank">admin interface</a> in your new game and log in with the administrative user that you just entered. Your temporary password will be &ldquo;1234&rdquo;.</li><li>Once you are logged in to the admin interface, select the <em>Settings</em> tab on the left, and then select <em>Permalinks</em> from the submenu.</li><li>You will not need to change any values under the permalinks options, but you will need to press the &ldquo;Save Changes&rdquo; button at the bottom of the screen to restore the URLs for your site.</li><li>Once the URLs are restored, it is important that you change the password for your administrative user. This user will be the primary administrative user for your game. You can change the password by selecting the <em>Users</em> tab on the left and then selecting <em>Your Profile</em> from the submenu.</li><li>Once this is done, you can visit the <a href="http://'. $VARS['local_dir']. '">home page</a> of your game.</li><li>You should then <strong>delete this page (<samp>install.php</samp>)</strong> from your server so that others can not overwrite your secure information.</li></ul>';
		$dbo->close();
	}
	else {
		print '<h4 class="error">Invalid user name, e-mail, or password.</h4>';
		$VARS['mode'] = 'form2';
	}
}




//------------------------------------------------
if ($VARS['mode'] == 'form1') {
?>

<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
	<h3>Enter the following information to set up your ARG database.</h3>
	<p>Database Server Address: <input type="text" name="host" size="44" <?php if ($VARS['host']) print 'value="'. $VARS['host']. '"'; ?> /></p>
	<p>Database User Name: <input type="text" name="user" size="44" <?php if ($VARS['user']) print 'value="'. $VARS['user']. '"'; ?> /></p>
	<p>Database User Password: <input type="password" name="pass" size="44" <?php if ($VARS['pass']) print 'value="'. $VARS['pass']. '"'; ?> /></p>
	<p>Database Name: <input type="text" name="name" size="44" <?php if ($VARS['name']) print 'value="'. $VARS['name']. '"'; ?> /></p>
	<p><input type="submit" name="mode" value="enter DB info" /></p>
</form>

<?php
}



//------------------------------------------------
if ($VARS['mode'] == 'form2') {
?>

<form action="<?php print $_SERVER['PHP_SELF']; ?>" method="post">
	<h3>Set a default user for your ARG installation.</h3>
	<p>This will be the administrative user name that you will use to log into the ARG.</p>
	<p>Admin User Name: <input type="text" name="uname" size="44" <?php if ($VARS['uname']) print 'value="'. $VARS['uname']. '"'; ?> /></p>
	<p>Admin User E-mail Address: <input type="text" name="uemail" size="44" <?php if ($VARS['uemail']) print 'value="'. $VARS['uemail']. '"'; ?> /></p>
	<input type="hidden" name="host" value="<?php print $VARS['host']; ?>" />
	<input type="hidden" name="user" value="<?php print $VARS['user']; ?>" />
	<input type="hidden" name="pass" value="<?php print $VARS['pass']; ?>" />
	<input type="hidden" name="name" value="<?php print $VARS['name']; ?>" />
	<p><input type="submit" name="mode" value="enter Admin info" /></p>
</form>

<?php
}


/*
print "<hr /><pre>";
print 'MODE = '. $VARS['mode']. "\n";
//print "MATCH = ". print_r($matches, true). "\n";
print 'VARS = '. print_r($VARS, true). "\n";
print 'TOKENS = '. print_r($tokens, true). "\n";
//print 'QUERY = '. print_r($queries, true). "\n";
print "</pre>";
*/

?>

		</div><!-- id="column" -->
	</body>
</html>
