<?php

class Attendee{
    private $idattendee;
    private $name;
    private $password;
    private $role;

        public function getIdattendee(){
             return $this->idattendee;
        }

        public function getName(){
             return $this->name;
        }

        public function getPassword(){
             return $this->password;
        }

        public function getRole(){
             return $this->role;
        }
}

class Role{
     private $idrole;
     private $name;


       /**
      * Get the value of idrole
      */ 
      public function getIdrole()
      {
           return $this->idrole;
      }


       /**
      * Get the value of name
      */ 
     public function getName()
     {
          return $this->name;
     }

}

class Venue{

     private $idvenue;
     private $name;
     private $capacity;

     /**
      * Get the value of idvenue
      */ 
     public function getIdvenue()
     {
          return $this->idvenue;
     }

     /**
      * Get the value of name
      */ 
     public function getName()
     {
          return $this->name;
     }

     /**
      * Get the value of capacity
      */ 
     public function getCapacity()
     {
          return $this->capacity;
     }
}

class Event{

     private $idevent;
     private $name;
     private $datestart;
     private $dateend;
     private $numberallowed;
     private $venue;



     /**
      * Get the value of numberallowed
      */ 
     public function getNumberallowed()
     {
          return $this->numberallowed;
     }

     /**
      * Get the value of venue
      */ 
     public function getVenue()
     {
          return $this->venue;
     }

     /**
      * Get the value of dateend
      */ 
     public function getDateend()
     {
          return $this->dateend;
     }

     /**
      * Get the value of datestart
      */ 
     public function getDatestart()
     {
          return $this->datestart;
     }

     /**
      * Get the value of name
      */ 
     public function getName()
     {
          return $this->name;
     }

     /**
      * Get the value of idevent
      */ 
     public function getIdevent()
     {
          return $this->idevent;
     }
}


class Session{

     private $idsession;
     private $name;
     private $numberallowed;
     private $event;
     private $startdate;
     private $enddate;


     /**
      * Get the value of enddate
      */ 
     public function getEnddate()
     {
          return $this->enddate;
     }

     /**
      * Get the value of startdate
      */ 
     public function getStartdate()
     {
          return $this->startdate;
     }

     /**
      * Get the value of event
      */ 
     public function getEvent()
     {
          return $this->event;
     }

     /**
      * Get the value of numberallowed
      */ 
     public function getNumberallowed()
     {
          return $this->numberallowed;
     }

     /**
      * Get the value of name
      */ 
     public function getName()
     {
          return $this->name;
     }

     /**
      * Get the value of idsession
      */ 
     public function getIdsession()
     {
          return $this->idsession;
     }
}

class ManagerEvent{
     
     private $event;
     private $manager;


     /**
      * Get the value of manager
      */ 
     public function getManager()
     {
          return $this->manager;
     }

     /**
      * Get the value of event
      */ 
     public function getEvent()
     {
          return $this->event;
     }
}

class AttendeeEvent{
     private $event;
     private $attendee;
     private $paid;

     
     /**
      * Get the value of event
      */ 
     public function getEvent()
     {
          return $this->event;
     }

     /**
      * Get the value of attendee
      */ 
     public function getAttendee()
     {
          return $this->attendee;
     }

     /**
      * Get the value of paid
      */ 
     public function getPaid()
     {
          return $this->paid;
     }
}

class AttendeeSession{
     private $session;
     private $attendee;

     /**
      * Get the value of session
      */ 
     public function getSession()
     {
          return $this->session;
     }

     /**
      * Get the value of attendee
      */ 
     public function getAttendee()
     {
          return $this->attendee;
     }

   
}


?>

