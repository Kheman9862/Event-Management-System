<?php
class DB {

    private static $host = "localhost";
    private static $database = "emsystem";
    private static $username = "root";
    private static $password = "";
    private $db;

   function __construct() {
       
    require_once ($_SERVER['DOCUMENT_ROOT'] ."/EMS/class/class.php");
        $dbq = 'mysql:host=' . DB::$host . ';dbname=' . DB::$database;

		try{			
			$this->db = new PDO($dbq, DB::$username, DB::$password);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $pdoException){
			echo $pdoException->getMessage();
			die();
		}
    }

    // Role--------------------------------------------------------------------------------------------

    public function getAllRoles(){
        try{
            $data=array();
            $query = "SELECT * FROM role";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Role");
            while($role = $stmt->fetch()){
                $data[] = $role;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    function getRoleById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM role WHERE idrole = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Role");
            while($role = $stmt->fetch()){
                $data = $role;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }

    //USERS--------------------------------------------------------------------------------------------

    public function insertUser($name,$password,$role){
        try{
            $query = "INSERT INTO attendee (name, password, role) VALUES (:name, :password, :role)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["name"=>$name, "password"=>$password, "role"=>$role]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    } 

    public function loginUser($name,$password){
        try{
            $query = "SELECT * FROM attendee WHERE name= :name";
            $stmt = $this->db->prepare($query);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Attendee");
            $stmt->execute(["name"=>$name]);
            $user = $stmt->fetch();
            if($user){
                if($password == $user->getPassword()){
                    return $user;
                }
                else{
                header("Location:../pages/login.php?error=nomatch");
                exit();
                }    
            }
            else{
                header("Location:../pages/login.php?error=nouser");
                exit();
            }
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    public function getAllUsers(){
        try{
            $data=array();
            $query = "SELECT * FROM attendee";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Attendee");
            while($users = $stmt->fetch()){
                $data[] = $users;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    function deleteUser($id){
        try{
            // deleting attendee
            $query = "DELETE FROM attendee WHERE idattendee = :idattendee";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["idattendee" => $id]);
            
            // deleting user from event
            $q1 = "DELETE FROM event WHERE idevent IN(SELECT manager_event.event FROM manager_event WHERE manager=:manager)";
            $stmt = $this->db->prepare($q1);
            $stmt->execute(["manager" => $id]);
            
            // deleting manager event
            $q2 = "DELETE FROM manager_event WHERE manager = :manager";
            $stmt = $this->db->prepare($q2);
            $stmt->execute(["manager" => $id]);
            $del = $stmt->rowCount();
            
            // deleting attendee event
            $q3 = "DELETE FROM attendee_event WHERE attendee = :attendee";
            $stmt = $this->db->prepare($q3);
            $stmt->execute(["attendee" => $id]);
            
            // deleting attendee session
            $q4 = "DELETE FROM attendee_session WHERE attendee = :attendee";
            $stmt = $this->db->prepare($q4);
            $stmt->execute(["attendee" => $id]);
            $del = $stmt->rowCount();
            return $del;
        }catch(PDOException $error) {
          echo $error->getMessage();
          die();
        }
    }

    function getSuperAdmin($iduser,$idrole){
        $txt = "";
        if($iduser==9){
               $txt="super admin";  
        }
        else{
            $txt = $this->getRoleById($idrole)->getName();
        }
        return $txt;
    }

    function getAllUsersAsTable(){
        $data = $this->getAllUsers();
        if(count($data) > 0){
            $bigString="
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addUser.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add User
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
                $val=$this->getSuperAdmin($row->getIdattendee(),$row->getRole());
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
              <h5 class=\"card-title\">{$row->getName()}</h5>
              <h6 class=\"card-subtitle mb-2 text-muted\">{$this->getSuperAdmin($row->getIdattendee(),$row->getRole())}</h6>
              <p class=\"card-text\">This is a User card.</p>
              <div class='text-center'>";

              if($val!="super admin"){
              $bigString .=  "<a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditUser.php?id={$row->getIdattendee()}\">Edit</a>
              <a href=\"../DeleteForms/deleteUser.php?id={$row->getIdattendee()}\" class=\"card-link btn btn-outline-danger\">Delete</a>";
            }
             
             $bigString.=" </div>
            </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addUser.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add User
    </button>
    </a>
    </div>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No User exist.</h2>
            </div>";
        }
        return $bigString;
    }

    function getAllAttendeeById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM attendee WHERE idattendee = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "attendee");
            while($attendee = $stmt->fetch()){
                $data = $attendee;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }


    public function updateUser($name,$passwordHashed,$role,$iduser){
        try{
        $sql = "UPDATE attendee SET name=:name, password=:passwordHashed, role=:role  WHERE idattendee=:iduser";
        $stmt= $this->db->prepare($sql);
        if($stmt->execute(["name"=>$name,"passwordHashed"=>$passwordHashed,"role"=> $role,"iduser"=>$iduser])){
            return 1;
        }
        else{
            return 0;
        }
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }

    function getUserById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM attendee WHERE idattendee = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Attendee");
            while($role = $stmt->fetch()){
                $data = $role;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }

    //Attendes--------------------------------------------------------------------------------------------

    public function getAllAttendee1(){
        try{
            $data=array();
            $query = "SELECT * FROM attendee WHERE role=3";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Attendee");
            while($attendee = $stmt->fetch()){
                $data[] = $attendee;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    function getAllAttendeesAsTable1(){
        $data = $this->getAllAttendee1();
        if(count($data) > 0){
            $bigString="
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addUser.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add User
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
              <h5 class=\"card-title\">{$row->getName()}</h5>
              <h6 class=\"card-subtitle mb-2 text-muted\">{$this->getRoleById($row->getRole())->getName()}</h6>
              <p class=\"card-text\">This is attendees</p>
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditUser.php?id={$row->getIdattendee()}\">Edit</a>
              <a href=\"../DeleteForms/deleteUser.php?id={$row->getIdattendee()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
              </div>
            </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = 
            "<div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addUser.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add User
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No User exist.</h2>
            </div>
            ";
        }
        return $bigString;
    }

  
    //Venue--------------------------------------------------------------------------------------------

    public function insertVenue($name,$capacity){
        try{
            $query = "INSERT INTO venue (name, capacity) VALUES (:name, :capacity)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["name"=>$name, "capacity"=>$capacity]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    } 

    public function updateVenue($name,$capacity,$idvenue){
        try{
        $sql = "UPDATE venue SET name=:name, capacity=:capacity WHERE idvenue=:idvenue";
        $stmt= $this->db->prepare($sql);
        if($stmt->execute(["name"=>$name,"capacity"=> $capacity,"idvenue"=>$idvenue])){
            return 1;
        }
        else{
            return 0;
        }
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }



    public function getAllVenues(){
        try{
            $data=array();
            $query = "SELECT * FROM venue";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Venue");
            while($venue = $stmt->fetch()){
                $data[] = $venue;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    function deleteVenue($id){
        try{
            $query = "DELETE FROM venue WHERE idvenue = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["id" => $id]);

            //select all events that will be deleting because of the venue
            $q1 = "SELECT * FROM event WHERE venue = :id";
            $stmt = $this->db->prepare($q1);
            $stmt->execute(["id" => $id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Event");
            while($events = $stmt->fetch()){
                $Events[] = $events;
            }
            foreach($Events as $event){
                $this->deleteEvent($event->getIdevent());
            };
            $del = $stmt->rowCount();
            return $del;
        }catch(PDOException $error) {
          echo $error->getMessage();
          die();
        }
    }

    function getAllVenuesAsTable(){
        $data = $this->getAllVenues();
        if(count($data) > 0){
            $bigString = "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addVenue.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Venue
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
              <h5 class=\"card-title\">{$row->getName()}</h5>
              <h6 class=\"card-subtitle mb-2 text-muted\">Capacity: {$row->getCapacity()}</h6>
              <p class=\"card-text\">This is venue</p>
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditVenue.php?id={$row->getIdvenue()}\">Edit</a>
              <a href=\"../DeleteForms/deleteVenue.php?id={$row->getIdvenue()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
                </div>
              </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = 
           "<div class=\"section \">        
            <div class=\"container mt-5\">
        
            <div class=\"row\">
            <div class='col-5'></div>
            <div class='col-7'>
            <a href=\"../AddForms/addVenue.php\">
            <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
            Add Venue
            </button>
            </a>
            </div>
            </div>
                <div class=\"row flex-row-stretch mt-5\">
            <h2>No Venue exist.</h2></div>";
        }
        return $bigString;
    }


    function getVenuesById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM venue WHERE idvenue = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Venue");
            while($venue = $stmt->fetch()){
                $data= $venue;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }


    //Events For just Event Managers--------------------------------------------------------------------------------------------

    function getManagerbyEventid($event){
        try{
            $query = "SELECT * FROM manager_event WHERE event=:event";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["event"=>$event]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "ManagerEvent");
            while($manager = $stmt->fetch()){
                $data= $manager;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }

    }    



function getAllEventsAsTableByEventManagers($id){
        $data = $this->getAllEvents();
        if(count($data) > 0){
            $bigString="
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">";

            if($this->getManagerbyEventid($row->getIdevent())->getManager()==$id){
                $bigString .= "<a href='./attendeeEventsList.php?id={$row->getIdevent()}'>  
            <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>
            </a>";
        }

            else {
            $bigString .= "  <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>";
             }
        $bigString .= " <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Venue: {$this->getVenuesById($row->getVenue())->getName()}</h6>
              <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Manager: {$this->getManagerbyEvent($row->getIdevent())}</h6>
              <p class=\"card-text\">Date Start:{$row->getdatestart()}</p>
              <p class=\"card-text\">Date End:{$row->getdateEnd()}</p>
              <p class=\"card-text\">Number of people: {$row->getNumberallowed()}</p>";
              

              if($this->getManagerbyEventid($row->getIdevent())->getManager()==$id){
              $bigString .= "
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditEvent.php?id={$row->getIdevent()}\">Edit</a>
              <a href=\"../DeleteForms/deleteEvent.php?id={$row->getIdevent()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
              </div>
              <div class='mt-4 text-center'>
              <a class=\"card-link btn btn-outline-warning\" href=\"../AddForms/addAttendeeEvent.php?id={$row->getIdevent()}\">Add Attendee</a>
                </div>  "; 
            }
              $bigString .=  "
              </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = 
            
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Venue exist.</h2>
            
            </div>
            ";
        }
        return $bigString;
    }


    //Managers For just Session Managers--------------------------------------------------------------------------------------------

    function getManagerbySessionid($session){
        try{
            $q = "SELECT manager FROM manager_event LEFT JOIN session on session.event = manager_event.event WHERE idsession = :session";
            // $query = "SELECT * FROM attendee_session LEFT JOIN ";
            $stmt = $this->db->prepare($q);
            $stmt->execute(["session"=>$session]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "ManagerEvent");
            while($manager = $stmt->fetch()){
                $data= $manager;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }

    }  
    
    function getAllSessionsAsTableBySessionManagers($idmanager){
        $data = $this->getAllSessions();
        if(count($data) > 0){
            $bigString =
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addSession.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Session
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">";
            
            if($this->getManagerbySessionid($row->getIdsession())->getManager()==$idmanager){
            $bigString .= "
            <a href='./attendeeSessionsList.php?id={$row->getIdsession()}'>  
              <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>
              </a>";}
              else{
            $bigString .= "
            <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>";

              }
            $bigString .= "
              <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Event: {$this->getAllEventsById($row->getEvent())->getName()}</h6>
              <p class=\"card-text\">Date Start:{$row->getStartdate()}</p>
              <p class=\"card-text\">Date End:{$row->getEnddate()}</p>
              <p class=\"card-text\">Number of people: {$row->getNumberallowed()}</p>";

              if($this->getManagerbySessionid($row->getIdsession())->getManager()==$idmanager){
                 $bigString .= "
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditSession.php?id={$row->getIdsession()}\">Edit</a>
              <a href=\"../DeleteForms/deleteSession.php?id={$row->getIdsession()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
              </div>
              <div class='mt-4 text-center'>
              <a class=\"card-link btn btn-outline-warning\" href=\"../AddForms/addAttendeeSession.php?id={$row->getIdsession()}&idevent={$row->getEvent()}\">Add Attendee</a>
                </div>";};
            $bigString .= "
            </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString =
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addSession.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Session
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Session exist.</h2>
            </div>
            ";
        }
        return $bigString;
    }

    //Events--------------------------------------------------------------------------------------------

    public function insertEvent($name,$datestart,$dateend,$numberallowed,$venue,$manager){
        try{
            $query = "INSERT INTO event (name,datestart,dateend,numberallowed,venue) VALUES (:name,:datestart,:dateend,:numberallowed,:venue)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["name"=>$name, "datestart"=>$datestart,"dateend"=>$dateend,"numberallowed"=>$numberallowed,"venue"=>$venue]);
            $event = $this->db->lastInsertId();
            $query2 = "INSERT INTO manager_event (event,manager) VALUES (:event,:manager)";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->execute(["event"=>$event,"manager"=>$manager]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    } 



    public function getAllEvents(){
        try{
            $data=array();
            $query = "SELECT * FROM event";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Event");
            while($event = $stmt->fetch()){
                $data[] = $event;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    function getAllEventsById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM event WHERE idevent = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "event");
            while($event = $stmt->fetch()){
                $data = $event;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }


    function getManagerbyEvent($event){
        try{
            $query = "SELECT * FROM manager_event WHERE event=:event";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["event"=>$event]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "ManagerEvent");
            $managerid = $stmt->fetch()->getManager(); 
            $name = $this->getUserById($managerid)->getName();
            return $name;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }

    }    



    function getAllEventsAsTable(){
        $data = $this->getAllEvents();
        if(count($data) > 0){
            $bigString="
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
            <a href='./attendeeEventsList.php?id={$row->getIdevent()}'>  
            <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>
            </a>
            <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Venue: {$this->getVenuesById($row->getVenue())->getName()}</h6>
              <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Manager: {$this->getManagerbyEvent($row->getIdevent())}</h6>
              <p class=\"card-text\">Date Start:{$row->getdatestart()}</p>
              <p class=\"card-text\">Date End:{$row->getdateEnd()}</p>
              <p class=\"card-text\">Number of people: {$row->getNumberallowed()}</p>
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditEvent.php?id={$row->getIdevent()}\">Edit</a>
              <a href=\"../DeleteForms/deleteEvent.php?id={$row->getIdevent()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
              </div>
              <div class='mt-4 text-center'>
              <a class=\"card-link btn btn-outline-warning\" href=\"../AddForms/addAttendeeEvent.php?id={$row->getIdevent()}\">Add Attendee</a>
                </div>
              </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = 
            
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Venue exist.</h2>
            
            </div>
            ";
        }
        return $bigString;
    }

  public function deleteEvent($id){

        try{
            $query = "DELETE FROM event WHERE idevent = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["id" => $id]);

            // Deleting all the child nodes

            $q2 = "DELETE FROM attendee_session WHERE session IN(SELECT session.idsession FROM session WHERE event=:id)";
            $stmt = $this->db->prepare($q2);
            $stmt->execute(["id" => $id]);
            $q3 = "DELETE FROM attendee_event WHERE event = :id";
            $stmt = $this->db->prepare($q3);
            $stmt->execute(["id" => $id]);
            $q4 = "DELETE FROM manager_event WHERE event = :id";
            $stmt = $this->db->prepare($q4);
            $stmt->execute(["id" => $id]);
            $q5 = "DELETE FROM session WHERE event = :id";
            $stmt = $this->db->prepare($q5);
            $stmt->execute(["id" => $id]);
            $del = $stmt->rowCount();
            return $del;
        }catch(PDOException $error) {
            echo $error->getMessage();
            die();
        }
    }    

    
    public function updateEvent($name,$datestart,$dateend,$numberallowed,$venue,$manager,$idevent){
        try{
        $sql = "UPDATE event SET name=:name, datestart=:datestart,dateend=:dateend,numberallowed=:numberallowed, venue=:venue WHERE idevent=:idevent";
        $stmt= $this->db->prepare($sql);
        $stmt->execute(["name"=>$name,"datestart"=>$datestart,"dateend"=>$dateend,"numberallowed"=>$numberallowed, "venue"=>$venue, "idevent"=>$idevent]);
        
        $sql2 = "UPDATE manager_event SET manager = :manager WHERE event=:idevent";
        $stmt = $this->db->prepare($sql2);
        $stmt->execute(["manager"=> $manager, "idevent"=> $idevent]);
    }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }

    //Session--------------------------------------------------------------------------------------------
    public function insertSession($name,$numberallowed,$event,$startdate,$enddate){
        try{
            $query = "INSERT INTO session (name,numberallowed,event,startdate,enddate) VALUES (:name,:numberallowed,:event,:startdate,:enddate)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["name"=>$name, "numberallowed"=>$numberallowed,"event"=>$event,"startdate"=>$startdate,"enddate"=>$enddate]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    } 



    public function getAllSessions(){
        try{
            $data=array();
            $query = "SELECT * FROM session";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Session");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }


    public function getAllSessionsById($id){
        try{
            $stmt = $this->db->prepare("SELECT * FROM session WHERE idsession = :id");
            $stmt->execute(["id"=>$id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Session");
            while($session = $stmt->fetch()){
                $data = $session;
            }
            return $data;
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }

    public function deleteSession($id){
        
        try{
        $query = "DELETE FROM session WHERE idsession = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["id" => $id]);
        
        // Deleting child node attendee-session

        $q2 = "DELETE FROM attendee_session WHERE attendee_session.session=:id";
        $stmt = $this->db->prepare($q2);
        $stmt->execute(["id" => $id]);
        $del = $stmt->rowCount();
        return $del;
    }catch(PDOException $error){
        echo $error->getMessage();
            die();
    }
    }

    function getAllSessionsAsTable(){
        $data = $this->getAllSessions();
        if(count($data) > 0){
            $bigString =
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addSession.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Session
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
            <a href='./attendeeSessionsList.php?id={$row->getIdsession()}'>  
              <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>
              </a>
              <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Event: {$this->getAllEventsById($row->getEvent())->getName()}</h6>
              <p class=\"card-text\">Date Start:{$row->getStartdate()}</p>
              <p class=\"card-text\">Date End:{$row->getEnddate()}</p>
              <p class=\"card-text\">Number of people: {$row->getNumberallowed()}</p>
              <div class='text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../EditForms/EditSession.php?id={$row->getIdsession()}\">Edit</a>
              <a href=\"../DeleteForms/deleteSession.php?id={$row->getIdsession()}\" class=\"card-link btn btn-outline-danger\">Delete</a>
              </div>
              <div class='mt-4 text-center'>
              <a class=\"card-link btn btn-outline-warning\" href=\"../AddForms/addAttendeeSession.php?id={$row->getIdsession()}&idevent={$row->getEvent()}\">Add Attendee</a>
                </div>
            </div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString =
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addSession.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Session
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Session exist.</h2>
            </div>
            ";
        }
        return $bigString;
    }

    public function updateSession($name,$numberallowed,$event,$startdate,$enddate,$idsession){
        try{
        $sql = "UPDATE session SET name=:name, startdate=:startdate,enddate=:enddate,numberallowed=:numberallowed, event=:event WHERE idsession=:idsession";
        $stmt= $this->db->prepare($sql);
        $stmt->execute(["name"=>$name, "startdate"=>$startdate,"enddate"=>$enddate,"numberallowed"=>$numberallowed, "event"=>$event, "idsession"=>$idsession]);
         
        }
        catch(PDOException $pe){
            echo $pe->getMessage();
            die();
        }
    }


    //Manager-Event--------------------------------------------------------------------------------------------

    public function getAllManagerEvents(){
        try{
            $data=array();
            $query = "SELECT * FROM manager_event ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "ManagerEvent");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }
    public function getManagerEventsByMid($mid){
        try{
            $data=array();
            $query = "SELECT * FROM manager_event WHERE manager = :mid";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["mid"=>$mid]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "ManagerEvent");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    public function getAllManagerEventsAsTable(){
        $data = $this->getAllManagerEvents();
        if(count($data) > 0){
            $bigString = "<table border = '1'>\n
                            <tr>
                                <th>Event</th><th>Manager</th>
                            </tr>
                         ";
            foreach($data as $row){
                $bigString .= "<tr>
                                    <td>{$this->getAllEventsById($row->getEvent())->getName()}</td>
                                    <td>{$this->getAllAttendeeById($row->getManager())->getName()}</td>
                                    </tr>\n";           
            }
            foreach($data as $row){
                // print_r();
            }
            $bigString .= "</table>\n";
        }
        else{
            $bigString = "<h2>No Manager exist.</h2>";
        }
        return $bigString;
    }



    //Attendee-Event--------------------------------------------------------------------------------------------

    public function insertAttendeeEvent($event,$attendee,$paid){
        try{
            $query = "INSERT INTO attendee_event (event,attendee,paid) VALUES (:event,:attendee,:paid)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["event"=>$event, "attendee"=>$attendee,"paid"=>$paid]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    } 



    public function getAllAttendeeEvents(){
        try{
            $data=array();
            $query = "SELECT * FROM attendee_event";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "AttendeeEvent");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    public function getPaidFunc($paid){
        if($paid==0){
            return "Paid";
        }
        else{
            return "Unpaid";
        }
    }

    public function getAttendeebyEventsid($id){
        try{
            $data=array();
            $query = "SELECT attendee FROM attendee_event where event=:id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["id" => $id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "AttendeeEvent");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    public function getAllAttendeeEventsAsTable(){
        $data = $this->getAllAttendeeEvents();
        if(count($data) > 0){
            $bigString = "<table border = '1'>\n
                            <tr>
                                <th>Event</th><th>Attendee</th><th>Paid</th>
                            </tr>
                         ";
            foreach($data as $row){
                $bigString .= "<tr>
                                    <td>{$this->getAllEventsById($row->getEvent())->getName()}</td>
                                    <td>{$this->getAllAttendeeById($row->getAttendee())->getName()}</td>
                                    <td>{$this->getPaidFunc($row->getPaid())}</td>
                              </tr>\n";           
            }

            $bigString .= "</table>\n";
        }
        else{
            $bigString = "<h2>No Attendee Event exist.</h2>";
        }
        return $bigString;
    }

    public function checkAttendeeEventEnrollment($event, $attendee){
        $query = "SELECT * FROM attendee_event WHERE event = :event AND attendee = :attendee";
        $statement = $this->db->prepare($query);
        $statement->execute(["event"=>$event, "attendee"=>$attendee]);
        $statement->setfetchMode(PDO::FETCH_CLASS, "EventAttendee");
        $user = $statement->fetch();
        
        if($user){
            return 1;
        }else{
            return 0;
        }

    }

     public function getAlleventsforuser($id){
        $data = $this->getAllEvents();
        if(count($data) > 0){
            $bigString="
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
            <a href='./attendeeEventsList.php?id={$row->getIdevent()}'>  
            <h5 class=\"card-title\" style='color:orange'>{$row->getName()}</h5>
            </a>
            <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Venue: {$this->getVenuesById($row->getVenue())->getName()}</h6>
              <h6 class='class=\"card-subtitle mb-2 text-muted\"'>Manager: {$this->getManagerbyEvent($row->getIdevent())}</h6>
              <p class=\"card-text\">Date Start:{$row->getdatestart()}</p>
              <p class=\"card-text\">Date End:{$row->getdateEnd()}</p>
              <p class=\"card-text\">Capacity: {$row->getNumberallowed()}</p>";
           
              if($this->checkAttendeeEventEnrollment($row->getIdevent(),$id)==1){
                $bigString .= "<div class='mt-4 text-center'>
              <a class=\"card-link btn btn-outline-primary\" href=\"../AddForms/addAttendeeEvent.php?id={$row->getIdevent()}\">Enrolled</a>
                </div>";}

                else{
                        $bigString .= "<div class='mt-4 text-center'>
                      <a class=\"card-link btn btn-outline-danger\" href=\"../AddForms/addAttendeeEvent.php?id={$row->getIdevent()}\">Not Enrolled</a>
                        </div>";
                }

                $bigString .=    "</div>
          </div>
          </div>
          ";
        }

        $bigString .= "
        </div>
        </div>
    </div>
        ";
        }
        else{
            $bigString = 
            
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">

    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    <a href=\"../AddForms/addEvent.php\">
    <button class=\"card-link btn btn-success\" style=\"width: 25%;\">
    Add Event
    </button>
    </a>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Venue exist.</h2>
            
            </div>
            ";
        }
        return $bigString;
     }


    //Attendee-Session--------------------------------------------------------------------------------------------

    public function getAttendeeSessionForm(){

        try{
            $data=array();
            $query = "SELECT * FROM `session` LEFT JOIN attendee_event ON attendee_event.event=session.event WHERE attendee=9";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "Session");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }

    }

    public function insertAttendeeSession($attendee,$session){
        try{
            $query = "INSERT INTO attendee_session (attendee,session) VALUES (:attendee,:session)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["attendee"=>$attendee,"session"=>$session]);
            return $this->db->lastInsertId();
        }
        catch(PDOException $pdoException){
                echo $pdoException->getMessage();
			die();
        }
    }

    public function getAllAttendeSessions(){
        try{
            $data=array();
            $query = "SELECT * FROM attendee_session";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stmt->setfetchMode(PDO::FETCH_CLASS, "AttendeeSession");
            while($session = $stmt->fetch()){
                $data[] = $session;
            }
            return $data;
        }
        catch(PDOException $pdoException){
            echo $pdoException->getMessage();
			die();
        }
    }

    public function getAllAttendeeSessionAsTable(){
        $data = $this->getAllAttendeSessions();
        if(count($data) > 0){
            $bigString = "<table border = '1'>\n
                            <tr>
                                <th>Session</th><th>Attendee</th>
                            </tr>
                         ";
            foreach($data as $row){
                $bigString .= "<tr>
                                    <td>{$this->getAllSessionsById($row->getSession())->getName()}</td>
                                    <td>{$this->getAllAttendeeById($row->getAttendee())->getName()}</td>
                              </tr>\n";           
            }

            $bigString .= "</table>\n";
        }
        else{
            $bigString = "<h2>No Attendee Session exist.</h2>";
        }
        return $bigString;
    }

    public function getAllAttendeBySessionid($id){
        try{
            $data = array();
            $query = "SELECT * FROM attendee_session WHERE session = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["id" => $id]);
            $stmt->setfetchMode(PDO::FETCH_CLASS, "AttendeeSession");
            while($users = $stmt->fetch()){
                $data[] = $users;
            }
            return $data;
        }catch(PDOException $error) {
          echo $error->getMessage();
          die();
        }
    }
    
    public function getAllAttendeebySessionsAsTable($id){
        $data = $this->getAllAttendeBySessionid($id);
        if(count($data) > 0){
            $bigString =  "
            <div class=\"section \">        
    <div class=\"container mt-5\">
    
    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            ";
            foreach($data as $row){
            $bigString .= "
            <div class=\"col-4 mt-5\">
            <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
            <div class=\"card-body\">
              <h5 class=\"card-title\">{$this->getAllAttendeeById($row->getAttendee())->getName()}</h5>
              <h6 class=\"card-subtitle mb-2 text-muted\">{$this->getAllSessionsById($row->getSession())->getName()}</h6>
              <a href=\"../DeleteForms/deleteAttendeeSession.php?id={$row->getAttendee()}\" class=\"card-link btn btn-outline-danger\">Remove User</a>
              </div>
            </div>
          </div>
          </div>
          ";}
            $bigString .= "
            </div>
            </div>
        </div>
            ";
        }
        else{
            $bigString = 
            "
            <div class=\"section \">        
    <div class=\"container mt-5\">
    
    <div class=\"row\">
    <div class='col-5'></div>
    <div class='col-7'>
    </div>
    </div>
        <div class=\"row flex-row-stretch mt-5\">
            <h2>No Attendee exist.</h2>
            </div>
            ";
        }
        return $bigString;
    }
    

    public function deleteAttendeeinSession($id){
        try{
            $query = "DELETE FROM attendee_session WHERE attendee = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(["id" => $id]);
            
            $del = $stmt->rowCount();
            return $del;
        }catch(PDOException $error){
            echo $error->getMessage();
                die();
        }
    }
    




//AttendeeByEvent--------------------------------------------------------------------------------------------


public function getAllAttendeByEventid($id){
    try{
        $data = array();
        $query = "SELECT * FROM attendee_event WHERE event = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["id" => $id]);
        $stmt->setfetchMode(PDO::FETCH_CLASS, "AttendeeEvent");
        while($users = $stmt->fetch()){
            $data[] = $users;
        }
        return $data;
    }catch(PDOException $error) {
      echo $error->getMessage();
      die();
    }
}

public function getAllAttendeebyEventsAsTable($id){
    $data = $this->getAllAttendeByEventid($id);
    if(count($data) > 0){
        $bigString =  "
        <div class=\"section \">        
<div class=\"container mt-5\">
    <div class=\"row mt-5\">
        ";
        foreach($data as $row){
        $bigString .= "
        <div class=\"col-4 mt-5\">
        <div class=\"card\" style=\"width: 18rem; padding:40px 10px\">
        <div class=\"card-body\">
          <h5 class=\"card-title\">{$this->getAllAttendeeById($row->getAttendee())->getName()}</h5>
          <h6 class=\"card-subtitle mb-2 text-muted\">{$this->getPaidFunc($row->getPaid())}</h6>
          <p>{$this->getAllEventsById($row->getEvent())->getName()}</p>
          <a href=\"../DeleteForms/deleteAttendeeEvent.php?id={$row->getAttendee()}\" class=\"card-link btn btn-outline-danger\">Remove User</a>
          </div>
        </div>
      </div>
      </div>
      ";}
        $bigString .= "
        </div>
        </div>
    </div>
        ";
    }
    else{
        $bigString = 
        "
        <div class=\"section \">        
<div class=\"container mt-5\">

<div class=\"row\">
<div class='col-5'></div>
<div class='col-7'>
</div>
</div>
    <div class=\"row flex-row-stretch mt-5\">
        
        <h2>No Attendee exist.</h2>
        </div>
        ";
    }
    return $bigString;
}

public function deleteAttendeeinEvent($id){
    try{
        $query = "DELETE FROM attendee_event WHERE attendee = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["id" => $id]);
        
        $del = $stmt->rowCount();
        return $del;
    }catch(PDOException $error){
        echo $error->getMessage();
            die();
    }
}

}
?>


