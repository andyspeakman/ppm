<?php

require_once APPLICATION_PATH . '/models/DbTable/Car.php';

class Lightman_Managers_Car
{
    
    public function fetchAll()
    {
        $table = new Model_DbTable_Car();
        $select = $table->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        $select->setIntegrityCheck(false)
            ->join('flat', 'flat.id = car_list.flat', 'display_order')
            ->order('flat.display_order');
        return $table->fetchAll($select);
    }
    
    public function add($carData)
    {
        $table = new Model_DbTable_Car();
        $table->insert($carData);
    }
   
    public function delete($id)
    {
        $table = new Model_DbTable_Car();
        return $table->delete('id = ' . $id);
    }
    
    public function fetch($id)
    {
        $table = new Model_DbTable_Car();
        $rows = $table->find($id);
        return $rows->toArray();
    }

   public function update($carData)
   {
      $carId = $carData['id'];
      unset($carData['id']);
      unset($carData['submit']);
      $table = new Model_DbTable_Car();
      $table->update($carData, 'id = ' . $carId); 
   }

}

?>
