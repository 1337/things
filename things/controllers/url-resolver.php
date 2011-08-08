<?php
    // this thing's job is to SWALLOW
    // the files corresponding to the input URL to show where they belong.
    require_once ('.things.php');
    
	$rules = array_merge (array (
		// rule => replacement, just like in .htaccess
		'^/(\w+)/([0-9]+)/?$'                         => '/things/controllers/$1-ui.php?id=$2',
		'^/css/(.+)?$'                                => '/things/controllers/css.php?f=$1',
		'^/(\w+)/([0-9]+)/?$'                         => '/things/controllers/$1-ui.php?id=$2',
		'^/new/(\w+)/?$'                              => '/things/controllers/$1-ui.php?new=1',
		'^/new/(\w+)/([0-9]+)/?$'                     => '/things/controllers/$1-ui.php?new=1&id=$2',
		'^/edit/(\w+)/([A-Za-z0-9]+)/?$'              => '/things/controllers/$1-ui.php?id=$2&edit=1',
		'^/delete/(\w+)/([0-9]+)/?$'                  => '/things/controllers/$1-ui.php?id=$2&delete=1',
		'^/edit/(\w+)/([0-9]+)/?$'                    => '/things/controllers/$1-ui.php?id=$2&edit=1',
		'^/delete/(\w+)/([A-Za-z0-9]+)/?$'            => '/things/controllers/$1-ui.php?id=$2&delete=1',
		'^/limit/(.+)$'                               => '/things/controllers/limiter.php?site=$1',
		'^/filesearch/(.+)/?$'                        => '/things/controllers/filesearch.php?q=$1',
		'^/login'                                     => '/things/controllers/login.php',
		'^/logout'                                    => '/things/controllers/logout.php',
		'^/menu/?$'                                   => '/things/views/menu.php',
		'^/objects/?$'                                => '/things/controllers/objects-viewer.php',
		'^/tickets/?$'                                => '/things/views/tickets.php',
		'^/register/?$'                               => '/things/views/register.php'
	), $redirect_rules);
    
	/*
	    others
		^get/([0-9]+)/?$ things/controllers/ajax.php?oid=$1
		^get/([0-9]+)/([A-Za-z0-9\.-_]+)/?$ things/controllers/ajax.php?oid=$1&prop=$2
		^set/([0-9]+)/([A-Za-z0-9\.-_]+)/(.+)/?$ things/controllers/ajax.php?oid=$1&prop=$2&val=$3
	*/
	
	$request_uri = $_SERVER['REQUEST_URI'];
	// echo ($request_uri);
	if (strlen ($request_uri) > 0) {
		$object_id = FindObjectByPermalink ($request_uri);
		// print_r ($object_id);
		if (!is_null ($object_id)) { // if an object had a permalink registered, show it immediately
			$type = GetObjectType ($object_id);
			// if object exists, replace permalink with its generic URL (e.g. /tickets/224)
			$request_uri = '/' . strtolower (GetTypeName ($type)) . "/" . $object_id;
		}
		$short_webroot = substr (WEBROOT, 0, strlen (WEBROOT) -1); // WEBROOT without the last slash
		// echo ($short_webroot); // http://ohai.ca
		// print_r ($rules);
		foreach ($rules as $rule => $request_replacement) {
			$dummy_array = array (); // must have something to pass as reference in next line
			$matched = (preg_match_all ('#' . $rule . '#', $request_uri, $dummy_array) > 0);
			// var_dump ($matched);
			$converted_url = preg_replace ('#' . $rule . '#', $request_replacement, $request_uri);
			if ($converted_url != $request_uri) { // if something matched
				$parsed = parse_url ($short_webroot . $converted_url);
				// Array ( [path] => /controllers/ticket-ui.php [query] => id=224 )
				if (array_key_exists ('query', $parsed)) {
					// if query string exists (so $_GET)
					$vars = array ();
					parse_str ($parsed['query'], $vars);
					foreach ($vars as $key => $val) {
						// add detected keys to $_GET.
						// $_GET[$key] = $val; // $gp->Set (array ($key => $val));
						$gp->Set (array ($key => $val), GP_GET);
						// print_r ($key . ' => ' . $_GET[$key]);
					}
					// print_r (array (PROOT . substr ($parsed['path'], 1), $_GET));
				}
				// var_dump ($parsed);
				// var_dump (PROOT);
				if (array_key_exists ('path', $parsed)) { // sometimes, stupid shit happens
				    $to_be_shown = $_SERVER['DOCUMENT_ROOT'] . $parsed['path'];
				    // $to_be_shown = str_overlap (PROOT, $parsed['path']);
				    // var_dump ($to_be_shown);
				    if (file_exists ($to_be_shown)) {
					    $gp->Set (array ('redirected_from' => $parsed['path'])); // for auth redirection purposes, mainly
					    require ($to_be_shown); // show the page
					    exit (); // finish replacing (similar to the [L] behaviour in htaccess)
				    }
				}
			}
		}
	} else {
		// I don't see how you can have a URL of 0 characters.
	}
	
	require (THINGS_404_PAGE); // Nothing else I can do for you ---> 404
?>
