<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by post-ui.php.
        // it uses variables only available from post-ui.php.
        die ("Wrong");
    }
	    
    if (isset ($_GET['id'])) {
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
                     date("F j, Y", $page->GetPostTime ()),
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
            render (array('title'=>$page->GetTitle()));
            exit ();
        } else {
            echo ("You evil child...");
        }
    } else {
        echo ("You. Get off my lawn.");
    }
?>
