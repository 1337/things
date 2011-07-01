<?php
    require_once ('../../.things.php');
    require_once (PROOT . 'lib/strings.php');

    $og = new Things (POST);
    if ($gp->Has('q')) {
        $tag = $gp->Get('q');
    } else {
        $tag = 'home'; // default
    }
    $tp = new Tag (FindObject ($tag, TAG));
	$counter = 0;
    foreach ($tp->GetPosts () as $post_id) {
        $counter++;
        $post = new Post ($post_id);
        $title = $post->GetTitle();
        printf ("<p><a href='/post/$post_id'>$title</a></p>");
    }    
    render (array ('title'=>"Archives"));
?>