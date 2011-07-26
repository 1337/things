<?php
    require_once ('.things.php');
    
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
	$body = $gp->Get ('body');
	$title = $gp->Get ('title');
	$alias = $gp->Get ('alias');
	$tagstr = $gp->Has ('tags') ? $gp->Get ('tags') : '';
    
	if (isset ($_POST['submit']) || $new == 1 || $edit == 1) {
		CheckAuth (); // require a login. --> $user is available to you.
	} else {
		// view doesn't require privileges.
	}
	
    if (isset ($_POST['submit'])) {
        require ('post-new.php');
    }

    if (isset ($_POST['delete'])) { // well
        $post = new Post ($id);
		$post->Destroy ();	
		header ("location: " . WEBROOT . "menu");
    }
    
    if (strlen ($new)  > 0 || 
       (strlen ($edit) > 0 && $id > 0 && GetObjectType ($id) == POST)) {
        // this is if new, or old and valid ID
        require ('post-edit.php');
    } else {
        // the only possible action remaining is to view a post
        require ('post-view.php');
    }
    render ();
?>
