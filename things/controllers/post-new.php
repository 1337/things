<?php
    // this file must be called by post-ui.php
	    
	// someone posted something
	$tags = explode (',', $tagstr);
	
	if ($id > 0) { // existing post
		$post = new Post ($id);
	} else {
		$post = new Post (NEW_POST);
		$id = $post->oid;
	}
	if (strlen ($title) == 0) {
		// automatic title
		$max_len = (strlen ($body) < 100) ? strlen ($body) : 100;
		$title = substr ($body, 0, $max_len);
		if ($max_len == 100) {
			$title .= "..."; // indicating "too long"
		}
	}
	$post->SetProps (
		array ('name'=>$title, 
			   'db_time'=>time (),
			   'body' => $body
	));
	
	if (isset ($alias) && strlen ($alias) > 0) {
		if (substr ($alias, 0, 1) != '/') {
			$alias = '/' . $alias;
		}
	} else {
		$alias = '/' . $post->MakeSEO ($title);
	}
	$post->SetProps (array ('alias'=>$alias));

	if (!is_null ($user)) {
		$user->SetChildren (array ($post->oid));
	}
	if (sizeof ($tags) > 0) {
		$post->DelChildren ($post->GetChildren (TAG)); // delete all existing tags
		foreach ($tags as $tag_name) {
			$tag_id = FindObject ($tag_name, TAG);
			if ($tag_id) {
				$post->SetChildren (array ($tag_id));
			}
		}
	}
	println ("Saved as #<a href='/post/$id'>$id</a>", $win);
?>