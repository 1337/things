<?php
	/*  entry point of Things
		Things must be included at the first line of every php file. This is law.
		DO NOT MODIFY REQUIRE/INCLUDE PLACEMENTS
 
		To develop, follow http://www.odi.ch/prog/design/php/guide.php 
	
		/						Where your app is supposedly served
		/things/				Contains config.php
		/things/models/		    Contains DAOs and their tools
		/things/views/		    Contains page "mako templates" except they aren't mako
		/things/controllers/	Contains the Value Object classes
		/things/test/			Tests.
	
	*/
    
    if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
        // if testing, enable error reporting
        error_reporting (E_ALL); // echo ("TESTING");
    }

	date_default_timezone_set ('America/Toronto'); // https://twitter.com/#!/marcoarment/status/59089853433921537
	ob_start (); // captures all buffer for the View class to apply theme to the page.
	
	define ('PROOT', dirname (__FILE__) . '/'); // the things path is there
	
	// load modules that you will likely use.
	require_once (PROOT . 'config/environment.php');
	require_once (PROOT . 'lib/core.php');
	require_once (PROOT . 'lib/strings.php');
	require_once (PROOT . 'lib/datetime.php');
	require_once (PROOT . 'lib/json.php');
	require_once (PROOT . 'lib/compressor.php');
	require_once (PROOT . 'models/things.php'); 
	require_once (PROOT . 'models/thing.php'); 
	require_once (PROOT . 'models/gpvar.php'); 
	require_once (PROOT . 'models/user.php');
	require_once (PROOT . 'models/group.php');
	require_once (PROOT . 'models/privilege.php');
	require_once (PROOT . 'models/post.php');
	require_once (PROOT . 'models/tag.php');
	require_once (PROOT . 'models/page.php');
	require_once (PROOT . 'controllers/auth.php');
	require_once (PROOT . 'controllers/view.php');
	require_once (PROOT . 'controllers/ajaxfield.php');
	require_once (PROOT . 'controllers/paginate.php');
?>