<?php
class UsersController extends app\core\FrontController{
	
	/**
	 * Logowanie, widok i realizacja
	 */
	public function actionLogin(){
		$musers=new app\models\UsersModel();
		//echo \Config::get('test');
		if(Input::post('submit')!=''){
			if(User::login(Input::post('login'),Input::post('password'))){
				//$this->message('Logowanie powiodło się');
				Url::redirect('homepage/index');
			}else{
				$this->error('Nieprawidłowy login lub hasło');
			};
		}
		$this->render('users/login');
	}
	
	/**
	 * Wylogowanie plus przekierowanie na stronę główną
	 */
	public function actionLogout(){
		\User::logout();
		Url::redirect('');
	}
	
	/**
	 * To tylko testy
	 */
	public function actionTests(){
		\User::login('user1','user1');
		\User::logout();
		echo 'curUser:';var_dump(\User::cur());echo '<br>';
		\App::$db->insert('users',['firstName'=>'Konrad2','age'=>10]);
		$test=\App::$db->get('users',array('id'=>1))->resultOne();
		echo 'user1:';var_dump($test);echo '<br>';
		\App::$db->where(['firstName'=>'Konrad'])->update('users',['firstName'=>'Angelika'],['id'=>4]);
		\App::$db->where(['id'=>'7'])->delete('users');
		//\App
		//var_dump($test);
		
	}
}