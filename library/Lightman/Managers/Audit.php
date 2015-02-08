<?php

class Lightman_Managers_Audit
{
   
   private $_model;
   
   public function __construct()
   {
      require_once APPLICATION_PATH . '/models/DbTable/Audit.php';
      $this->_model = new Model_DbTable_Audit();
   }
   
   public function auditLogon($userId)
   {
      // Don't audit my logins!
      if ($userId == 1 && APPLICATION_ENVIRONMENT == 'production') {
         return;
      }
      $data = array(
         'action' => 'User login',
         'user' => $userId
      );
      $this->_model->insert($data);
   }

   public function auditAction($userId, $action, $subject = null)
   {
      // Cut the size of the subject down if necessary:
      if (strlen($subject) > 50) {
         $subject = substr($subject, 0, 47) . '...';
      }
      $data = array(
         'action' => $action,
         'user' => $userId,
         'subject' => $subject
      );
      $this->_model->insert($data);
   }

   public function fetchEntries()
    {
        $db = Zend_Registry::get('dbAdapter');
        $sql = 'SELECT DATE_FORMAT(a.date_time, "%a %e %M %T") date_time, a.action, a.subject, r.name FROM audit a, resident_list r WHERE a.user = r.id ORDER BY a.date_time DESC LIMIT 20';
        return $db->fetchAll($sql);
    }

}

?>