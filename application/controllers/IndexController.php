<?php

class IndexController extends Zend_Controller_Action
{
	
   const FLICKR_RESULTS = 'flickr_result_set';
   
   public function indexAction()
   {
   }

   public function caretakerAction()
   {
   }

   public function contactAction()
   {
   }

   public function historyAction()
   {
   }

   public function ppmmclAction()
   {
   }

   public function regulationsAction()
   {
   }

   public function norightsAction()
   {
   }

   public function loginAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getLoginForm();

      // check to see if this action has been POST'ed to
      if ($this->getRequest()->isPost()) {

         // now check to see if the form submitted exists, and
         // if the values passed in are valid for this form
         if ($form->isValid($request->getPost())) {

            // since we now know the form validated, we can now
            // start integrating that data sumitted via the form
            // into our model
            $values = $form->getValues();

            $bootstrap = $this->getInvokeArg('bootstrap');
            $resource = $bootstrap->getPluginResource('db');
            $log = $bootstrap->getResource('log');
            
            $authAdapter = new Zend_Auth_Adapter_DbTable($resource->getDbAdapter());
            $conf = Zend_Registry::get('config');
            $authAdapter
            ->setTableName($conf->database->authentication->table)
            ->setIdentityColumn($conf->database->authentication->identity)
            ->setCredentialColumn($conf->database->authentication->credential)
            ->setIdentity($values['email'])
            ->setCredential($values['password'])
            ;

            // Perform the authentication query, saving the result
            $result = Zend_Auth::getInstance()->authenticate($authAdapter);
            switch ($result->getCode()) {
               
               case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
               case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                  $log->err('Problem authenticating user ' . $values['email'] . ', result code = ' . $result->getCode());
                  $ele = $form->getElement('password');
                  $ele->addErrorMessage('Login incorrect - Email address or password incorrect')
                     ->markAsError();
                  $this->view->form = $form;
                  return;

               case Zend_Auth_Result::SUCCESS:
                  $user = new Lightman_User(
                     $authAdapter->getResultRowObject()->email,
                     $authAdapter->getResultRowObject()->name,
                     $authAdapter->getResultRowObject()->id,
                     $authAdapter->getResultRowObject()->role);
                   
                  $ppm_session = new Zend_Session_Namespace('ppm');
                  $ppm_session->user = $user;

                  // Audit the logon:
                  $auditor = new Lightman_Managers_Audit();
                  $auditor->auditLogon($user->getId());

                  // Go to the next page:
                  return $this->_redirect('message');
            }

            // now that we have saved our model, lets url redirect
            // to a new location
            // this is also considered a "redirect after post"
            // @see http://en.wikipedia.org/wiki/Post/Redirect/Get
            return $this->_helper->redirector('index');
         }
      }

      // assign the form to the view
      $this->view->form = $form;
   }

   public function logoffAction()
   {
      Zend_Auth::getInstance()->clearIdentity();
      Zend_Session::destroy(true);
      return $this->_helper->redirector('index');
   }
   
   public function forgottenpasswordAction()
   {
      $request = $this->getRequest();
      $form    = $this->_getForgottenPasswordForm();

      // check to see if this action has been POST'ed to
      if ($this->getRequest()->isPost()) {

         // now check to see if the form submitted exists, and
         // if the values passed in are valid for this form
         if ($form->isValid($request->getPost())) {
            
            $email = $form->getValue('email');
            $flatno = $form->getValue('flatno');
            
            // Look up the password:
            $manager = new Lightman_Managers_User();
            $password = null;
            try {
               $password = $manager->getPassword($email, $flatno);
            } catch (Lightman_Exception_UserNotFound $unf) {
               $ele = $form->getElement('email');
               $ele->addErrorMessage('We could not find an account with the given email address and flat number')
                   ->markAsError();
               $this->view->form = $form;
               return $this->render('forgottenpassword');
            }
            
            // Email the password to the user:
            $emailManager = new Lightman_Managers_Email();
            $emailManager->sendForgottenPasswordEmail($email, $password);

            // Head to the confirmation page:
            $this->_redirect('/index/forgottenpasswordsuccess');
         }
      }

      // assign the form to the view
      $this->view->form = $form;
   }
   
   public function forgottenpasswordsuccessAction()
   {
   }

   public function galleryAction()
   {
      $bootstrap = $this->getInvokeArg('bootstrap');
      $log = $bootstrap->getResource('log');

      $manager = $bootstrap->getResource('cachemanager');
      $cache = $manager->getCache('ppm');

      // Retrieve pictures from Flickr:
      if (!$pictureArray = $cache->load(self::FLICKR_RESULTS)) {
	      $conf = $bootstrap->getResource('config');
	      $flickr = new Zend_Service_Flickr($conf->flickr->key);
	      $results = $flickr->groupPoolGetPhotos("1381716@N21", array('per_page' => 50));
	      
	      // Convert the results to an array for caching:
	      $pictureArray = array();
	      foreach ($results as $pic) {
	          $pictureArray[] = Lightman_Helper_Flickr::convertFlickrPicToArray($pic);
	      }
	      $cache->save($pictureArray, self::FLICKR_RESULTS);
      }
      $this->view->flickrResults = $pictureArray;
   }

   protected function _getLoginForm()
   {
      require_once APPLICATION_PATH . '/forms/Login.php';
      return new Form_Login();
   }
   
   protected function _getForgottenPasswordForm()
   {
      require_once APPLICATION_PATH . '/forms/ForgottenPassword.php';
      return new Form_ForgottenPassword();
   } 
   
}
