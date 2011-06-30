<?php
    require_once ('.things.php');
    
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
	$body = $gp->Get ('body');
	$title = $gp->Get ('title');
	$alias = $gp->Get ('alias');
	$tagstr = $gp->Has ('tags') ? $gp->Get ('tags') : '';
    
	if (isset ($_POST['submit']) || $new || $edit) {
		CheckAuth (); // require a login. --> $user is available to you.
	} else {
		// view doesn't require privileges.
	}
	
    if (isset ($_POST['submit'])) {
        require ('post-new.php');
    }
    
    if (strlen ($new)  > 0 || 
       (strlen ($edit) > 0 && $id > 0 && GetObjectType ($id) == POST)) {
        // this is if new, or old and valid ID
        
        $titlestr = '';
        $bodystr  = '';
        $aliasstr = '';
        $idstr    = '';
        $tags     = '';
        if ($id > 0 && strlen ($edit) > 0) { // existing post
            $post = new Post ($id);
            $titlestr = "value='" . htmlentities ($post->GetTitle (), ENT_QUOTES) . "'";
            $aliasstr = "value='" . htmlentities ($post->GetProp ('alias'), ENT_QUOTES) . "'";
			$bodystr = htmlentities ($post->GetProp ('body'), ENT_QUOTES);
            $idstr = "<input type='hidden' name='id' value='$id' />";
            $tags = array ();
            $tag_ids = $post->GetChildren (TAG);
            if (sizeof ($tag_ids) > 0) {
                foreach ($tag_ids as $tag_id) {
                    $tag = new Tag ($tag_id);
                    $tags[] = $tag->GetProp('name');
                }
            }
            if (sizeof ($tags) > 0) {
                $tags = implode (', ', $tags);
            } else {
                $tags = '';
            }
        }
        echo (" <form method='post'>
		            <h1>New Post</h1>
		            <textarea name='body' id='body' style='width:100%;'>$bodystr</textarea>
					
					$idstr
					<br />
					
                    <fieldset>
					    <legend>Posting type</legend>
						<input type='radio' name='pubtype' value='post' /><label for='pubtype'>Post (for blogs)</label><br />
						<input type='radio' name='pubtype' value='page' /><label for='pubtype'>Page (allows any content)</label><br />
						<input type='radio' name='pubtype' value='html' /><label for='pubtype'>Pure HTML</label><br />
					</fieldset>				
					<fieldset>
					    <legend>Post options</legend>
						
						<label for='title'>Title:</label>
						<input type='text' id='title' name='title' $titlestr style='width:98%;' /><br />
					
						<label for='alias'>Friendly URL (/dir/file.htm):</label>
						<input type='text' id='alias' name='alias' $aliasstr style='width:98%;' /><br />
							
						<label for='tags'>Tags, separated by commas: </label>
						<input type='text' id='tags' name='tags' value='$tags' style='width:98%;' /><br />
					</fieldset>
					<br />
                    <input type='submit' name='submit' value='submit' />
                </form>
				<script type='text/javascript' src='/scripts/nicedit/nicEdit.min.js'></script>
				<script type='text/javascript' src='/scripts/.nicedit-loader.js'></script>
				<script type='text/javascript'>window.onload = function () {document.getElementById('body').focus();}</script>");
        page_out ();
        exit();
    } else {
        if (isset ($_GET['id'])) { // view post
            $page_id = $_GET['id'];
            if ($page_id > 0 && GetObjectType ($page_id) == POST) {
                $page = new Post ($page_id);
                $authors = $page->GetParents(USER);
                if (sizeof ($authors) > 0) {
                    $author = new User ($authors[0]); // first guy out always wins the jackpot
                    $author = $author->GetProp ('name');
                } else {
                    $author = 'someone';
                }
                printf ("<h1>%s</h1>
                         <div>%s</div>
                         <p>Last edited %s by %s</p>",
                         $page->GetTitle(),
						 $page->GetProp ('body'),
                         date("l \\t\h\e jS", $page->GetPostTime ()),
                         $author);
?>
                <div id="disqus_thread"></div>
                <script type="text/javascript">
                    var disqus_shortname = 'ohaica';                
                    var disqus_identifier = 'post_<?php echo($page_id); ?>';
                    var disqus_url = 'http://ohai.ca/post/<?php echo($page_id); ?>';
                
                    (function() {
                        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                    })();
                </script>
<?php
                page_out (array('title'=>$page->GetTitle()));
                exit ();
            } else {
                echo ("You evil child...");
            }
        } else {
            echo ("You. Get off my lawn.");
        }
    }
    page_out ();
?>
