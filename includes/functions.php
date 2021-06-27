<?php
include_once('crud.php');


class functions{
	protected $db;
    function __construct(){
        $this->db = new Database();
        $this->db->connect();
        date_default_timezone_set('Asia/Kolkata');
    } 
	
	public $currency_info = array(
		array('code' => 'AED', 'name' => 'United Arab Emirates Dirham'),
		array('code' => 'ANG', 'name' => 'NL Antillian Guilder'),
		array('code' => 'ARS', 'name' => 'Argentine Peso'),
		array('code' => 'AUD', 'name' => 'Australian Dollar'),
		array('code' => 'BRL', 'name' => 'Brazilian Real'),
		array('code' => 'BSD', 'name' => 'Bahamian Dollar'),
		array('code' => 'CAD', 'name' => 'Canadian Dollar'),
		array('code' => 'CHF', 'name' => 'Swiss Franc'),
		array('code' => 'CLP', 'name' => 'Chilean Peso'),
		array('code' => 'CNY', 'name' => 'Chinese Yuan Renminbi'),
		array('code' => 'COP', 'name' => 'Colombian Peso'),
		array('code' => 'CZK', 'name' => 'Czech Koruna'),
		array('code' => 'DKK', 'name' => 'Danish Krone'),
		array('code' => 'EUR', 'name' => 'Euro'),
		array('code' => 'FJD', 'name' => 'Fiji Dollar'),
		array('code' => 'GBP', 'name' => 'British Pound'),
		array('code' => 'GHS', 'name' => 'Ghanaian New Cedi'),
		array('code' => 'GTQ', 'name' => 'Guatemalan Quetzal'),
		array('code' => 'HKD', 'name' => 'Hong Kong Dollar'),
		array('code' => 'HNL', 'name' => 'Honduran Lempira'),
		array('code' => 'HRK', 'name' => 'Croatian Kuna'),
		array('code' => 'HUF', 'name' => 'Hungarian Forint'),
		array('code' => 'IDR', 'name' => 'Indonesian Rupiah'),
		array('code' => 'ILS', 'name' => 'Israeli New Shekel'),
		array('code' => 'INR', 'name' => 'Indian Rupee'),
		array('code' => 'ISK', 'name' => 'Iceland Krona'),
		array('code' => 'JMD', 'name' => 'Jamaican Dollar'),
		array('code' => 'JPY', 'name' => 'Japanese Yen'),
		array('code' => 'KRW', 'name' => 'South-Korean Won'),
		array('code' => 'LKR', 'name' => 'Sri Lanka Rupee'),
		array('code' => 'MAD', 'name' => 'Moroccan Dirham'),
		array('code' => 'MMK', 'name' => 'Myanmar Kyat'),
		array('code' => 'MXN', 'name' => 'Mexican Peso'),
		array('code' => 'MYR', 'name' => 'Malaysian Ringgit'),
		array('code' => 'NOK', 'name' => 'Norwegian Kroner'),
		array('code' => 'NZD', 'name' => 'New Zealand Dollar'),
		array('code' => 'PAB', 'name' => 'Panamanian Balboa'),
		array('code' => 'PEN', 'name' => 'Peruvian Nuevo Sol'),
		array('code' => 'PHP', 'name' => 'Philippine Peso'),
		array('code' => 'PKR', 'name' => 'Pakistan Rupee'),
		array('code' => 'PLN', 'name' => 'Polish Zloty'),
		array('code' => 'RON', 'name' => 'Romanian New Lei'),
		array('code' => 'RSD', 'name' => 'Serbian Dinar'),
		array('code' => 'RUB', 'name' => 'Russian Rouble'),
		array('code' => 'SEK', 'name' => 'Swedish Krona'),
		array('code' => 'SGD', 'name' => 'Singapore Dollar'),
		array('code' => 'THB', 'name' => 'Thai Baht'),
		array('code' => 'TND', 'name' => 'Tunisian Dinar'),
		array('code' => 'TRY', 'name' => 'Turkish Lira'),
		array('code' => 'TTD', 'name' => 'Trinidad/Tobago Dollar'),
		array('code' => 'TWD', 'name' => 'Taiwan Dollar'),
		array('code' => 'USD', 'name' => 'US Dollar'),
		array('code' => 'VEF', 'name' => 'Venezuelan Bolivar Fuerte'),
		array('code' => 'VND', 'name' => 'Vietnamese Dong'),
		array('code' => 'XAF', 'name' => 'CFA Franc BEAC'),
		array('code' => 'XCD', 'name' => 'East Caribbean Dollar'),
		array('code' => 'XPF', 'name' => 'CFP Franc'),
		array('code' => 'ZAR', 'name' => 'South African Rand')
	);
	
	function get_system_configs(){
		$sql = "select * from `settings` where `variable`='system_timezone'";
    	$this->db->sql($sql);
    	$result = $this->db->getResult();
    	$this->db->disconnect();
    	if(!empty($result)){
    	    return json_decode($result[0]['value'],1);
    	}else{
    	    return false;
    	}
    	
	}

	function get_random_string($valid_chars, $length){

		// start with an empty random string
		$random_string = "";

		// count the number of chars in the valid chars string so we know how many choices we have
		$num_valid_chars = strlen($valid_chars);

		// repeat the steps until we've created a string of the right length
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random number from 1 up to the number of valid chars
			$random_pick = mt_rand(1, $num_valid_chars);

			// take the random character out of the string of valid chars
			// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
			$random_char = $valid_chars[$random_pick-1];

			// add the randomly-chosen char onto the end of our string so far
			$random_string .= $random_char;
		}

