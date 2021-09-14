<?php

class Lightman_Managers_Task
{
   private $_model;
    
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Task.php';
      $this->_model = new Model_DbTable_Task();
   }
   
   public function add($taskData, $userId)
   {
      $this->_cleanEmptyData($taskData);
      $taskData['added_by'] = $userId;
      
      if (!empty($taskData['date_requested'])) {
         $d = new Zend_Date($taskData['date_requested'], 'dd/MM/yyyy');
         $taskData['date_requested'] = $d->toString('yyyyMMdd');
      }
      if (!empty($taskData['date_completed'])) {
         $d = new Zend_Date($taskData['date_completed'], 'dd/MM/yyyy');
         $taskData['date_completed'] = $d->toString('yyyyMMdd');
      }
      $this->_model->insert($taskData);
   }
   
   public function fetchSelect()
   {
      $db = Zend_Registry::get('dbAdapter');
      $select = $db->select();
      $select->from(array('t' => 'task'),
                    array('id', 'priority', 'title', 'notes',
                    'date_added' => 'DATE_FORMAT(date_added, "%D %b %Y")',
                    'requested_by',
                    'age' => 'DATEDIFF(CURDATE(), date_requested)'
                    ))
             ->join(array('r' => 'resident_list'),
                    'r.id = t.assigned_to',
                    array('name'))
             ->order('priority ASC');
      
      return $select;
   }

   public function fetchEntries($parameters)
   {
      $db = Zend_Registry::get('dbAdapter');
      $select = $this->fetchSelect($parameters);
      $stmt = $db->query($select);
      return $stmt->fetchAll();
   }

   public function fetch($id)
   {
      $rows = $this->_model->find($id);
      return $rows->toArray();
   }
    
   public function update($taskData)
   {
      $this->_cleanEmptyData($taskData);
      $taskId = $taskData['id'];
      unset($taskData['added_by']);
      
      if (!empty($taskData['date_requested'])) {
         $d = new Zend_Date($taskData['date_requested'], 'dd/MM/YYYY');
         $taskData['date_requested'] = $d->toString('YYYYMMdd'); 
      }
      if (!empty($taskData['date_completed'])) {
         $d = new Zend_Date($taskData['date_completed'], 'dd/MM/YYYY');
         $taskData['date_completed'] = $d->toString('YYYYMMdd'); 
      }
      $this->_model->update($taskData, 'id = ' . $taskId);
   }
   
   public function delete($id)
   {
      $this->_model->delete('id = ' . $id);
   }
   
   public function fetchAssignees()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT r.id, r.name 
                FROM resident_list r
               WHERE r.role IN ("director","caretaker")
                 AND r.name NOT LIKE "Test%"
               ORDER BY r.role, r.name ASC';
      return $db->fetchAll($sql);
   }

   private function _cleanEmptyData(&$taskData)
   {
      unset($taskData['submit']);
      if (empty($taskData['effort']) || strlen($taskData['effort'] == 0)) {
         unset($taskData['effort']);
      }
      if (empty($taskData['cost']) || strlen($taskData['cost'] == 0)) {
         unset($taskData['cost']);
      }
      
      if (empty($taskData['date_completed']) || strlen($taskData['date_completed'] == 0)) {
         unset($taskData['date_completed']);
      }
   }

}

?>
