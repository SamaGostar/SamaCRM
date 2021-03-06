<?php

class AppController extends Controller{
	var $view = 'Theme';
	var $theme = 'asrenet';
	var $components = array('Auth');
	var $helpers = array('Html', 'Form', 'Javascript');
		
	function beforeRender()
	{
		if ( $this->Auth->user() ) $this->set('user', $this->Auth->user());
		$userid = $this->Session->read('Admin.userid');
		if(!empty($userid)) $this->set('logged_as', true);
		$this->pageTitle = '';
	}
	
	function beforeFilter()
	{
		$this->Auth->allow('*');
		//$this->SavedSetting = $this->Setting->find();
	}
	
	function blockqoute($input)
	{
		return nl2br(str_replace(array('{کد}', '{/کد}'), array('<blockquote style="text-align:left; direction:ltr;"><div>', '</div></blockquote>'), $input));
	}
	
	function SendSMS(){
		if($this->setting['send_sms_client'] == 1)
		{
			$mobile = $this->Auth->user('cellnum');
			if(strlen($mobile) == 11 && substr($mobile, 0, 2) == '09')
				$this->Asresms->Send(array('cell' => $mobile, 'text' => $this->data['Ticket']['title'], 'flash' => 0));
		}
		
		if($this->setting['send_sms_admin'] == 1)
		{
			$this->Asresms->Send(array('cell' => $this->setting['mobile_number'], 'text' => 'تيکت جديدی با عنوان '. $this->data['Ticket']['title'] .' توسط '. $this->Auth->user('name') .' افتتاح گرديده است.', 'flash' => 0));
		}
	}
}
?>