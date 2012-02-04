<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by post-ui.php.
        // it uses variables only available from post-ui.php.
        die ();
    }
     
    $titlestr = '';
    $bodystr  = '';
    $aliasstr = '';
    $idstr    = '';
    $tags     = '';
    
    if ($id > 0 && strlen ($edit) > 0) { // existing post
        $post = new Post ($id);
        $titlestr = "value='" . htmlspecialchars ($post->GetTitle (), ENT_QUOTES) . "'";
        $aliasstr = "value='" . htmlspecialchars ($post->GetProp ('permalink'), ENT_QUOTES) . "'";
        $bodystr = htmlspecialchars ($post->GetProp ('body'), ENT_QUOTES);
     
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
                <textarea name='body' id='body' style='width:98%;min-height:400px;'>$bodystr</textarea>
             
                $idstr
                <br />
             
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
                <input type='submit' name='submit' value='Save' />");
    if ($id > 0) {
        echo (" <input type='button' value='Discard changes' onclick='window.location=\"/post/" . $id . "\";' />
                 <input type='submit' name='delete' value='Delete Post (!)' />");
    }
    echo (" </form>
            <!--script type='text/javascript' src='<!--root-->scripts/nicedit/nicEdit.min.js'></script>
            <script type='text/javascript' src='<!--root-->scripts/.nicedit-loader.js'></script-->
            <script type='text/javascript' src='<!--root-->scripts/tiny_mce/jquery.tinymce.js'></script>
            <script type='text/javascript' src='<!--root-->scripts/.tinymce-loader.js'></script>");
    render (array (
        'title' => 'Post editor',
        'headers' => '<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>'
    ));
    exit();
?>
