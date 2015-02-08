<?php

class Form_Resident extends Zend_Form
{

   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post');

      $residentId = $this->createElement('hidden', 'id');
      $this->addElement($residentId);

      // Flat:
      $flatList = $this->createElement('select', 'flat');
      $flatList->setRegisterInArrayValidator(false)
               ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                  array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please select a Flat')));
      $this->addElement($flatList);
      
      // Resident name:
      $residentName = $this->createElement('text', 'name');
      $residentName->setFilters(array('StringTrim'))
                ->setRequired(TRUE)
                ->addValidator('StringLength', false,
                    array('min' => 5, 'max' => 50));
      $this->addElement($residentName);

      // Resident status:
      $residentStatus = $this->createElement('select', 'status');
      $residentStatus->setRegisterInArrayValidator(false)
                     ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                      array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please select a Status')));
            $this->addElement($residentStatus);

      // Resident role:
      $role = $this->createElement('select', 'role');
      $role->setRegisterInArrayValidator(false);
      $this->addElement($role);

      // Resident email:
      $residentEmail = $this->createElement('text', 'email');
      $residentEmail->setFilters(array('StringTrim'))
                ->addValidator('StringLength', false,
                    array('min' => 5, 'max' => 50))
                ->addValidator('EmailAddress');
      $this->addElement($residentEmail);
   }

}
