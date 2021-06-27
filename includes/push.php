<?php 

class Push {
   
    private $title;

    
    private $message;

   
    private $image;
    
    
    private $type;
    
    private $id;

    
    function __construct($title, $message, $image,$type,$id) {
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
        $this->type = $type;
        $this->id = $id; 
    }
    
  
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        $res['data']['type'] = $this->type;
        $res['data']['id'] = $this->id;
      
        return $res;
    }
 
}