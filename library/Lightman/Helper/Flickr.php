<?php

class Lightman_Helper_Flickr
{
     
    public static function convertFlickrPicToArray($flickrPic)
    {
        // Get the largest photo that isn't over 960px wide:
        if (!empty($flickrPic->Original) && $flickrPic->Original->width < 960) {
            $uri = $flickrPic->Original->uri;
        } elseif (!empty($flickrPic->Large) && $flickrPic->Large->width < 960) {
            $uri = $flickrPic->Large->uri;
        } else {
            $uri = $flickrPic->Medium->uri;
        }
        
        return array('thumbwidth' => $flickrPic->Small->width,
         'thumbheight' => $flickrPic->Small->height,
         'thumburi' => $flickrPic->Small->uri,
         'mainuri' => $uri,
         'title' => $flickrPic->title,
         'owner' => $flickrPic->ownername);
    }
     
}

?>