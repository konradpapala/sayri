<?php
namespace app\core;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseController extends \sayri\BaseController{
	public function __construct(){
		parent::__construct();
	}

	protected function setAccess(){
		parent::setAccess();
	}
}