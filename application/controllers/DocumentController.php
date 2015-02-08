<?php

class DocumentController extends Zend_Controller_Action
{
   public function indexAction()
   {
      // Get the document types for the filter droplist:
      $cleanTypes = $this->_getTypesList();
      $this->view->documentTypes = $cleanTypes;
      
      $this->view->dateTypes = array(
         array('id' => 'all', 'name' => 'All'),
         array('id' => '3month', 'name' => 'Past 3 Months'),
         array('id' => '6month', 'name' => 'Past 6 Months'),
         array('id' => 'year', 'name' => 'Past Year')
         );
      
      // Retrieve any request parameter for filtering:
      $params = $this->getRequest()->getParams();
      
      // Set some filter defaults:
      if (empty($params['doc_type'])) {
         $params['doc_type'] = 0;
      }
      if (empty($params['doc_date'])) {
         $params['doc_date'] = '3month';
      }
      
      $this->view->form = $this->_getDocumentFilterForm();
      $this->view->form->populate($params);

      $manager = new Lightman_Managers_Document();
      $select = $manager->fetchSelect($params);
      $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
      $paginator->setItemCountPerPage(10)
         ->setCurrentPageNumber($this->_getParam('page', 1));
      $this->view->paginator = $paginator;
   }

   public function addAction()
   {
      if (!isset($this->view->form)) {
         $this->view->form = $this->_getDocumentForm();
      }

      // Get the types droplist ready:
      $cleanTypes = $this->_getTypesList();
      $this->view->documentTypes = $cleanTypes;
      $this->view->form->setAction('/document/savenew');
   }

   public function savenewAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getDocumentForm();

      // check to see if this action has been POST'ed to
      if ($this->getRequest()->isPost()) {

         // now check to see if the form submitted exists, and
         // if the values passed in are valid for this form
         if ($form->isValid($request->getPost())) {

            $front = Zend_Controller_Front::getInstance();
            $log = $front->getParam('bootstrap')->getResource('log');
            $log->debug('Preparing to FTP...');

            $values = $form->getValues();
            $log->debug('FTP Destination: ' . $_SERVER['DOCUMENT_ROOT'] . $values['doc_folder']);
            $form->filename->setDestination($_SERVER['DOCUMENT_ROOT'] . $values['doc_folder']);
            unset($values['doc_folder']);
            if (!$form->filename->receive()) {
               $log->err('Error uploading file: ' . implode("\n", $form->filename->getMessages()));
               $fs = $form->filename->getFileInfo();
               $log->debug('Entries: ' . count($fs));
            } else {
               $log->debug('File uploaded successfully.');
            }

            $user = Lightman_Managers_User::getUser();
            $log->debug('File path used to determine size: ' . basename($form->filename->getFileName()));
            $values['path'] = basename($form->filename->getFileName());
            $values['size'] = ceil(filesize($form->filename->getFileName()) / 1024);
            $log->debug('Size of file: ' . $values['size']);

            $manager = new Lightman_Managers_Document();
            $manager->add($values);

            // Audit the upload:
            $auditor = new Lightman_Managers_Audit();
            $auditor->auditAction($user->getId(), 'Add Document', $values['title']);

            // Go to the next page:
            return $this->_redirect('document');
         }

         $this->view->form = $form;
         return $this->_forward('add');
      }
   }
   
   public function deleteAction() {
      $user = Lightman_Managers_User::getUser();
      $documentId = $this->getRequest()->getParam('id');
      $documentTitle = $this->getRequest()->getParam('title');
      $manager = new Lightman_Managers_Document();
      $manager->delete($documentId);

      // Audit the delete:
      $auditor = new Lightman_Managers_Audit();
      $auditor->auditAction($user->getId(), 'Delete Document', $documentTitle);

      return $this->_redirect('document/index');
   }

   public function searchAction()
   {
      // Get the types droplist ready:
      $cleanTypes = $this->_getTypesList();
      $this->view->documentTypes = $cleanTypes;
   }

   protected function _getDocumentForm()
   {
      require_once APPLICATION_PATH . '/forms/Document.php';
      return new Form_Document();
   }

   protected function _getDocumentFilterForm()
   {
      require_once APPLICATION_PATH . '/forms/DocumentFilter.php';
      return new Form_DocumentFilter();
   }

   private function _getTypesList()
   {
      $manager = new Lightman_Managers_Document();
      return $manager->fetchTypes();
   }
   
}
