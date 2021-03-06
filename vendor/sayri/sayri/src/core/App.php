<?php
namespace sayri;
/**
 * Wyjątkowo nie w namespace app by można było po prostu napisać App::coś_tam
 */
class App{
	/**
	 *
	 * @var \sayri\Db 
	 */
	static public $db;
	static public $projectDir;
	static public $appDir;
	static public $frameworkDir;
	static public $classAliases;
	static public function abort404($additonalMessage='',$viewPage='errors/404'){
		header("HTTP/1.0 404 Not Found");
		echo View::get($viewPage,['message'=>$additonalMessage]);
		die();
	}
	
	static public function run($projectDir){
		error_reporting(E_ALL|E_STRICT);
		ini_set('display_errors', 'On');
		assert_options(ASSERT_BAIL,1);
		session_start();		
		$projectDir=str_replace('\\','/',$projectDir);
		self::$projectDir=$projectDir;
		self::$appDir=$projectDir.'app/';
		self::$frameworkDir=$projectDir.'vendor/sayri/sayri/src/';
		self::$classAliases=require_once(self::$frameworkDir.'config/ClassAliases.php');		
		
		//$frameworkPath='vendor/sayri/sayri/';
		require_once(self::$frameworkDir.'core/Autoload.php');
		/*foreach(
				['core/Autoload','core/ArrayUtils','core/Db','core/Session','core/Auth','core/Users',
				'core/Config','core/Input','core/Url','core/Route','core/Request','core/ViewCompiler','core/View','core/Utils','core/Helpers'
			] as $frameworkFile){
			require_once(self::$frameworkDir.$frameworkFile.'.php');
		}
		*/
		new \sayri\Autoload();
		//class aliases, so we can simply use "App" instead of sayri\App
		foreach(self::$classAliases as $class=>$alias){
			//class_alias('sayri\Url','Url');
			class_alias($alias,$class);
		}				
		require_once(self::$appDir.'config/Routes.php');
		//testy:
		//$x=new sayri\Tests();
		//
		//App::$input=new \system\Input();
		//echo App::$input->get('blah');
		//require_once($appPath.'core/Controller.php');
		//database
		$dbConfig=\sayri\Db::getConfig('default');
		//var_dump($dbConfig);
		self::$db=new \sayri\Db($dbConfig['dsn'],$dbConfig['user'],$dbConfig['password']);

		//tests
		self::tests();
		//
		//load the controller
		$controllerClass=Request::getController();
		//$controllerFile=self::$appDir.'controllers/'.$controllerClass.'.php';
		$controllerFile=Request::getControllerPath();
		if(file_exists($controllerFile)){
			require_once($controllerFile);
			$controller=new $controllerClass();
		}
		$controllerMethod=Request::getMethod();
		if(empty($controller) || !method_exists($controller,$controllerMethod)){
			static::abort404('Controller: '.$controllerClass.' method: '.$controllerMethod);
		}

		
		$result=call_user_func_array(array($controller, $controllerMethod), Request::getParameters());		
		if(is_a($result,'sayri\ViewBase'))
			echo $result->getAsString();
		elseif (is_string($result)){
			echo $result;
		}
	}
	
	
	public static function tests(){
		return;
		$user=new Users();
		$user->load(['users.id'=>1]);
		if($user->is('admin'))
			echo 'is admin';
		else
			echo 'is not admin';
	}
}
