<?php

class ResidentController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Resident();
      $this->view->entries = $manager->fetchEntries();
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getResidentForm();
      }
      $this->view->form->setAction('/resident/savenew');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getResidentForm();

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
            $manager = new Lightman_Managers_Resident();
            $manager->add($values, $user->getId());

            // Audit the message:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Resident', $values['name']);

            // Go to the next page:
            return $this->_redirect('resident/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function editAction()
   {
      // If the form is already set, then we're
      // returning due to validation:
      if (isset($this->view->form)) {
         return;
      }
      
      $this->view->form = $this->_getResidentForm();
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Resident();
      $residentId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($residentId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/resident/save');
   }

    public function saveAction()
    {
        $request = $this->getRequest();
        $form    = $this->_getResidentForm();

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
                $manager = new Lightman_Managers_Resident();
                $manager->update($values);

                // Audit the logon:
                $auditor = new Lightman_Managers_Audit();
                $auditor->auditAction($user->getId(), 'Edit Resident', $values['name']);

                // Go to the next page:
                return $this->_redirect('resident/index');
             }
            $this->view->form = $form;
            return $this->_forward('add');
        }
    }
   
   public function deleteAction() {
        $user = Lightman_Managers_User::getUser();
        $residentId = $this->getRequest()->getParam('id');
        $residentName = $this->getRequest()->getParam('name');
        $manager = new Lightman_Managers_Resident();
        $manager->delete($residentId);

        // Audit the delete:
        $auditor = new Lightman_Managers_Audit();
        $auditor->auditAction($user->getId(), 'Delete Resident', $residentName);

        return $this->_redirect('resident/index');
    }
   
   protected function _getResidentForm()
   {
      require_once APPLICATION_PATH . '/forms/Resident.php';
      return new Form_Resident();
   }

}
