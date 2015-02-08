<?php

class TaskController extends Zend_Controller_Action
{
   public function indexAction()
   {
      $manager = new Lightman_Managers_Task();
      $select = $manager->fetchSelect();
      $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
      $paginator->setItemCountPerPage(10)
         ->setCurrentPageNumber($this->_getParam('page', 1));
      $this->view->paginator = $paginator;
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getTaskForm();
      }
      // Get the assignees droplist ready:
      $assignees = $this->_getAssigneesList();
      $this->view->assignees = $assignees;
      $this->view->form->setAction('/task/savenew');
   }

   public function editAction()
   {
      // If the form is already set, then we're
      // returning due to validation:
      if (isset($this->view->form)) {
         return;
      }
      
      $this->view->form = $this->_getTaskForm();
      
      // Get the assignees droplist ready:
      $assignees = $this->_getAssigneesList();
      $this->view->assignees = $assignees;
      
      // Retrieve the record for editing:
      $manager = new Lightman_Managers_Task();
      $taskId = $this->getRequest()->getParam('id');
      $data = $manager->fetch($taskId);
      $this->view->form->populate($data[0]);
      $this->view->form->setAction('/task/save');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getTaskForm();

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
         
            $manager = new Lightman_Managers_Task();
            $manager->add($values, $user->getId());

            // Email the task to assignee:
            // Look up the email address:
            $userManager = new Lightman_Managers_User();
            $emailAddress = $userManager->getEmail($values['assigned_to']);
            $emailManager = new Lightman_Managers_Email();
            $emailManager->sendMessageEmail('Task Assignment', 'You have been assigned a task on the Princes Park Mansions website. The title is: ' . $values['title'], $emailAddress);
            
            // Audit the task creation:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Task', $values['title']);

            // Go to the next page:
            return $this->_redirect('task/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function saveAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getTaskForm();

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
            $manager = new Lightman_Managers_Task();
            $manager->update($values);

            // Audit the edit:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Edit Task', $values['notes']);

            // Go to the next page:
            return $this->_redirect('task/index');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }

   public function deleteAction() {
      $user = Lightman_Managers_User::getUser();
      $recordId = $this->getRequest()->getParam('id');
      $manager = new Lightman_Managers_Task();
      $manager->delete($recordId);

      // Audit the delete:
      $auditor = new Lightman_Managers_Audit();
      $auditor->auditAction($user->getId(), 'Delete Task');

      return $this->_redirect('task/index');
   }

   protected function _getTaskForm()
   {
      require_once APPLICATION_PATH . '/forms/Task.php';
      return new Form_Task();
   }

   private function _getAssigneesList()
   {
      $manager = new Lightman_Managers_Task();
      return $manager->fetchAssignees();
   }

}
