<?php

class Form_DocumentFilter extends Zend_Form
{
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post');

      // Document type:
      $typeList = $this->createElement('select', 'doc_type');
      $this->addElement($typeList);
      
      // Title:
      $title = $this->createElement('text', 'doc_title');
      $this->addElement($title);

      // Date:
      $dateList = $this->createElement('select', 'doc_date');
      $this->addElement($dateList);
   }

}
