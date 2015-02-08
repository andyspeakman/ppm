<?php

class MessageController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Message();
      $this->view->entries = $manager->fetchEntries();
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getMessageForm();
      }
      $this->view->form->setAction('/message/savenew');
   }

   public function editAction()
   {
      // If the form is already set, then we're
      // returning due to validation:
      if (isset($this->view->form)) {
         return;
      }
      
      $this->view->form = $this->_getMessageForm();
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Message();
      $messageId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($messageId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/message/save');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getMessageForm();

      // check to see if this action has been POST'ed to
      if ($this->getRequest()->isPost()) {

         // now check to see if the form submitted exists, and
         // if the values passed in are valid for this form
         if ($form->isValid($request->getPost())) {

            // since we now know the form validated, we can now
            // start integrating that data sumitted via the form
            // into our model
            $user = Lightman_Managers_User::getUser();
            $values = $form->getValues();
            $manager = new Lightman_Managers_Message();
            $manager->add($values, $user->getId());

            // Email the message to residents:
            $emailManager = new Lightman_Managers_Email();
            $emailManager->sendMessageEmail($values['title'], $values['notes']);

            // Audit the message:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Message', $values['title']);

            // Go to the next page:
            return $this->_redirect('message/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function saveAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getMessageForm();

      // check to see if this action has been POST'ed to
      if ($this->getRequest()->isPost()) {

         // now check to see if the form submitted exists, and
         // if the values passed in are valid for this form
         if ($form->isValid($request->getPost())) {

            // since we now know the form validated, we can now
            // start integrating that data sumitted via the form
            // into our model
            $user = Lightman_Managers_User::getUser();
            $values = $form->getValues();
            $manager = new Lightman_Managers_Message();
            $manager->update($values);

            // Audit the logon:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Edit Message', $values['title']);

            // Go to the next page:
            return $this->_redirect('message/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function expireAction() {
      $user = Lightman_Managers_User::getUser();
      $messageId = $this->getRequest()->getParam('id');
      $manager = new Lightman_Managers_Message();
      $manager->expire($messageId);

      // Audit the expire:
      $auditor = new Lightman_Managers_Audit();
      $auditor->auditAction($user->getId(), 'Expire Message');
      
      return $this->_redirect('message/index');
   }

   protected function _getMessageForm()
   {
      require_once APPLICATION_PATH . '/forms/Message.php';
      return new Form_Message();
   }

}
