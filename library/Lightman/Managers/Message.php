<?php

class Lightman_Managers_Message
{
   private $_model;
    
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Message.php';
      $this->_model = new Model_DbTable_Message();
   }
   
   public function add($messageData, $userId)
   {
      $this->_cleanEmptyData($messageData);
      $messageData['added_by'] = $userId;
      if (!empty($messageData['date_expires'])) {
         $d = new Zend_Date($messageData['date_expires'], 'dd/MM/YYYY');
         $messageData['date_expires'] = $d->toString('yyyyMMdd'); 
      }
      $this->_model->insert($messageData); 
   }
   
   public function expire($messageId) {
      $data = array(
         'expired'      => 'Y'
      );
      $where = 'id = ' . $messageId;
      $this->_model->update($data, $where);
   }
   
   public function update($messageData)
   {
      $this->_cleanEmptyData($messageData);
      $messageId = $messageData['id'];
      unset($messageData['added_by']);
      if (!empty($messageData['date_expires'])) {
         $d = new Zend_Date($messageData['date_expires'], 'dd/MM/YYYY');
         $messageData['date_expires'] = $d->toString('YYYYMMdd'); 
      }
      $this->_model->update($messageData, 'id = ' . $messageId);
   }
   
   public function fetch($id)
   {
      $rows = $this->_model->find($id);
      $data = $rows->toArray();
      return $data;
   }
    
   public function fetchEntries()
   {
      $db = Zend_Registry::get('dbAdapter');
      $sql = 'SELECT m.id, DATE_FORMAT(m.date_added, "%a %e %M") date_added, m.title, m.notes, m.added_by, r.name
                FROM message m, resident_list r
               WHERE m.added_by = r.id
                 AND m.expired = "N"
                 AND (m.date_expires = 0 OR m.date_expires IS NULL OR m.date_expires > CURDATE())
               ORDER BY m.date_added DESC';
      return $db->fetchAll($sql);
   }

   private function _cleanEmptyData(&$messageData)
   {
      unset($messageData['submit']);
      if (empty($messageData['date_expires']) || strlen($messageData['date_expires'] == 0)) {
         unset($messageData['date_expires']);
      }
   }

}

?>
