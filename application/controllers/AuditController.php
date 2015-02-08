<?php

class AuditController extends Zend_Controller_Action 
{

    public function indexAction()
    {
        $manager = new Lightman_Managers_Audit();
        $this->view->entries = $manager->fetchEntries();
    }

}
