<?php
    class Page extends Thing {
        /*private $body;
     
        function GetBody () {
            // if the post has a "body" property, return it as body text.
            // otherwise, look for a "bodyhref" property and load the file.
            $props = $this->GetProps ();
            if (array_key_exists ('body', $props)) {
                $this->body = $props['body']; //cache
                return $this->body;
            } else {
                // check for a url tag and see if it exists
                if (array_key_exists ('bodyhref', $props) && file_exists ($props['bodyhref'])) {
                    $this->body = implode('', file($props['bodyhref']));
                    return $this->body;
                }
                return null; // :(
            }
        }
     
        function SetBody ($what) {
            // create the file, then SetProp bodyhref to that file.
            $cwd = getcwd ();
            $this->body = ''; // flush cache
            do {
                $randchars = substr (md5(time().rand()), 0, 10); // pick random string
                $filename = "things/props/$randchars.php";
            } while (file_exists ($filename) == true);
         
            $fh = @fopen($filename, 'w') or die("cannot create post $cwd/$filename :(");
            @fwrite ($fh, stripslashes($what));
            fclose ($fh);
         
            $existing_post = $this->GetProp('bodyhref');
            if (!is_null ($existing_post)) {
                // if old post exists, then delete it.
                unlink ($existing_post);
            }
         
            $this->SetProps (array ('bodyhref'=>$filename)); // set file name
            $this->DelProps (array ('body')); // because body is prioritised, it must be killed
        }*/
     
    }
?>
