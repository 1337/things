<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by post-ui.php.
        // it uses variables only available from post-ui.php.
        die ("Wrong");
    }
	    
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
             $auth->IsLoggedIn () ? " [<a href='<!--root-->edit/post/" . $id . "'>Edit</a>]": '',
             WEBROOT . substr ($post->GetProp ('permalink'),1),
             WEBROOT . substr ($post->GetProp ('permalink'),1));
?>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = 'ohaica';             
        var disqus_identifier = 'post_<?php echo ($id); ?>';
        var disqus_url = '<!--root-->post/<?php echo ($id); ?>';
 
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
?>