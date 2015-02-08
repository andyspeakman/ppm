<?php

class CarController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Car();
      $this->view->entries = $manager->fetchAll();
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getCarForm();
      }
      $this->view->form->setAction('/car/savenew');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getCarForm();

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
            $manager = new Lightman_Managers_Car();
            $manager->add($values, $user->getId());

            // Audit the message:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Car', $values['registration']);

            // Go to the next page:
            return $this->_redirect('car/index');
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
      
      $this->view->form = $this->_getCarForm();
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Car();
      $carId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($carId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/car/save');
   }

    public function saveAction()
    {
        $request = $this->getRequest();
        $form    = $this->_getCarForm();

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
                $manager = new Lightman_Managers_Car();
                $manager->update($values);

                // Audit the edit:
                $auditor = new Lightman_Managers_Audit();
                $auditor->auditAction($user->getId(), 'Edit Car', $values['registration']);

                // Go to the next page:
                return $this->_redirect('car/index');
             }
            $this->view->form = $form;
            return $this->_forward('add');
        }
    }
   
   public function deleteAction() {
        $user = Lightman_Managers_User::getUser();
        $carId = $this->getRequest()->getParam('id');
        $carReg = $this->getRequest()->getParam('registration');
        $manager = new Lightman_Managers_Car();
        $manager->delete($carId);

        // Audit the delete:
        $auditor = new Lightman_Managers_Audit();
        $auditor->auditAction($user->getId(), 'Delete Car', $carReg);

        return $this->_redirect('car/index');
    }
   
   protected function _getCarForm()
   {
      require_once APPLICATION_PATH . '/forms/Car.php';
      return new Form_Car();
   }

}
