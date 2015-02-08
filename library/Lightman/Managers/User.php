<?php

class Lightman_Managers_User {

   private $_db;

   public function changePassword($userId, $oldPass, $newPass)
   {
      // Check that a single row exists:
      $sql = 'SELECT id FROM resident_list WHERE id = ? AND password = ?';
      $db = $this->_getDb();
      $result = $db->fetchRow($sql, array($userId, $oldPass));

      if (count($result) == 1) {
         $data = array('password' => $newPass);
         $result = $db->update('resident_list', $data, 'id = ' . $userId);
      } else {
         throw new Lightman_Exception_BadPassword();
      }
   }

   public function getPassword($email, $flatno)
   {
      // Check that a single row exists:
      $sql = 'SELECT password FROM resident_list WHERE UPPER(email) = ? AND flat = ?';
      $db = $this->_getDb();
      $result = $db->fetchRow($sql, array($email, $flatno));
      if (count($result) == 1 && !empty($result['password'])) {
         return $result['password'];
      } else {
         throw new Lightman_Exception_UserNotFound();
      }
   }

   public function getEmail($id)
   {
      // Check that a single row exists:
      $sql = 'SELECT email FROM resident_list WHERE id = ?';
      $db = $this->_getDb();
      $result = $db->fetchRow($sql, $id);
      if (count($result) == 1 && !empty($result['email'])) {
         return $result['email'];
      } else {
         throw new Lightman_Exception_UserNotFound();
      }
   }

   public static function getUser()
   {
      $ppm_session = new Zend_Session_Namespace('ppm');
      return $ppm_session->user;
   }
   
   private function _getDb()
   {
      if (null === $this->_db) {
         $this->_db = Zend_Registry::get('dbAdapter');
      }
      return $this->_db;
   }
}

?>