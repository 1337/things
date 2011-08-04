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
	    if ($id > 0) {
			if ($id > 0 && GetObjectType ($id) == POST) {
				$post = new Post ($id);
				$post->SetProp ('views', $post->GetProp ('views') + 1); // what do you think that means?
				$authors = $post->GetParents (USER);
				if (sizeof ($authors) > 0) {
					$author = new User ($authors[0]); // first guy out always wins the jackpot
					$author = $author->GetProp ('name');
				} else {
					// no author found. What?
					$author = 'someone';
				}
				printf ("<h1>%s</h1>
						 <div>%s</div>
						 <p>&nbsp;</p>
						 <p>Last edited %s by %s%s<br />
						    Permalink: <a href='%s'>%s</a></p>",
						 $post->GetTitle(),
						 $post->GetProp ('body'),
						 date("F j, Y", $post->GetPostTime ()),
						 $author,
						 $auth->IsLoggedIn () ? " [<a href='" . WEBROOT . "edit/post/" . $id . "'>Edit</a>]": '',
						 $post->GetProp ('permalink'),
						 $post->GetProp ('permalink'));
	?>
				<div id="disqus_thread"></div>
				<script type="text/javascript">
					var disqus_shortname = 'ohaica';                
					var disqus_identifier = 'post_<?php echo ($id); ?>';
					var disqus_url = 'http://ohai.ca/post/<?php echo ($id); ?>';
				
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();
				</script>
	<?php
				render (array(
				    'title'=>$post->GetTitle()
				));
				exit ();
			} else {
				println ("This ID specified is not a post.", $fail);
			}
		} else {
			println ("For which post are you looking?", $fail);
		}
    }
    render (array (
	    'title' => 'Posts'
	));
?>
