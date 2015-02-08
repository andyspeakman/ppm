<?php

class ContractorController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Contractor();
      $this->view->entries = $manager->fetchEntries();
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getContractorForm();
      }
      
      // Get the types droplist ready:
      $cleanTypes = $this->_getTypesList();
      $this->view->contractorTypes = $cleanTypes;
      $this->view->form->setAction('/contractor/savenew');
   }

   public function editAction()
   {
      // If the form is already set, then we're
      // returning due to validation:
      if (isset($this->view->form)) {

         // Get the types droplist ready:
         $cleanTypes = $this->_getTypesList();
         $this->view->contractorTypes = $cleanTypes;
         return;
      }
      
      $this->view->form = $this->_getContractorForm();
      
      // Get the types droplist ready:
      $cleanTypes = $this->_getTypesList();
      $this->view->contractorTypes = $cleanTypes;
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Contractor();
      $contractorId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($contractorId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/contractor/save');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getContractorForm();

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
            $manager = new Lightman_Managers_Contractor();
            $manager->add($values, $user->getId());

            // Audit the addition:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Contractor', $values['company_name']);

            // Go to the next page:
            return $this->_redirect('/contractor/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function saveAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getContractorForm();

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
            $manager = new Lightman_Managers_Contractor();
            $manager->update($values, $user->getId());

            // Audit the update:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Edit Contractor', $values['company_name']);

            // Go to the next page:
            return $this->_redirect('/contractor/index');
         }
         $this->view->form = $form;
         return $this->_forward('edit');
      }
   }
   
   public function deleteAction() {
      $user = Lightman_Managers_User::getUser();
      $contractorId = $this->getRequest()->getParam('id');
      $contractorName = $this->getRequest()->getParam('name');
      $manager = new Lightman_Managers_Contractor();
      $manager->delete($contractorId);

      // Audit the delete:
      $auditor = new Lightman_Managers_Audit();
      $auditor->auditAction($user->getId(), 'Delete Contractor', $contractorName);

      return $this->_redirect('contractor/index');
   }

   protected function _getContractorForm()
   {
      require_once APPLICATION_PATH . '/forms/Contractor.php';
      return new Form_Contractor();
   }
   
   private function _getTypesList()
   {
      $manager = new Lightman_Managers_Contractor();
      $contractorTypes = $manager->fetchTypes();
      $cleanTypes = array();
      $cleanTypes[0] = 'Select...';
      foreach($contractorTypes as $key => $value) {
         $cleanTypes[$value['id']] = $value['name'];
      }
      return $cleanTypes;
   }

}
