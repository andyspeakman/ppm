<?php

class Lightman_Managers_Email {

	private $_transport;
	 
	public function sendForgottenPasswordEmail($email, $password)
	{
		$mail = new Zend_Mail();
		$mail->setFrom('princesparkmansions@yahoo.co.uk', 'Princes Park Mansions');
		$mail->addTo($email);
		$mail->setSubject('Forgotten Password');
		$mail->setBodyText('Your password for the Princes Park Mansions website is: ' . $password);
		$mail->send($this->_getTransport());
	}
	 
	public function sendMessageEmail($title, $body, $to = NULL)
	{
		$front = Zend_Controller_Front::getInstance();
		$log = $front->getParam('bootstrap')->getResource('log');
		$log->debug('Message being sent to: ' . $to);

		$mail = new Zend_Mail();
		$mail->setFrom('info@princes-park-mansions.org.uk', 'Princes Park Mansions');

		if (is_null($to)) {
			if (APPLICATION_ENVIRONMENT != 'production') {
				$mail->addTo('andy.speakman@gmail.com');
			} else {
				foreach ($this->_getEmailAddresses() as $add) {
					$mail->addBcc($add['email']);
				}
			}
		} else {
			if (APPLICATION_ENVIRONMENT != 'production') {
				$mail->addTo('andy.speakman@gmail.com');
			} else {
				$mail->addTo($to);
			}
		}

		$mail->setSubject('PPM - ' . $title);
		$mail->setBodyText($body);
		$mail->send($this->_getTransport());
	}
	 
	private function _getEmailAddresses()
	{
		$db = Zend_Registry::get('dbAdapter');
		$sql = 'SELECT r.email
                FROM resident_list r
               WHERE r.e_contact = "Y"';
		return $db->fetchAll($sql);
	}
	 
	private function _getTransport()
	{
		if (null === $this->_transport) {
			$conf = Zend_Registry::getInstance()->config;
			$mailuser = $conf->smtp->user;
			$mailpass = $conf->smtp->pass;
			$mailserver = $conf->smtp->server;

			$mailconfig = array('auth' => 'login',
                            'username' => $mailuser,
                            'password' => $mailpass);

			$this->_transport = new Zend_Mail_Transport_Smtp($mailserver, $mailconfig);
		}
		return $this->_transport;
	}
}

?>