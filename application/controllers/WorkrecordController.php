<?php

class WorkrecordController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Workrecord();
      $select = $manager->fetchSelect();
      $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
      $paginator->setItemCountPerPage(10)
         ->setCurrentPageNumber($this->_getParam('page', 1));
      $this->view->paginator = $paginator;
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getWorkrecordForm();
      }
      $this->view->form->setAction('/workrecord/savenew');
   }

   public function editAction()
   {
      // If the form is already set, then we're
      // returning due to validation:
      if (isset($this->view->form)) {
         return;
      }
      
      $this->view->form = $this->_getWorkrecordForm();
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Workrecord();
      $messageId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($messageId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/workrecord/save');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getWorkrecordForm();

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
            $manager = new Lightman_Managers_Workrecord();
            $manager->add($values, $user->getId());

            // Audit the logon:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Work Record', $values['notes']);

            // Go to the next page:
            return $this->_redirect('workrecord/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function saveAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getWorkrecordForm();

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
            $manager = new Lightman_Managers_Workrecord();
            $manager->update($values);

            // Audit the edit:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Edit Work Record', $values['notes']);

            // Go to the next page:
            return $this->_redirect('workrecord/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }

   public function deleteAction() {
      $user = Lightman_Managers_User::getUser();
      $recordId = $this->getRequest()->getParam('id');
      $manager = new Lightman_Managers_Workrecord();
      $manager->delete($recordId);

      // Audit the delete:
      $auditor = new Lightman_Managers_Audit();
      $auditor->auditAction($user->getId(), 'Delete Work Record');

      return $this->_redirect('workrecord/index');
   }

   protected function _getWorkrecordForm()
   {
      require_once APPLICATION_PATH . '/forms/Workrecord.php';
      return new Form_Workrecord();
   }

}
