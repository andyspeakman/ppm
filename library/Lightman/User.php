<?php
class Lightman_User {
   
   private $email;
   private $name;
   private $id;
   private $role;
   
   public function __construct($email, $name, $id, $role) {
      $this->email = $email;
      $this->name = $name;
      $this->id = $id;
      $this->role = $role;
   }
   
   public function getEmail() {
      return $this->email;
   }

   public function getName() {
      return $this->name;
   }

   public function getId() {
      return $this->id;
   }
   
   public function getRole() {
      return $this->role;
   }
   
}
?>