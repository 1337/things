<?php
    
    //FTP connection class
    // not written by me
    
    class FTP{
        var $server='';
        var $username='';
        var $password='';
        var $port=21;
        var $remote_dir='';
        var $tmp_dir = '/fsys1/www/creativeservices.uwaterloo.ca/_files/temp/';
        var $upload_dir="incoming/_ftpuploads/";
        var $download_dir="public/_ftpdownloads/";
        var $conn = '';
       
            //contructor (not necessary, just convention)
            function FTP($server, $username='anonymous', $password='', $port=21){
                $this->server=$server;
                $this->username=$username;
                $this->password=$password;
                $this->port=$port;
            }
           
            //send to ftp site
            function send($source='', $destination='', $passive=TRUE){
                $conn=$this->return_connection() or die;
                @ftp_pasv($conn, $passive);
                $this->set_remote_dir(ftp_pwd($conn));
                    if(!@ftp_put($conn, $this->remote_dir.$destination, $source, FTP_BINARY)){
                        @ftp_quit($conn);
                        return false;
                    }else{
                        @ftp_quit($conn);
                        return true;
                    }
                return true;
            }
            
            //retrieve from ftp site to the server      
            function retrieveToServer($source='', $destination='', $passive=TRUE){
               
                $conn=$this->return_connection() or die;
                @ftp_pasv($conn, $passive);
                $this->set_remote_dir(ftp_pwd($conn));
                    if(!@ftp_get($conn, $destination, $this->remote_dir.$source, FTP_BINARY)){
                        @ftp_quit($conn);
                        return false;
                    }else{
                        @ftp_quit($conn);
                        return true;
                    }
            }
            
            //download from ftp site to local machine
            function retrieveToLocal($source='', $name='', $passive=TRUE){
                set_time_limit(0);  
                $conn=$this->return_connection() or die;
                @ftp_pasv($conn, $passive);
                $this->set_remote_dir(ftp_pwd($conn));
                $location = "ftp://".$this->server.$this->remote_dir.$source;
                if($name == ''){
                    $name = basename($location);
                    echo $name;
                }
                output_file($location, $name);
                @ftp_quit($conn);
            }
            
            //Delete from FTP site
            function delete($location, $passive=TRUE){
                $conn=$this->return_connection() or die;
                @ftp_pasv($conn, $passive);
                $this->set_remote_dir(ftp_pwd($conn));
                if (!ftp_delete($conn, $this->remote_dir.$location)) {
                    @ftp_quit($conn);
                    return false;
                }else {
                    @ftp_quit($conn);
                    return true;
                }
            }
            
            //directory listing
            function dir($directory="", $passive=TRUE){
                $conn=$this->return_connection() or die;
                @ftp_pasv($conn, $passive);
                $this->set_remote_dir(ftp_pwd($conn));
                $directory = $this->remote_dir.$directory;
                if($contents = ftp_nlist($conn, $directory)){
                    @ftp_quit($conn);
                    foreach($contents as $content){
                        $output .= $content."<br/>";
                    }
                    return $output;
                }else{
                    @ftp_quit($conn);
                    return "Error reading directory";
                }
                
            }
            
            // Disconnect server
            function kill(){
                    if($this->conn)
                        @ftp_quit($this->conn);
                unset($this);
            }
            
            // Connect to server
            function return_connection(){
                $conn_id = @ftp_connect($this->server, $this->port) or die("Could not connect to FTP");
                $login_result = @ftp_login($conn_id, $this->username, $this->password) or die("Could not login to FTP");
                $this->conn = $conn_id;
                return $conn_id;
            }
           
            //Instantiate the remote directory
            function set_remote_dir($dir){
                $x = substr($dir, (strlen($dir)-1));
                    if($x != "/" && $x != "\\")
                        $dir.="/";
                $this->remote_dir=$dir;
            }          
    }
?>