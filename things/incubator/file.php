<?php
    // PHP file interaction class (C) 2011 Brian Lai
 
    class File {
        public $filename;
        public $filehandle; // r+ is chosen by default because it's both read and write
        public $openmode; // codes from: http://ca2.php.net/manual/en/function.fopen.php
     
        function __construct ($filename = '', $openmode = 'r+') {
            if (is_file ($filename)) {
                $this->Open ($filename, $openmode);
            }
        }
     
        function File ($filename = '', $openmode = 'r+') {
            $this->__construct ($filename, $openmode);
        }
     
        function GetFile () {
            return $this->filename;
        }
     
        function Open ($filename, $openmode = 'r+') {
         
            // close existing opened file first (if any).
            if ($this->filehandle) {
                $this->Close ();
            }
         
            $this->filename = $filename;
            $this->openmode = $openmode;
            $this->filehandle = fopen ($filename, $this->openmode);
        }
     
        function Close () {
            // close the file handle.
            fclose ($this->filehandle);
            $this->filehandle = null;
            $this->filename = null;
            $this->openmode = '';
        }
     
        function GetMode () {
            return $this->openmode;
        }
     
        function SetMode ($openmode = 'r+') {
            // "change"s the current file mode to the one specified.
            // it will surely reset the file pointer.
            $fn = $this->filename; // copy value
            $this->SetFile ($fn, $openmode);
        }

        function GetLine () {
            // get a line from the current file pointer.
            // PHP decides what "a line" is.
            if ($this->filehandle) {
                return fgets($this->filehandle);
            }
            return false;
        }
     
        function GetContent () {
            // gets all lines from the file pointer.
            if ($this->filehandle) {
                $content = null;
                while ($buffer = $this->GetLine() && $buffer !== false) {
                    $content .= $buffer;
                }
                return $content;
            }
        }

        function SetContent ($content) {
            // true on success, false on failure.
            // if called multiple times with mode 'w', the content is added.
            // if called with mode 'a', the content is always added.
            if ($this->filehandle) {
                try {
                    fwrite ($this->filehandle, $content);
                } catch (Exception $e) {
                    die ("cannot write file");
                }
            }
        }
     
        function Seek ($position) {
            fseek ($this->filehandle, $position);
        }
     
        function Delete () {
            // well, deletes the file. false on failure.
            $res = false;
            $this->Close ();
            if (strlen ($this->filename) > 0) {
                $res = unlink ($this->filename);
            }
            return $res;
        }
    }
 
?>