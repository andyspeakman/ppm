<?php

class SecureController extends Zend_Controller_Action
{

   public function noticeboardAction()
   {
   }

   public function contractorsAction()
   {
   }

   public function changepasswordsuccessAction()
   {
   }

   public function insuranceAction()
   {
   }

   public function searchAction()
   {
   }

   public function changepasswordAction()
   {
      $this->view->form = $this->_getChangePasswordForm();
      $this->render();
   }

   public function submitpasswordAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getChangePasswordForm();

      // check to see if this action has been POST'ed to
      if (!$this->getRequest()->isPost()) {
         return $this->_forward('changepassword');
      }
       
      // now check to see if the form submitted exists, and
      // if the values passed in are valid for this form:
      if (!$form->isValid($request->getPost())) {
         $this->view->form = $form;
         return $this->render('changepassword');
      }

      // Check that the two new password values match:
      $values = $form->getValues();
      if ($values['newPasswordOne'] != $values['newPasswordTwo']) {
         $ele = $form->getElement('newPasswordTwo');
         $ele->addErrorMessage('Value for New Password and Repeat New Password must match')
             ->markAsError();
         
         $this->view->form = $form;
         return $this->render('changepassword');
      }

      // Process all is well:
      $auditer = new Lightman_Managers_Audit();
      $user = Lightman_Managers_User::getUser();
      $manager = new Lightman_Managers_User();
      try {
         $manager->changePassword($user->getId(), $values['currentPassword'], $values['newPasswordTwo']);
      } catch (Lightman_Exception_BadPassword $e) {
         $ele = $form->getElement('newPasswordTwo');
         $ele->addErrorMessage('Incorrect value for Old Password')
             ->markAsError();
         
         $this->view->form = $form;
         return $this->render('changepassword');
      }
      
      $auditer->auditAction($user->getId(), 'Change password');
      $this->_redirect('/secure/changepasswordsuccess');
   }

   protected function _getChangePasswordForm()
   {
      require_once APPLICATION_PATH . '/forms/ChangePassword.php';
      $form = new Form_ChangePassword();
      return $form;
   }
   
}
