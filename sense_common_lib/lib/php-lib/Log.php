<?php

/**
 * Created by PhpStorm.
 * User: sense
 * Date: 5/4/18
 * Time: 7:34 PM
 */
namespace Sense{
    
    class Log{
        private $_logfile_name;
        private $_fle_handle;
        private $_php_filename;
        function __construct($logfile_pathname, $php_filename){
         //$this->_file_name=$_SERVER['DOCUMENT_ROOT']."/$folder_name/$this->_abs_fle_nme";
         //echo $logfile_pathname."<br>";
         //echo $php_filename."<br>";
         $this->_logfile_name=$logfile_pathname;
         $this->_php_filename=$php_filename;
        }

        function logfile_open($access_mode){
            //echo "logfile name".$this->_logfile_name."<br>";
            $this->_fle_handle=fopen($this->_logfile_name, $access_mode);
            $this->logfile_write("=========LOG START: " . date("Y-m-d H:i:s") . "==========".$this->_php_filename."\n");
        }

        function logfile_close(){
            $this->logfile_write("=========LOG END: " . date("Y-m-d H:i:s") . "==========".$this->_php_filename."\n\n\n\n");
            fclose($this->_fle_handle);
        }


        function logfile_write($content){
            try{
               // chmod($this->_file_name, 0777);
                fwrite($this->_fle_handle,$content);
                
            }catch (\Exception $e){
               echo "the log content writing not succesful \n";
            }

        }

        function logfile_writeline($content){
            try{
               // chmod($this->_file_name, 0777);
                fwrite($this->_fle_handle,$content."\n");
                
            }catch (\Exception $e){
               echo "the log content writing not succesful \n";
            }

        }


    }
}

?>
