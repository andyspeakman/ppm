<?php

class Lightman_Managers_Document
{
   private $_model;
   private $_groups;
    
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Document.php';
      $this->_model = new Model_DbTable_Document();
      $user = Lightman_Managers_User::getUser();
      $this->_groups = '%' . $user->getRole() . '%';
   }
   
   public function add($documentData)
   {
      $this->_cleanEmptyData($documentData);
      if (!empty($documentData['doc_date'])) {
         $d = new Zend_Date($documentData['doc_date'], 'dd/MM/yyyy');
         $documentData['doc_date'] = $d->toString('yyyyMMdd');
      }
      $this->_model->insert($documentData); 
   }
   
   public function fetchSelect($parameters)
   {
      $db = Zend_Registry::get('dbAdapter');
      $select = $db->select();
      $select->from(array('d' => 'document'),
                    array('id', 'dated' => 'DATE_FORMAT(d.doc_date, "%D %b %Y")', 'title', 'path', 'size'))
             ->join(array('dt' => 'document_type'),
                        'd.doc_type = dt.id',
                        array('name', 'folder'))
             ->order('d.doc_date DESC')
             ->where('dt.groups LIKE \'' . $this->_groups . '\'');
      
      // Apply the filters:
      if ($parameters['doc_type'] != 0) {
         $select->where('dt.id = ?', $parameters['doc_type']);
      }
      switch($parameters['doc_date']) {
         case '3month':
            $select->where('d.doc_date > DATE_SUB(NOW(), INTERVAL 3 MONTH)');
            break;
         case '6month':
            $select->where('d.doc_date > DATE_SUB(NOW(), INTERVAL 6 MONTH)');
            break;
         case 'year':
            $select->where('d.doc_date > DATE_SUB(NOW(), INTERVAL 1 YEAR)');
            break;
         default:
            break;
      }
      if ($parameters['doc_title'] != '') {
         $select->where('d.title LIKE ?', '%' . $parameters['doc_title'] . '%');
      }
      return $select;
   }

   public function fetchEntries($parameters)
   {
      $db = Zend_Registry::get('dbAdapter');
      $select = $this->fetchSelect($parameters);
      $stmt = $db->query($select);
      return $stmt->fetchAll();
   }

   public function fetchTypes()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT dt.id, dt.name, dt.folder, INSTR(dt.groups, ?) flag
                FROM document_type dt
               WHERE dt.groups LIKE ?
               ORDER BY dt.name ASC';
      return $db->fetchAll($sql, array('shareholder', $this->_groups));
   }

   public function delete($id)
   {
      $this->_model->delete('id = ' . $id);
   }

   private function _cleanEmptyData(&$docData)
   {
      unset($docData['submit']);
      unset($docData['filename']);
      if (empty($docData['doc_date']) || strlen($docData['doc_date'] == 0)) {
         unset($docData['doc_date']);
      }
   }

}

?>
