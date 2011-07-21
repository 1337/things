<?php
    $import[] = "things.lib.strings";
    require_once ('.things.php');

    $og = new Things (POST);
    if ($gp->Has('tag')) {
        $tag = $gp->Get('tag');
    } else {
        $tag = 'home'; // default
    }
    $tp = new Tag (FindObject ($tag, TAG));
	// $counter = 0;
	$posts = new Paginate (array (
	    'objects' => $tp->GetPosts (),
		'page_size' => 50
	));
    foreach ($posts->GetObjects () as $post_id) {
        // $counter++;
        $post = new Post ($post_id);
        $title = $post->GetTitle();
		$snippet = first (strip_tags ($post->GetProp ('body')), 100);
        printf ("<p><b><a href='/post/$post_id'>$title</a></b>
		    <br />
		    <span style='color:gray;'>$snippet</span></p>");
    }
	
	echo ($posts->Bar ());
    render (array ('title'=>"Archives"));
?>