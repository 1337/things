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

    require_once('../../.things.php');
    require_once(PROOT . 'lib/strings.php');
    header("Content-type: application/rss+xml"); // @IMPORTANT

    echo("<?xml version='1.0'?><rss version='2.0' xmlns:dc='http://purl.org/dc/elements/1.1/' xmlns:content='http://purl.org/rss/1.0/modules/content/' xmlns:admin='http://webns.net/mvcb/' xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#' xmlns:atom='http://www.w3.org/2005/Atom'>
            <channel>
                <title>http://ohai.ca</title>
                <link>http://ohai.ca</link>
                <description>ohai</description>
                <atom:link href='http://ohai.ca' rel='self' type='application/rss+xml' />
                <pubDate>" . date(DATE_RSS,time()) . "</pubDate>");
 
    $og = new Things (POST);
    // foreach ($og->GetObjects () as $post_id) {
    $tp = new Tag (FindObject ('home', TAG));
    $limit = 100;
    $counter = 0;
    foreach ($tp->GetPosts () as $post_id) {
        $counter++;
        if ($counter < $limit) {
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
                    date(DATE_RFC822,$post->GetPostTime ()));
        } else {
            break;
        }
    }
?>
</channel>
</rss>
