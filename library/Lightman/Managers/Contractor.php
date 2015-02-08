<?php

class Lightman_Managers_Contractor
{
    
   private $_model;
    
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Contractor.php';
      $this->_model = new Model_DbTable_Contractor();
   }
   
   public function add($contractorData, $userId)
   {
      unset($contractorData['submit']);
      $contractorData['added_by'] = $userId;
      $this->_model->insert($contractorData); 
   }
    
   public function update($contractorData, $userId)
   {
      $contractorId = $contractorData['id'];
      unset($contractorData['id']);
      unset($contractorData['added_by']);
      unset($contractorData['submit']);
      $contractorData['added_by'] = $userId;
      $this->_model->update($contractorData, 'id = ' . $contractorId); 
   }
   
   public function fetch($id)
   {
      $rows = $this->_model->find($id);
      return $rows->toArray();
   }
    
   public function fetchEntries()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT c.id, ct.name type, c.company_name, c.website, c.company_telno, c.contact_name, c.contact_telno, c.notes, c.added_by, r.name 
                FROM contractor c, resident_list r, contractor_type ct
               WHERE c.added_by = r.id
                 AND c.contractor_type = ct.id
               ORDER BY ct.name ASC, c.company_name';
      return $db->fetchAll($sql);
   }

   public function fetchTypes()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT ct.id, ct.name 
                FROM contractor_type ct
               ORDER BY ct.name ASC';
      return $db->fetchAll($sql);
   }

   public function delete($id)
   {
      $this->_model->delete('id = ' . $id);
   }
}

?>