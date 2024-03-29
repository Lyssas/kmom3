<?php
/**
* A guestbook controller as an example to show off some basic controller and model-stuff.
* 
* @package RedCore
*/
class CCGuestbook extends CObject implements IController, IHasSQL {

  private $pageTitle = 'RED Guestbook Example';
 // private $pageHeader = '<h1>Guestbook Example</h1><p>Showing off how to implement a guestbook in Lydia.</p>';
//  private $pageMessages = '<h2>Current messages</h2>';

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
  }
  
   public static function SQL($key=null) 
  {
  	  $queries = array(
  	  	  'create table guestbook'  => "CREATE TABLE IF NOT EXISTS Guestbook (id INTEGER PRIMARY KEY, entry TEXT, created DATETIME default (datetime('now')));",
  	  	  'insert into guestbook'   => 'INSERT INTO Guestbook (entry) VALUES (?);',
  	  	  'select * from guestbook' => 'SELECT * FROM Guestbook ORDER BY id DESC;',
  	  	  'delete from guestbook'   => 'DELETE FROM Guestbook;',
  	  	  );
  	  
  	  if(!isset($queries[$key])) 
  	  {
  	  	  throw new Exception("No such SQL query, key '$key' was not found.");
  	  }
  	  
  	  return $queries[$key];
   }

  /**
  * Implementing interface IController. All controllers must have an index action.
  */
  public function Index() 
  {
  	  $this->views->SetTitle($this->pageTitle);
  	  $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
  	  	  						'entries'=>$this->ReadAllFromDatabase(), 
  	  	  						'formAction'=>$this->request->CreateUrl('guestbook/handler')
  	  	  						));
  }
  
  /**
  * Handle posts from the form and take appropriate action.
  */
  public function Handler() 
  {
  	  if(isset($_POST['doAdd'])) 
  	  {
  	  	  $this->SaveNewToDatabase(strip_tags($_POST['newEntry']));
  	  }
  	  elseif(isset($_POST['doClear'])) 
  	  {
  	  	  $this->DeleteAllFromDatabase();
  	  }            
  	  elseif(isset($_POST['doCreate'])) 
  	  {
  	  	  $this->CreateTableInDatabase();
  	  }            
  	  header('Location: ' . $this->request->CreateUrl('guestbook'));
  }
  
  /**
  * Save a new entry to database.
  */
  private function CreateTableInDatabase() 
  {
  	  try 
  	  {
  	  	  $this->db->ExecuteQuery(self::SQL('create table guestbook'));
      	  } 
      	  catch(Exception$e) 
      	  {
      	  	  die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
      	  }
  }
  
    /**
   * Save a new entry to database.
   */
    /**
   * Save a new entry to database.
   */
  private function SaveNewToDatabase($entry) 
  {  	
  	  $this->db->ExecuteQuery(self::SQL('insert into guestbook'), array($entry));
  	  $this->session->AddMessage('success', 'Successfully inserted new message.');
  	  if($this->db->rowCount() != 1) 
  	  {
  	  	  echo 'Failed to insert new guestbook item into database.';
  	  }
  	  
  }
  
  /**
  * Delete all entries from the database.
  */
  private function DeleteAllFromDatabase() 
  {
  	  $this->db->ExecuteQuery(self::SQL('delete from guestbook'));
  	  $this->session->AddMessage('info', 'Removed all messages from the database table.');
  	  
  }
  
  /**
  * Read all entries from the database.
  */
  private function ReadAllFromDatabase() 
  {
  	  try 
  	  {
  	  	  $this->db->SetAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  	  	  return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * from guestbook'));
  	  } 
  	  catch(Exception $e) 
  	  {
  	  	  return array();    
  	  }
  }
  
  /**
  * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
  *
  * @param string $key the string that is the key of the wanted SQL-entry in the array.
  */
 
   
}
