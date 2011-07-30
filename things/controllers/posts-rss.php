<?php
    // RSS Feed Module
    // For external news services
    // Brian Lai
    
    /*  Structure
        <channel>
            <title>
            <link>
            <description>
            <pubDate>
        </>
        <item>
            <title>
            <link><![CDATA[ ]]>
            <description><![CDATA[ ]]>
            <pubDate>
        </> */

    header("Content-type: application/rss+xml"); // @IMPORTANT
	$import[] = "things.controllers.postcontroller,
	             things.lib.strings";
    require_once('.things.php');

    echo("<?xml version='1.0'?><rss version='2.0' xmlns:dc='http://purl.org/dc/elements/1.1/' xmlns:content='http://purl.org/rss/1.0/modules/content/' xmlns:admin='http://webns.net/mvcb/' xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#' xmlns:atom='http://www.w3.org/2005/Atom'>
            <channel>
                <title>http://ohai.ca</title>
                <link>http://ohai.ca</link>
                <description>ohai</description>
                <atom:link href='http://ohai.ca' rel='self' type='application/rss+xml' />
                <pubDate>" . date(DATE_RSS,time()) . "</pubDate>");
    
    $posts = PostController::GetPostsByTag ('home', 100);
    foreach ($posts as $post_id) {
		$post = new Post ($post_id);
		$tags = array (); // empty
		foreach ($post->GetTags() as $tag_id) {
			$tag = new Tag ($tag_id);
			$tags[] = $tag->GetProp ('name');
		}
		printf ("<item>
					<title><![CDATA[%s]]></title>
					<link>http://ohai.ca%s</link>
					<description><![CDATA[%s]]></description>
					<pubDate>%s</pubDate>
				</item>",
				$post->GetTitle(),
				'/post/' . $post->oid,
				strip_tags (first ($post->GetProp ('body'), 500),'<p><a><b><img><div><span>'),
				date(DATE_RFC822, $post->GetPostTime ()));
    }
?>
</channel>
</rss>
