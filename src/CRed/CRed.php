<?php
/**
* Main class for Red, holds everything.
*
* @package RedCore
*/
class CRed implements ISingleton 
{

   private static $instance = null;

   
   /**
    * Constructor
    */
   protected function __construct() 
   {
      // include the site specific config.php and create a ref to $ly to be used by config.php
      $RED = &$this;
      require(RED_SITE_PATH.'/config.php');
      
     // Start a named session
      session_name($this->config['session_name']);
      session_start();
      $this->session = new CSession($this->config['session_key']);
      $this->session->PopulateFromSession();
      
      // Create a database object.
      if(isset($this->config['database'][0]['dsn'])) 
      {
      	      $this->db = new CMDatabase($this->config['database'][0]['dsn']);
      }
      
      // Create a container for all views and theme data
      $this->views = new CViewContainer();
   }
   
  /**
  * Singleton pattern. Get the instance of the latest created object or create a new one. 
  * @return CLydia The instance of this class.
  */
   public static function Instance() 
   {
      if(self::$instance == null) {
         self::$instance = new CRed();
      }
      return self::$instance;
   }
   
  /**
  * Frontcontroller, check url and route to controllers.
  */
  public function FrontControllerRoute() 
  {
	    // Step 1
	    // Take current url and divide it in controller, method and parameters
	    $this->request = new CRequest();
	    $this->request->Init($this->config['base_url']);
	    $controller = $this->request->controller;
	    $method     = $this->request->method;
	    $arguments  = $this->request->arguments;
	    $formattedMethod = str_replace(array('_', '-'), '', $method);
	    
	    // Is the controller enabled in config.php?
	    $controllerExists    = isset($this->config['controllers'][$controller]);
	    $controllerEnabled    = false;
	    $className             = false;
	    $classExists           = false;
	
	    if($controllerExists) 
	    {
	      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] == true);
	      $className               = $this->config['controllers'][$controller]['class'];
	      $classExists           = class_exists($className);
	    }
	    
	    // Step 2
	    // Check if there is a callable method in the controller class, if then call it
	    if($controllerExists && $controllerEnabled && $classExists) 
	    {
		      $rc = new ReflectionClass($className);
		      if($rc->implementsInterface('IController')) 
		      {
			      if($rc->hasMethod($formattedMethod)) 
			      {
				      $controllerObj = $rc->newInstance();
				      $methodObj = $rc->getMethod($formattedMethod);
				      $methodObj->invokeArgs($controllerObj, $arguments);
			      } 
			      else 
			      {
				      die("404. " . get_class() . ' error: Controller does not contain method.');
			      }
		      } 
		      else 
		      {
			die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
		      }
	    } 
	    else
	    { 
		    die('404. Page is not found.');
	    }
	    
  }
  
  /**
  * Theme Engine Render, renders the views using the selected theme.
  */
  public function ThemeEngineRender() 
  {
	    // Get the paths and settings for the theme
	    $themeName    = $this->config['theme']['name'];
	    $themePath    = RED_INSTALL_PATH . "/themes/{$themeName}";
	    $themeUrl	 = $this->request->base_url . "themes/{$themeName}";
	    
	    // Add stylesheet path to the $ly->data array
	    $this->data['stylesheet'] = "{$themeUrl}/style.css";
	
	    // Include the global functions.php and the functions.php that are part of the theme
	    $RED = &$this;
	    $functionsPath = "{$themePath}/functions.php";
	    
	    include("themes/functions.php");
	    if(is_file($functionsPath)) 
	    {
	    	    include $functionsPath;
	    }
	    
	    // Extract $RED->data to own variables and handover to the template file
	     extract($this->data);      
	     extract($this->views->GetData());      
	     include("{$themePath}/default.tpl.php");	  
  }	
}

   
   
   
  
