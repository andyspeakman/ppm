<?php

class Form_ForgottenPassword extends Zend_Form
{
    /**
     * init() is the initialization routine called when Zend_Form objects are 
     * created. In most cases, it make alot of sense to put definitions in this 
     * method, as you can see below.  This is not required, but suggested.  
     * There might exist other application scenarios where one might want to 
     * configure their form objects in a different way, those are best 
     * described in the manual:
     *
     * @see    http://framework.zend.com/manual/en/zend.form.html
     * @return void
     */ 
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post')
           ->setAction('forgottenpassword');
      
      // Email address:
      $email = $this->createElement('text', 'email');
      $email->setRequired(true)
            ->setLabel('Your email address:')
            ->setDescription('Must be the email address registered with PPM.')
            ->setFilters(array('StringTrim', 'StringToUpper'))
            ->addValidator('EmailAddress');
      $this->addElement($email);
      
      // Flat number:
      $flatno = $this->createElement('text', 'flatno');
      $flatno->setLabel('Flat number:')
                 ->setDescription('Please don\'t use spaces between numbers and letters')
                 ->setFilters(array('StringTrim', 'StringToUpper'))
                 ->setRequired(true)
                 ->addValidator('StringLength', false,
                    array('min' => 1, 'max' => 3));
      $this->addElement($flatno);
   }

}
