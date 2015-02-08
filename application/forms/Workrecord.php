<?php

class Form_Workrecord extends Zend_Form
{
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post')
           ->setAction('save');

      $messageId = $this->createElement('hidden', 'id');
      $this->addElement($messageId);
      
      // Date expires:
      $workDate = $this->createElement('text', 'work_date');
      $workDate->setLabel('Date:')
                 ->setRequired(true)
                 ->setDescription('Date the work take place. Should be in format DD/MM/YYYY.')
                 ->setFilters(array('StringTrim'))
                 ->addValidator('Date', false,
                    array('format' => 'dd/MM/yyyy', 'messages' =>
                       array(Zend_Validate_Date::FALSEFORMAT => 'Not a valid date - should be DD/MM/YYYY')));
      $this->addElement($workDate);

      // Notes:
      $body = $this->createElement('textarea', 'notes');
      $body->setRequired(true)
           ->setLabel('Notes:')
           ->setDescription('Description of work, 500 chars max.')
           ->setFilters(array('StringTrim'))
           ->addValidator('StringLength', false,
              array('min' => 10, 'max' => 500,
              'messages' => array(Zend_Validate_StringLength::TOO_LONG => 'Notes can be up to 500 chars')));
      $this->addElement($body);

      // Hours:
      $hours = $this->createElement('text', 'hours');
      $hours->setRequired(true)
            ->setLabel('Hours:')
            ->setDescription('Approx. hours spent on work. Whole numbers only.')
            ->setFilters(array('StringTrim'))
            ->addValidator('Digits')
            ->addValidator('Between', false, 
               array('min' => 1, 'max' => 24));
      $this->addElement($hours);
   }
}
