<?php

class Form_Message extends Zend_Form
{
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post')
           ->setAction('save');

      $messageId = $this->createElement('hidden', 'id');
      $this->addElement($messageId);
      
      // Title:
      $title = $this->createElement('text', 'title');
      $title->setRequired(true)
            ->setLabel('Title:')
            ->setDescription('Title of the message, up to 50 chars.')
            ->setFilters(array('StringTrim'))
            ->addValidator('StringLength', false,
               array('min' => 10, 'max' => 50));
      $this->addElement($title);

      // Body:
      $body = $this->createElement('textarea', 'notes');
      $body->setRequired(true)
           ->setLabel('Message:')
           ->setDescription('Body of the message, 500 chars max.')
           ->setFilters(array('StringTrim'))
           ->addValidator('StringLength', false,
              array('min' => 10, 'max' => 500,
              'messages' => array(Zend_Validate_StringLength::TOO_LONG => 'Notes can be up to 500 chars')));
      $this->addElement($body);

      // Date expires:
      $expiryDate = $this->createElement('text', 'date_expires');
      $expiryDate->setLabel('Expiry Date:')
                 ->setDescription('Date the message expires, if any. Should be in format DD/MM/YYYY.')
                 ->setFilters(array('StringTrim'))
                 ->addValidator('Date', false,
                    array('format' => 'dd/MM/yyyy', 'messages' =>
                       array(Zend_Validate_Date::FALSEFORMAT => 'Not a valid date - should be DD/MM/YYYY')));
      $this->addElement($expiryDate);
   }
}
