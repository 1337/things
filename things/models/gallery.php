<?php
    // photo gallery.
    class Gallery extends Thing {
        /* I think it's better to do indexed properties
           because people can then "own" gallery.
           Gallery - {
                        photos = [url, url, url]
                        description = url
                     } */
        
        function AddPhoto ($url) {
            // adds a photo to the lowest-indexed photo[].
            
        }
        
        function AddPhotos ($urls) {
            // accepts an array of URLs and appends them to the list.
        }
    }
?>