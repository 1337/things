<?php
    require_once ('.things.php');
    CheckAuth ("Administrative privilege"); // require a login. --> $user is available to you.
?>
    <h1>Site tools</h1>
    <ul>
    	<li><a href="<!--root-->things/tools/cvs.php">File editor</a></li>
    	<li><a href="<!--root-->things/tools/gexec.php">Graphical SQL UI</a></li>
    	<li><a href="<!--root-->things/tools/gview.php">Database viewer</a></li>
    	<li><a href="<!--root-->things/tools/install_db.php">Database install script</a></li>
    	<li><a href="<!--root-->things/tools/bom.php">Byte-order mark (BOM) checker</a></li>
    </ul>
    <h2>Cleanup</h2>
    <p>Operation will take place immediately after the link is clicked.</p>
    <ul>
    	<li><a href="<!--root-->things/tools/nobak.php">Remove all backup (.bak) files</a></li>
    	<li><a href="<!--root-->things/tools/noerrorlog.php">Remove all PHP error logs</a></li>
    	<li><a href="<!--root-->things/tools/nosvn.php">Remove all SVN directories</a></li>
    </ul>
<?php
    render (array (
        'title' => 'Tools'
    ));
?>