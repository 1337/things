<?php
    class PostController {
		static function GetPostsByTag ($tag, $limit) {
	    	$og = new Things (POST);
			$tp = new Tag (FindObject ($tag, TAG));
			return $tp->GetPosts ();
		}
	}
?>