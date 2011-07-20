<?php
    /*  entry point of Things
        Things must be included at the first line of every php file. This is law.
        DO NOT MODIFY REQUIRE/INCLUDE PLACEMENTS
    
        To develop, follow http://www.odi.ch/prog/design/php/guide.php 
       
        /                        Where your app is supposedly served
        /things/                 Contains config.php
        /things/models/          Contains DAOs and their tools
        /things/views/           Contains page "mako templates" except they aren't mako
        /things/controllers/     Contains the Value Object classes
        /things/test/            Tests.
    */
    
    error_reporting (E_ALL);
    date_default_timezone_set('America/Toronto'); // https://twitter.com/#!/marcoarment/status/59089853433921537
    $epoch = microtime ();
    ob_start();
    
	
    define ('PROOT', dirname (__FILE__) . '/'); // the things path is there
	define ('WEBROOT', 
	    "http://" . $_SERVER['SERVER_NAME'] . 
		substr (dirname (dirname (realpath (__FILE__))), strlen ($_SERVER['DOCUMENT_ROOT'])) . 
		'/'
	);
	require_once (PROOT . 'config/config.php'); // import configurations immediately
    
    // Stuff you'll be loading. sort by time of use.
    $import[] ="things.lib.strings,
	            things.lib.datetime,
	            things.lib.json,
	            things.lib.compressor,
	            things.models.superglobal, 
                things.controllers.core, 
                things.config.mysql_connect, 
                things.models.mysql, 
                things.models.things, 
                things.models.thing, 
                things.models.gpvar, 
                things.models.user,
                things.models.group,
                things.models.privilege,
                things.controllers.auth,
                things.models.post,
                things.models.tag,
                things.models.setting,
                things.models.page,
                things.controllers.validator,
                things.models.ticket,
                things.controllers.view,
                things.models.gallery,
                things.controllers.ajaxfield,
				things.controllers.paginate";
    
    /*  IMPORT HELPER
        USE LIKE THIS:
        $import[] = "java.awk.*,
                     java.awk.styler";
        require_once ('.things.php');
    */
    if (isset ($import) && sizeof ($import) > 0) {
        foreach ($import as $w) {
            foreach (
                explode (',', 
                    implode (',', 
                        array_map (
                            create_function (
							    '$z',
								'return implode(
								    ",",
									glob("$z.php")
								);'
							), 
                            array_map ('trim',
                                explode (',',
                                    str_replace ('.', '/', 
                                        str_replace (
										    'things.', 
											PROOT, 
											$w
									    )
                                    )
                                )
                            )
                        )
                    )
                ) as $x) {
                    if ($x) {
						require_once ($x);
					}
            }
        }
    }
    
?>
