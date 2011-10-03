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
	 
		In a sense, the Things library is free software.
		Copyright (C) 2011 Brian Lai

		<GPLv2 license>
		
		This program is free software; you can redistribute it and/or
		modify it under the terms of the GNU General Public License
		as published by the Free Software Foundation; either version 2
		of the License, or (at your option) any later version.
		
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
		
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/
 
	error_reporting (E_ALL);
	date_default_timezone_set ('America/Toronto'); // https://twitter.com/#!/marcoarment/status/59089853433921537
	ob_start (); // captures all buffer for the View class to apply theme to the page.
	
	define ('PROOT', dirname (__FILE__) . '/'); // the things path is there
	define ('WEBROOT', "http://" . $_SERVER['SERVER_NAME'] . 
		substr (
		    dirname (dirname (realpath (__FILE__))), 
			strlen ($_SERVER['DOCUMENT_ROOT'])
		) . '/'
	);
	
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