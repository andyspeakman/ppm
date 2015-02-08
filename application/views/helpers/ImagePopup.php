<?php
class Zend_View_Helper_ImagePopup extends Zend_View_Helper_Abstract
{
   public function imagePopup($imageFile, $imageName, $size = 80)
   {
      $html = '<a href="' . $imageFile . '" ';
      $html .= 'class="fancybox" '; 
      $html .= 'title="'. $imageName . '" rel="fancybox">'; 
      $html .= '<img src="'. $imageFile . '" height="' . $size . 'px " alt="' . $imageName . '" />';
      $html .= '</a>'; 
      
      return $html;
   }
}
?>