		// return our finished random string
		return $random_string;
	}// end of get_random_string()
	
	function sanitize($string){
		// check string value
		$string = trim(strip_tags(stripslashes($string)));
		return $string;
	}// end of sanitize()
	
	function check_integer($which) {
		if(isset($_GET[$which])){
			if (intval($_GET[$which])>0) {
				return intval($_GET[$which]);
			} else {
				return false;
			}
		}
		return false;
	}//end of check_integer()

	function get_current_page() {
		if(($var=$this->check_integer('page'))) {
			//return value of 'page', in support to above method
			return $var;
		} else {
			//return 1, if it wasnt set before, page=1
			return 1;
		}
	}//end of method get_current_page()
	
	function doPages($page_size, $thepage, $query_string, $total=0, $keyword) {
		//per page count
		$index_limit = 10;
		
		//set the query string to blank, then later attach it with $query_string
		$query='';
		
		if(strlen($query_string)>0){
			$query = "&amp;".$query_string;
		}
			
		//get the current page number example: 3, 4 etc: see above method description
		$current = $this->get_current_page();
		
		$total_pages=ceil($total/$page_size);
		$start=max($current-intval($index_limit/2), 1);
		$end=$start+$index_limit-1;

		echo '<div id="box-footer clearfix">';
		echo '<ul class="pagination pagination-sm no-margin pull-right">';

		if($current==1) {
			echo '';
		} else {
			$i = $current-1;
			echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'">&laquo;</a></li>';
			//echo '<p>...</p>&nbsp;';
		}
			//<button>'.$i.'</button>
		if($start > 1) {
			$i = 1;
			echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
		}

		for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
			if($i==$current) {
				echo '<li class="active"><a>'.$i.'</a></li>';
			} else {
				echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
			}
		}

		if($total_pages > $end){
			$i = $total_pages;
			echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
		}

		if($current < $total_pages) {
			$i = $current+1;
			//echo '<p>...</p>&nbsp;';
			echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'">&raquo;</a></li>';
		} else {
			echo '';
		}
		
		echo '</ul>';

		//if nothing passed to method or zero, then dont print result, else print the total count below:       
		if ($total != 0){
			//prints the total result count just below the paging
			echo '<p><br>Total :  '.$total.'</p></div>';
		}else {
			echo '</div>';
		};
	 
	}//end of method doPages()
	
	public function slugify($text,$table='products',$field='slug',$key=NULL,$value=NULL)
    {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
    
      // trim
      $text = trim($text, '-');
    
      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);
    
      // lowercase
      $slug = strtolower($text);
      
      if (empty($slug)) {
        return 'n-a';
      }
        $sql = "SELECT COUNT(id) AS total_slugs FROM $table WHERE $field  LIKE '$slug%'";
        $this->db->sql($sql);
    	$total = $this->db->getResult();
        return ($total[0]['total_slugs'] > 0) ? ($slug . '-' . $total[0]['total_slugs']) : $slug;
    }
     //storing token in database 
    public function registerDevice($user_id,$token){
        if(!$this->isDeviceRegistered($user_id)){
        	$sql="INSERT INTO devices (user_id, token) VALUES ('$user_id','$token')";
        	$this->db->sql($sql);
        	$res=$this->db->getResult();
        	if(!empty($res))
                return 1; //return 1 means failure
            return 0; //return 0 means success
        }else{
        	$sql="update devices set token='$token' where `user_id` =".$user_id;
            $this->db->sql($sql);
    		return 2; //returning 2 means user_id already exist
        }
    }
    //the method will check if email already exist 
    private function isDeviceRegistered($user_id){
    	$sql="SELECT id FROM devices WHERE `user_id` =".$user_id;
        $this->db->sql($sql);
        $res=$this->db->getResult();
        $num_rows = $this->db->numRows($res);
        return $num_rows > 0;
    }
     //getting all tokens to send push to all devices
    public function getAllTokens(){
    	$sql="SELECT `fcm_id` FROM `users`";
    	$this->db->sql($sql); 
        /* $result = $stmt->get_result();
        $tokens = array(); 
        while($token = $result->fetch_assoc()){
            array_push($tokens, $token['token']);
        } */
        $res=$this->db->getResult();
        $tokens = array(); 
        foreach($res as $row){
        	array_push($tokens, $row['fcm_id']);
        }
        return $tokens; 
    }
    //getting a specified token to send push to selected device
    public function getTokenByEmail($email){
    	$sql="SELECT fcm_id FROM users WHERE email = '".$email."'";
    	$this->db->sql($sql); 
    	$res=$this->db->getResult();
    	$tokens = array(); 
        foreach($res as $row){
        	array_push($tokens, $row['token']);
        }
        return $tokens;        
    }
    //getting all the registered devices from database 
    public function getAllDevices(){
    	$sql="SELECT fcm_id FROM users";
        $this->db->sql($sql); 
    	$res=$this->db->getResult();
        return $result; 
    }
    public function getTokenByUid($uid){
    	$sql="SELECT fcm_id FROM users WHERE id = '".$uid."'";
    	$this->db->sql($sql); 
    	$res=$this->db->getResult();
    	$token = array(); 
        foreach($res as $row){
        	array_push($token, $row['fcm_id']);
        }
        return $token;        
    }
}
?>