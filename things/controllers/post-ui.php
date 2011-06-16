<?php
    require_once ('.things.php');
    
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
    
    if (isset ($_POST['submit'])) {
        CheckAuth (); // require a login. --> $user is available to you.
        // someone posted something
        $body = $_POST['body'];
        $title = $_POST['title'];
        $alias = $_POST['alias'];
        $tags = explode (',', $_POST['tags']);
        if ($id > 0) { // existing post
            $post = new Post ($id);
        } else {
            $post = new Post (NEW_POST);
            $id = $post->oid;
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
            foreach ($tags as $tag_name) {
                $tag_id = FindObject ($tag_name, TAG);
                if ($tag_id) {
                    $post->SetChildren (array ($tag_id));
                }
            }
        }
        println ("Saved as #<a href='/post/$id'>$id</a>", $win);
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
            $aliasstr = "value='" . htmlentities ($post->GetProp('alias'), ENT_QUOTES) . "'";
			$bodystr = htmlentities ($post->GetProp ('body'), ENT_QUOTES);
            $idstr = "<input type='hidden' name='id' value='$id' />";
            $tags = array ();
            $tag_ids = $post->GetChildren(TAG);
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
                    <fieldset>
                        $idstr
                        Title:* <input type='text' name='title' $titlestr style='width:90%;' />
                        <table style='width:100%'>
                            <tr>
                                <td style='width:50%'>
                                    Alias (/dir/file.htm): <input type='text' name='alias' $aliasstr />
                                </td>
                                <td style='width:50%'>
                                    Categories: <input type='text' value='$tags'>                    
                                </td>
                            </tr>
                        </table>
                        Body:* 
                        <textarea name='body' id='body' style='width:100%;height: 400px;'>$bodystr</textarea>
                    </fieldset>
                    <input type='submit' name='submit' value='submit' />
                </form>");
        page_out (array ('headers'=>'<script>if(typeof jQuery=="undefined"){document.write(unescape("%3Cscript src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js\" type=\"text/javascript\"%3E%3C/script%3E"));}</script>
                                      <script type="text/javascript" src="/scripts/tiny_mce/jquery.tinymce.js"></script>
                                      <script type="text/javascript" src="/scripts/.tinymce-loader.js"></script>'));
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
