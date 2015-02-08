<?php

class Form_Car extends Zend_Form
{

   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post');

      $carId = $this->createElement('hidden', 'id');
      $this->addElement($carId);

      // Flat:
      $flatList = $this->createElement('select', 'flat');
      $flatList->setRegisterInArrayValidator(false)
               ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                  array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please select a Flat')));
      $this->addElement($flatList);
      
      // Registration:
      $carReg = $this->createElement('text', 'registration');
      $carReg->setFilters(array('StringTrim', 'StringToUpper'))
                ->setRequired(TRUE)
                ->addValidator('StringLength', false,
                    array('min' => 3, 'max' => 10));
      $this->addElement($carReg);

      // Make:
      $carMake = $this->createElement('text', 'make');
      $carMake->setFilters(array('StringTrim'))
                ->setRequired(TRUE)
                ->addValidator('StringLength', false,
                    array('min' => 2, 'max' => 50));
      $this->addElement($carMake);

      // Model:
      $carModel = $this->createElement('text', 'model');
      $carModel->setFilters(array('StringTrim'))
                ->setRequired(TRUE)
                ->addValidator('StringLength', false,
                    array('min' => 2, 'max' => 50));
      $this->addElement($carModel);

         // Colour:
      $carColour = $this->createElement('text', 'colour');
      $carColour->setFilters(array('StringTrim'))
                ->setRequired(TRUE)
                ->addValidator('StringLength', false,
                    array('min' => 2, 'max' => 50));
      $this->addElement($carColour);
   }

}
