<?php

require_once('Lightman/User.php');

class Lightman_Controller_AuthPlugin extends Zend_Controller_Plugin_Abstract
{
   public function preDispatch(Zend_Controller_Request_Abstract $request)
   {
      $loginController = 'index';
      $loginAction     = 'login';
      
      if ($request->getControllerName() != $loginController
         && $request->getControllerName() != 'error') {
         
         $registry = Zend_Registry::getInstance();
         $front = Zend_Controller_Front::getInstance();
         $log = $front->getParam('bootstrap')->getResource('log');
          
         $log->debug('Entered preDispatch()');
         $log->debug('  Controller = ' . $request->getControllerName() . '; Action = ' . $request->getActionName());
          
         $auth = Zend_Auth::getInstance();
          
         // If user is not logged in and is not requesting login page
         // - redirect to login page:
         if (!$auth->hasIdentity())  {
             
            $log->debug('auth does NOT have an identity...sending to login');
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            $redirector->gotoSimpleAndExit($loginAction, $loginController);
         
         } else  {
            $log->debug('User DOES have an identity.');

            // Is logged in
            // Let's check the credentials:
            $identity = $auth->getIdentity();
            $acl = $registry->acl;
            $ppm_session = new Zend_Session_Namespace('ppm');
            $user = $ppm_session->user;

            $log->debug('Role: ' . $user->getRole());
            
            $isAllowed = $acl->isAllowed($user->getRole(),
               $request->getControllerName(),
               $request->getActionName());

            if (!$isAllowed) {
               $log->warn('User does not have the necessary rights - sending them to norights');
               $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
               $redirector->gotoUrlAndExit('/index/norights');
            }
         }
      }
   }
}
