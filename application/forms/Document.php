<?php

class Form_Document extends Zend_Form
{
   public function init()
   {
      // set the method for the display form to POST
      $this->setMethod('post')
           ->setAction('save');

      $documentId = $this->createElement('hidden', 'id');
      $this->addElement($documentId);
      
      $docFolder = $this->createElement('hidden', 'doc_folder');
      $this->addElement($docFolder);
      
      // Document type:
      $typeList = $this->createElement('select', 'doc_type');
      $typeList->setLabel('Type:')
               ->setDescription('Type of document - those with an asterisk can be viewed by Shareholders.')
               ->setRegisterInArrayValidator(false)
               ->addValidator('GreaterThan', false, array('min' => 0, 'messages' =>
                  array(Zend_Validate_GreaterThan::NOT_GREATER => 'Please select a Type')));
      $this->addElement($typeList);
      
      // Title:
      $title = $this->createElement('text', 'title');
      $title->setRequired(true)
            ->setLabel('Title:')
            ->setDescription('Title of the document, up to 100 chars.')
            ->setFilters(array('StringTrim'))
            ->addValidator('StringLength', false,
               array('min' => 10, 'max' => 100));
      $this->addElement($title);

      // File:
      $filename = $this->createElement('file', 'filename');
      $filename->setRequired(true)
           ->setLabel('File:')
           ->setDescription('File to be uploaded - PDF format, 5MB max.')
           ->setFilters(array('StringTrim'))
           ->setValueDisabled(true)
           ->addValidator('Extension', false,
               array('extension' => 'pdf', 'messages' =>
                  array(Zend_Validate_File_Extension::FALSE_EXTENSION => 'Files must be in PDF format')));
      
      // Allow admins to upload larger files:
      $registry = Zend_Registry::getInstance();
      $acl = $registry->acl;
      $user = Lightman_Managers_User::getUser();
      if ($acl->isAllowed($user->getRole(), 'document', 'largefiles')) {
         $filename->addValidator('Size', false, 10245760);
      } else {
         $filename->addValidator('Size', false, 5242880);
      }
      $this->addElement($filename);

      // Date:
      $expiryDate = $this->createElement('text', 'doc_date');
      $expiryDate->setLabel('Document Date:')
                 ->setDescription('Date the document was authored. Should be in format DD/MM/YYYY.')
                 ->setFilters(array('StringTrim'))
                 ->setRequired(true)
                 ->addValidator('Date', false,
                    array('format' => 'dd/MM/yyyy', 'messages' =>
                       array(Zend_Validate_Date::FALSEFORMAT => 'Not a valid date - should be DD/MM/YYYY')));
      $this->addElement($expiryDate);
      
      $this->setAttrib('enctype', 'multipart/form-data');
   }
}
