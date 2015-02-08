<?php

class Form_Task extends Zend_Form
{
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post');

      $taskId = $this->createElement('hidden', 'id');
      $this->addElement($taskId);
      
      // Title:
      $title = $this->createElement('text', 'title');
      $title->setRequired(true)
                  ->setDescription('Title of task.')
                  ->setLabel('Title:')
                  ->setFilters(array('StringTrim'))
                  ->addValidator('StringLength', false,
                     array('min' => 0, 'max' => 50));
      $this->addElement($title);
      
      // Notes:
      $body = $this->createElement('textarea', 'notes');
      $body->setRequired(true)
           ->setLabel('Notes:')
           ->setDescription('Description of task, 500 chars max.')
           ->setFilters(array('StringTrim'))
           ->addValidator('StringLength', false,
              array('min' => 10, 'max' => 500,
              'messages' => array(Zend_Validate_StringLength::TOO_LONG => 'Notes can be up to 500 chars')));
      $this->addElement($body);

      // Date requested:
      $dateRequested = $this->createElement('text', 'date_requested');
      $dateRequested->setLabel('Date Requested:')
                 ->setRequired(true)
                 ->setDescription('Date the task was requested. Should be in format DD/MM/YYYY.')
                 ->setFilters(array('StringTrim'))
                 ->addValidator('Date', false,
                    array('format' => 'dd/MM/yyyy', 'messages' =>
                       array(Zend_Validate_Date::FALSEFORMAT => 'Not a valid date - should be DD/MM/YYYY')));
      $this->addElement($dateRequested);
      
      // Requested by:
      $requestedBy = $this->createElement('text', 'requested_by');
      $requestedBy->setRequired(true)
                  ->setDescription('Names of those that requested the work.')
                  ->setLabel('Requested By:')
                  ->setFilters(array('StringTrim'))
                  ->addValidator('StringLength', false,
                     array('min' => 0, 'max' => 100));
      $this->addElement($requestedBy);
      
      // Assigned To:
      $assigneeList = $this->createElement('select', 'assigned_to');
      $assigneeList->setLabel('Assigned To:')
               ->setDescription('Person responsible for this task.')
               ->setRegisterInArrayValidator(false)
               ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                  array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please assign this task to somebody')));
      $this->addElement($assigneeList);
            
      // Date completed:
      $dateCompleted = $this->createElement('text', 'date_completed');
      $dateCompleted->setLabel('Date Completed:')
                 ->setRequired(false)
                 ->setDescription('Date the task was completed. Should be in format DD/MM/YYYY.')
                 ->setFilters(array('StringTrim'))
                 ->addValidator('Date', false,
                    array('format' => 'dd/MM/yyyy', 'messages' =>
                       array(Zend_Validate_Date::FALSEFORMAT => 'Not a valid date - should be DD/MM/YYYY')));
      $this->addElement($dateCompleted);

      // Priority:
      $priority = $this->createElement('text', 'priority');
      $priority->setRequired(false)
            ->setLabel('Priority:')
            ->setDescription('Priority of task, with 1 the highest.')
            ->setFilters(array('StringTrim'))
            ->addValidator('Digits')
            ->addValidator('Between', false, 
               array('min' => 1, 'max' => 1000));
      $this->addElement($priority);

      // Effort:
      $effort = $this->createElement('text', 'effort');
      $effort->setRequired(false)
            ->setLabel('Effort:')
            ->setDescription('Approx. hours spent on task. Whole numbers only.')
            ->setFilters(array('StringTrim'))
            ->addValidator('Digits')
            ->addValidator('Between', false, 
               array('min' => 1, 'max' => 1000));
      $this->addElement($effort);

      // Cost:
      $cost = $this->createElement('text', 'cost');
      $cost->setRequired(false)
            ->setLabel('Cost:')
            ->setDescription('Money spent on completing the task.')
            ->addValidator('Float');
      $this->addElement($cost);
   }
}
