<?php

class Form_Contractor extends Zend_Form
{

   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post');
      $this->addElementPrefixPath('Lightman_Validator', 'Lightman/Validator', 'validate');

      $contractorId = $this->createElement('hidden', 'id');
      $this->addElement($contractorId);
      
      $typeList = $this->createElement('select', 'contractor_type');
      $typeList->setLabel('Category:')
               ->setDescription('Type of company.')
               ->setRegisterInArrayValidator(false)
               ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                  array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please select a Category')));
      $this->addElement($typeList);
      
      // Company name:
      $companyName = $this->createElement('text', 'company_name');
      $companyName->setRequired(true)
                  ->setDescription('Name of the company.')
                  ->setLabel('Company Name:')
                  ->setFilters(array('StringTrim'))
                  ->addValidator('StringLength', false,
                     array('min' => 0, 'max' => 50));
      $this->addElement($companyName);

      // Company website:
      $website = $this->createElement('text', 'website');
      $website->setLabel('Website:')
                ->setDescription('Company\'s website address.')
                ->setFilters(array('StringTrim'))
                ->addValidator('StringLength', false,
                    array('min' => 0, 'max' => 100));
      $this->addElement($website);
      
      // Company telno:
      $companyTelno = $this->createElement('text', 'company_telno');
      $companyTelno->setLabel('Company Tel.:')
                ->setDescription('Company\'s main telephone number.')
                ->setFilters(array('StringTrim'))
                ->setRequired(true)
                ->addValidator('Phone');
      $this->addElement($companyTelno);
      
      // Contact name:
      $contactName = $this->createElement('text', 'contact_name');
      $contactName->setLabel('Contact Name:')
                ->setDescription('Name of our contact at this company.')
                ->setFilters(array('StringTrim'))
                ->addValidator('StringLength', false,
                    array('min' => 5, 'max' => 50));
      $this->addElement($contactName);
      
      // Contact telno:
      $contactTelno = $this->createElement('text', 'contact_telno');
      $contactTelno->setLabel('Contact Tel.:')
                ->setDescription('Contact telephone number for our contact.')
                ->setFilters(array('StringTrim'))
                ->addValidator('Phone');
      $this->addElement($contactTelno);
      
      // Notes:
      $notes = $this->createElement('textarea', 'notes');
      $notes->setRequired(false)
           ->setLabel('Notes:')
           ->setDescription('Notes related to the contractor, 200 chars max.')
           ->setFilters(array('StringTrim'))
           ->addValidator('StringLength', false,
              array('min' => 10, 'max' => 200,
              'messages' => array(Zend_Validate_StringLength::TOO_LONG => 'Notes can be up to 200 chars')));
      $this->addElement($notes);
   }

}
