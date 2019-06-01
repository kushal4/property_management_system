<?php
class sensession{
    private static $instance;
    private $_is_log_set=false;
    private $_session_log_handle=null;
    private $_curr_secmap_group=null;


    /**
     * Constructor which creates instance and instanitiates the sec_map array
     */
    private function __construct(){
        
        $_SESSION['sec_map']=array();
    }

    /**
     * 
     * Fetch Singleton instance from this static function
     * which checks whether instance is there,if not
     *  present return new instance else return existing one
     */
    public static function getIstance(){
        if(session_id() == ''){
            //session has not started
            session_start();
        }
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;

        }

        return self::$instance;
    }

    /**
     * set log with the log file 
     * 
     * @$log_file : log file on which all of the session dumps will 
     * be done for debugging purpose
     */
    public function set_log($log_file,$base_folder,$file_mode="w"){
        $this->_is_log_set=true;
        $abs_log_path=$_SERVER["DOCUMENT_ROOT"]."/".$base_folder."/".$log_file;
        $this->_session_log_handle=fopen($abs_log_path,"$file_mode");
        fwrite($this->_session_log_handle,"log set ");
    }

    /**
     * 
     * unset the log file so that no  further dumps is to be made
     *  
     */
    public function unset_log(){
        $this->_is_log_set=false;
        
        if($this->_session_log_handle){
           fclose($this->_session_log_handle);
           $this->_session_log_handle=null; 
        }  
    }


  /**
   * insert key value pair in the session array
   * token is a (key, val) pair
   * returns a boolean that is true if a new token is created, false if existing token is updated
   */

    public function token($key,$value){
        $ret=false;
        if(!(array_key_exists($key,$_SESSION))){
            $ret=true;
        }

        $_SESSION[$key]=$value;

        if($this->_is_log_set){
            $log_string="---*****session token operation started*****------ \n";
            $this->_log_key_value_type("Session","Key: ",$key,$log_string);
            $this->_log_key_value_type("Session","Value: ",$value,$log_string);
            $this->_log_key_value_type("Session","New Token created: ",$ret,$log_string);
            fwrite($this->_session_log_handle,$log_string);
            //$log_string.="*********Session key :- $key*********** \n";
           // $log_string.="******** Session Value :- $value******* \n";
        }
        return $ret;
    }

    /**
     * 
     * update the existing key in the session array
     */
    /*
    public function update($key,$value){
        $ret=1;
        if(array_key_exists($key,$_SESSION)){
            $_SESSION[$key]=$value;
            $ret=0;
        }
        if($this->_is_log_set){
            $log_string="---*****Update session operation started*****------ \n";
            $this->_log_key_value_type("Session","Key",$key,$log_string);
            $this->_log_key_value_type("Session","Value",$value,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }

        return $ret;
    }
    */

    /**
     * clear the session array to empty string
     * Returns true if the token is found and successfully cleared
     */
    public function clear($key){
        $ret=false;
        if(array_key_exists($key,$_SESSION)){
            $_SESSION[$key]=null;
            $ret=true;
        }

        if($this->_is_log_set){
            $log_string="---*****Clear session operation started*****------ \n";
            $this->_log_key_value_type("Session","Key",$key,$log_string);
            $this->_log_key_value_type("Session","Token Found: ",$ret,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }
        return $ret;
    }

    /**
     * 
     * unset the key from the session array
     */
    public function delete($key){
        $ret=false;
        if(array_key_exists($key,$_SESSION)){
            unset($_SESSION[$key]);
            $ret=true;
        }

        if($this->_is_log_set){
            $log_string="---*****Delete session operation started*****------ \n";
            $this->_log_key_value_type("Session","Key",$key,$log_string);
            $this->_log_key_value_type("Session","Token Found: ",$ret,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }
       return $ret;
    }

    /**
     * get the value associated to the session array
     * 
     */
    public function get_val($key){
        $ret=false;
        $session_val=null;
        if(array_key_exists($key,$_SESSION)){
          $session_val=$_SESSION[$key];
          $ret=true;
        }

        if($this->_is_log_set){
            $log_string="---*****get_value from  session operation started*****------ \n";
            $this->_log_key_value_type("Session","Key",$key,$log_string);
            $this->_log_key_value_type("Session","Value Fetched",$session_val,$log_string);
            $this->_log_key_value_type("Session","Token Found: ",$ret,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }

        return $session_val;
    }

    /**
     * returns true in the token with the specified key exists
     */
    public function token_exists($key){

        $ret = array_key_exists($key,$_SESSION);
        if($this->_is_log_set){
            $log_string="---*****token_exists*****------ \n";
            $this->_log_key_value_type("Session","Key",$key,$log_string);
            $this->_log_key_value_type("Session","Token Found: ",$ret,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }

        return $ret;
    }

    /*
    Switches to a sec group. All existing sec groups are automatically cleared.
    Once this function is called, only this sec group will exist in SESSION
    */
    public function set_curr_secmap_group($sec_map_group_name) {
        $this->$_curr_secmap_group = $sec_map_group_name;
        foreach (array_keys($_SESSION['sec_map']) as $sec_map_group_key) {
            $this->delete_fea_secmap_group($sec_group_key);
        }
        $this->_create_fea_secmap_group();
    }

    /**
     * create a sec_map group. Returns false if group already exists. Else Creates the group and returns true 
     * 
     */


    private function _create_fea_secmap_group(){
        $ret = false;
        if(!(array_key_exists($this->$_curr_secmap_group,$_SESSION['sec_map']))){
            $_SESSION['sec_map'][$this->$_curr_secmap_group]=array();
            $ret = true;
        }
        return $ret;
    }

    /*
        Clears current secmap group and makes it empty. Removes all secmaps under this secmap group
        if the group is found and successfully emptied, retunrs true. Else false.
    */
    public function clear_fea_secmap_group(){
        $ret = false;
        if (array_key_exists($this->$_curr_secmap_group,$_SESSION['sec_map'])){
            $ret = true;
            foreach (array_keys($_SESSION['sec_map'][$this->$_curr_secmap_group]) as $sec_map_key) {
                $_SESSION['sec_map'][$this->$_curr_secmap_group][$sec_map_key] = array();
                unset ($_SESSION['sec_map'][$this->$_curr_secmap_group][$sec_map_key]);
            }
            $_SESSION['sec_map'][$this->$_curr_secmap_group] = array();
        }
        return $ret;
    }

    /*
    Removes current secmap group from session. If found and successfully removed, returns true. Else false
    */
    private function remove_curr_secmap_group(){
        $ret = false;
        if ($this->clear_fea_secmap_group()) {
            $ret = true;
            unset ($_SESSION['sec_map'][$this->$_curr_secmap_group]);
        }
        $this->$_curr_secmap_group = null;
        return $ret;
    }

    /*
        Inerts a secmap into the current secmap group
    */


    /*
    Inserts a secmap under current secmap group. On success, returns code 0
    - if the current group is not set, returns failure code 1
    - if the secmap key already exists, retuns failure code 2
    
    */
    public function insertmap($key,$map_val){
        $ret = 0;
        $log_string="---*****insertmap to session array operation started with secmap Key '".$key."'*****------ \n";
        if ($this->$_curr_secmap_group != NULL) {
            if (!(key_exists($key, $_SESSION['sec_map'][$this->$_curr_secmap_group]))) {
                $_SESSION['sec_map'][$this->$_curr_secmap_group][$key]=$map_val;

                if($this->_is_log_set){
                    $this->_log_key_value_type("","Ret ",$ret,$log_string);
                    $this->_log_key_value_type("Session sec map array ","Key",$key,$log_string);
                    $this->_log_key_value_type("Session sec map array","Sec map Value",$map_val,$log_string);
                    fwrite($this->_session_log_handle,$log_string);
                }
            } else {
                $ret = 2;
                if($this->_is_log_set){
                    $this->_log_key_value_type(""," ","Secmap Key already Exists. Insert Failed!!!",$log_string);
                    $this->_log_key_value_type("","Ret ",$ret,$log_string);
                    fwrite($this->_session_log_handle,$log_string);
                }
            }
        } else {
            $ret = 1;
            if($this->_is_log_set){
                $this->_log_key_value_type("","Ret ",$ret,$log_string);
                fwrite($this->_session_log_handle,$log_string);
            }
        }
        return $ret;
    }

    /**
     * update sec_map key to sec_map array and return success or failure code
     */

     public function updatemap($key,$map_val){
         $ret=1;
         $session_map_arr=&$_SESSION['sec_map'];
         if(array_key_exists($key,$session_map_arr)){
            $session_map_arr[$key]=$map_val;
         }

         if($this->_is_log_set){
            $log_string="---*****updatemap  to   session array operation started*****------ \n";
            $this->_log_key_value_type("Session sec map array ","Key",$key,$log_string);
            $this->_log_key_value_type("Session sec map array","Sec map Value",$map_val,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }
     }

     /**
      * clear sec_map val from the sec_map key from the session array
      */

      public function clearmap($key){
          
        $session_map_arr=&$_SESSION['sec_map'];
        if (array_key_exists($key, $session_map_arr)) {
            $session_map_arr[$key]=null;
        }

        if($this->_is_log_set){
            $log_string="---*****clearmap  to   session array operation started*****------ \n";
            $this->_log_key_value_type("Session sec map array ","Key",$key,$log_string);
            fwrite($this->_session_log_handle,$log_string);

        }
      }

      /**
       * delete sec_map key from the session sec_map array
       * 
       */
      public function deletemap($key){
        $session_map_arr=&$_SESSION['sec_map'];
        if (array_key_exists($key, $session_map_arr)) {
            unset($session_map_arr[$key]);
        }

        if($this->_is_log_set){
            $log_string="---*****deletemap  to   session array operation started*****------ \n";
            $this->_log_key_value_type("Session sec map array ","Key",$key,$log_string);
            fwrite($this->_session_log_handle,$log_string);

        }
      }

      public function get_map_val($key){
        $session_map_arr=$_SESSION['sec_map'];
        $session_sec_map_val=null;
        if(array_key_exists($key,$_SESSION)){
          $session_sec_map_val=$session_map_arr[$key];
        }

        if($this->_is_log_set){
            $log_string="---*****get_map_val   from    session sec array  key operation started*****------ \n";
            $this->_log_key_value_type("Session sec map array ","Key",$key,$log_string);
            $this->_log_key_value_type("Session sec map array ","Sec Value Fetched",$session_sec_map_val,$log_string);
            fwrite($this->_session_log_handle,$log_string);
        }

        return $session_sec_map_val;
      }

      private function _log_key_value_type($type,$log_prop,$log_prop_val,&$log_string){
          $log_string.="*********$type $log_prop :- $log_prop_val*********** \n";
      }

      public function destroy(){
        if($this->_is_log_set){
            $log_string="---*****Session is about to be destroyed*****------ \n";
            fwrite($this->_session_log_handle,$log_string);
        }
        session_destroy();
      }

      public function session_exists(){
          if(isset($_SESSION["login_user"])){
             return true;
          }else{
             return false;
          }
      }

}



?>
