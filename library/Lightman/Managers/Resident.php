<?php

class Lightman_Managers_Resident
{
    
    private $_model;
    
    public function __construct()
    {
        require_once APPLICATION_PATH . '/models/DbTable/Resident.php';
        $this->_model = new Model_DbTable_Resident();
    }
   
    public function fetchEntries()
    {
      $db = Zend_Registry::get('dbAdapter');
      
      $registry = Zend_Registry::getInstance();
      $acl = $registry->acl;
      $user = Lightman_Managers_User::getUser();
      
      // Use a different query depending on the user rights:
      if ($acl->isAllowed($user->getRole(), 'resident', 'listall')) {
         $select = $db->select();
         $select->from(array('f' => 'flat'),
                       array('flatno' => 'id'))
                ->joinLeft(array('r' => 'resident_list'),
                           'f.id = r.flat',
                           array('resident_name' => 'name', 'role', 'email', 'e_contact', 'resident_id' => 'id'))
                ->joinLeft(array('rs' => 'resident_status'),
                           'r.status = rs.id',
                           array('resident_status' => 'name'))
                ->where('f.id < 99')
                ->order('f.display_order ASC');
         $stmt = $db->query($select);
         return $stmt->fetchAll();
         
      } else {
         
         $sql = 'SELECT f.id AS flatno, rs.name AS resident_status, r.role, r.name AS resident_name, r.email, r.e_contact
                   FROM flat f
                   LEFT JOIN resident_list r ON f.id = r.flat
                   LEFT JOIN resident_status rs ON r.status = rs.id
                  WHERE r.flat IN (SELECT fr2.flat FROM resident_list fr2 WHERE fr2.id = ?)';
          return $db->fetchAll($sql, $user->getId());
      }
   }

    public function add($residentData)
    {
        $this->_model->insert($residentData);
    }
   
    public function delete($id)
    {
        $this->_model->delete('id = ' . $id);
    }
    
    public function fetch($id)
    {
        $rows = $this->_model->find($id);
        return $rows->toArray();
    }

   public function update($residentData)
   {
      $residentId = $residentData['id'];
      unset($residentData['id']);
      unset($residentData['submit']);
      $this->_model->update($residentData, 'id = ' . $residentId); 
   }

}

?>
