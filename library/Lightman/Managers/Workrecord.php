<?php

class Lightman_Managers_Workrecord
{

   private $_model;
    
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Workrecord.php';
      $this->_model = new Model_DbTable_Workrecord();
   }
   
   public function add($workrecordData, $userId)
   {
      unset($workrecordData['submit']);
      $workrecordData['added_by'] = $userId;
      $d = new Zend_Date($workrecordData['work_date'], 'dd/MM/YYYY');
      $workrecordData['work_date'] = $d->toString('yyyyMMdd'); 
      $this->_model->insert($workrecordData); 
   }
   
   public function fetchEntries()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT wr.id, DATE_FORMAT(wr.work_date, "%a %e %M") work_date, wr.notes, wr.hours, wr.added_by, r.name
                FROM work_record wr, resident_list r
               WHERE wr.added_by = r.id
               ORDER BY wr.work_date DESC';
      return $db->fetchAll($sql);
   }
   
   public function fetchSelect()
   {
      $db = Zend_Registry::get('dbAdapter');
      $select = $db->select();
      $select->from(array('wr' => 'work_record'),
                    array('work_date' => 'DATE_FORMAT(work_date, "%a %e %M")', 'notes', 'hours'))
             ->join(array('r' => 'resident_list'),
                        'r.id = wr.added_by',
                        array('name'))
             ->order('wr.work_date DESC');

      return $select;
   }

   public function delete($id)
   {
      $this->_model->delete('id = ' . $id);
   }

}

?>
