<?php

class Lightman_Validator_Phone extends Zend_Validate_Regex
{
   const NOT_MATCH = 'regexNotMatch';

   protected $_messageTemplates = array(
      self::NOT_MATCH => "'%value%' is not a valid telephone number - must be between 10 and 20 valid chars"
   );

   public function __construct()
   {
      $this->setPattern('/^[0-9\-\(\) ]{10,20}$/');
   }

}

?>