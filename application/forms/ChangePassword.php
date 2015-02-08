<?php

class Form_ChangePassword extends Zend_Form
{

   public function init()
   {
      $this->setMethod('post')
         ->setAction('submitpassword');

      $this->addElement('password', 'currentPassword', array(
            'label'      => 'Old Password:',
            'required'   => true,
            'validators' => array(
            array('validator' => 'StringLength', 'options' => array(0, 20))
            )
      ));

      $this->addElement('password', 'newPasswordOne', array(
            'label'      => 'New Password:',
            'required'   => true,
            'validators' => array(
            array('validator' => 'StringLength', 'options' => array(6, 20))
            )
      ));

      $this->addElement('password', 'newPasswordTwo', array(
            'label'      => 'Repeat New Password:',
            'required'   => true,
            'validators' => array(
            array('validator' => 'StringLength', 'options' => array(6, 20))
            )
      ));
      
      // add the submit button
      $this->addElement('submit', 'submit', array(
            'label'    => 'Change Password',
      ));
   }
}
