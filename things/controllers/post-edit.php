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
        $aliasstr = "value='" . htmlspecialchars ($post->GetProp ('alias'), ENT_QUOTES) . "'";
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
                <textarea name='body' id='body' style='width:100%;'>$bodystr</textarea>
                
                $idstr
                <br />
                
                <!--fieldset>
                    <legend>Posting type</legend>
                    <input type='radio' name='pubtype' value='post' /><label for='pubtype'>Post (for blogs)</label><br />
                    <input type='radio' name='pubtype' value='page' /><label for='pubtype'>Page (allows any content)</label><br />
                    <input type='radio' name='pubtype' value='html' /><label for='pubtype'>Pure HTML</label><br />
                </fieldset-->
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
        echo (" <input type='submit' name='delete' value='Delete Post (!)' />");
	}
	echo (" </form>
            <script type='text/javascript' src='/scripts/nicedit/nicEdit.min.js'></script>
            <script type='text/javascript' src='/scripts/.nicedit-loader.js'></script>");
    render ();
    exit();
?>
