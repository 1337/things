<?php
    // this thing's job is to SWALLOW
    // the files corresponding to the input URL to show where they belong.
    require_once ('.things.php');
    
    $rules = array_merge (array (
        // rule => replacement, just like in .htaccess
        '^/(\w+)/([0-9]+)'                            => '/things/controllers/$1-ui.php?id=$2',
        '^/css/(.+)?$'                                => '/things/controllers/css.php?f=$1',
        '^/(\w+)/([0-9]+)'                            => '/things/controllers/$1-ui.php?id=$2',
        '^/new/(\w+)/?$'                              => '/things/controllers/$1-ui.php?new=1',
        '^/new/(\w+)/([0-9]+)'                        => '/things/controllers/$1-ui.php?new=1&id=$2',
        '^/edit/(\w+)/([A-Za-z0-9]+)'                 => '/things/controllers/$1-ui.php?id=$2&edit=1',
        '^/delete/(\w+)/([0-9]+)'                     => '/things/controllers/$1-ui.php?id=$2&delete=1',
        '^/edit/(\w+)/([0-9]+)'                       => '/things/controllers/$1-ui.php?id=$2&edit=1',
        '^/delete/(\w+)/([A-Za-z0-9]+)'               => '/things/controllers/$1-ui.php?id=$2&delete=1',
        '^/limit/(.+)$'                               => '/things/controllers/limiter.php?site=$1',
        '^/filesearch/(.+)'                           => '/things/controllers/filesearch.php?q=$1',
        '^/login'                                     => '/things/controllers/login.php',
        '^/logout'                                    => '/things/controllers/logout.php',
        '^/menu/?'                                    => '/things/views/menu.php',
        '^/objects/?'                                 => '/things/controllers/objects-viewer.php',
        '^/tickets/?'                                 => '/things/controllers/tickets.php',
        '^/register/?'                                => '/things/views/register.php'
    ), $redirect_rules); // redirect_rules (your custom redirctions) can be found in config/config.php
    
    LoadPage ($_SERVER['REQUEST_URI']); // kickstart
?>