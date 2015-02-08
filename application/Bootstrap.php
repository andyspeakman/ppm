<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initConfig()
    {
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/config/config.xml', APPLICATION_ENVIRONMENT);
        Zend_Registry::set('config', $config);
        return $config;
    }
    
    protected function _initAcl()
    {
         $acl = new Zend_Acl();
         
         $acl->addRole(new Zend_Acl_Role('shareholder'));
         $acl->addRole(new Zend_Acl_Role('director'), 'shareholder');
         $acl->addRole(new Zend_Acl_Role('caretaker'), 'shareholder');
         $acl->addRole(new Zend_Acl_Role('admin'));
         
         $acl->addResource(new Zend_Acl_Resource('index'));
         $acl->addResource(new Zend_Acl_Resource('secure'));
         $acl->addResource(new Zend_Acl_Resource('audit'));
         $acl->addResource(new Zend_Acl_Resource('contractor'));
         $acl->addResource(new Zend_Acl_Resource('message'));
         $acl->addResource(new Zend_Acl_Resource('document'));
         $acl->addResource(new Zend_Acl_Resource('resident'));
         $acl->addResource(new Zend_Acl_Resource('workrecord'));
         $acl->addResource(new Zend_Acl_Resource('task'));
         $acl->addResource(new Zend_Acl_Resource('car'));
         
         $acl->allow('shareholder', 'index');
         $acl->allow('shareholder', 'secure');
         $acl->allow('shareholder', 'contractor', 'index');
         $acl->allow('shareholder', 'message', 'index');
         $acl->allow('shareholder', 'document', 'index');
         $acl->allow('shareholder', 'resident', 'index');
         $acl->allow('shareholder', 'task', 'index');
         $acl->allow('shareholder', 'car', 'index');
         
         $acl->allow('caretaker', 'resident', 'listall');
         $acl->allow('caretaker', 'workrecord');
         $acl->allow('caretaker', 'car');
         $acl->deny('caretaker', 'document');
         
         $acl->allow('director', 'car');
         $acl->allow('director', 'secure');
         $acl->allow('director', 'contractor');
         $acl->allow('director', 'message');
         $acl->allow('director', 'document');
         $acl->deny('director', 'document', 'largefiles');
         $acl->allow('director', 'resident');
         $acl->allow('director', 'workrecord', 'index');
         $acl->allow('director', 'task');
         $acl->allow('director', 'audit');
         
         $acl->allow('admin');
         
         Zend_Registry::set('acl', $acl);
         return $acl;
    }
    
    protected function _initDbAdapter()
    {
        $dbAdapter = Zend_Db::factory($this->config->database);
        Zend_Db_Table::setDefaultAdapter($dbAdapter);
        Zend_Registry::set('dbAdapter', $dbAdapter);
        return $dbAdapter;
    }

}
