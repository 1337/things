<?php
    require_once ('file.php'); // CSV is sort of supported by File.
	
	class CSVFile extends File {
		
		/*
		    This CSVFile class caches reads and writes (until you sort).
			Cache is VERTICAL:
			array (Field_1, Field_2, ...)
			         ||       ||
			        row_1    row_1
				    row_2    row_2
				    row_3    row_3
		    All fields have the same number of rows, making their UBounds the same.
			
			FYI, the CSVFile class is PHP5 only. In PHP4, it will read, but not write.
			Trying to write will, uh, give weird results.
			
		*/
		
		function is_assoc ($arr) {
			// something I got off http://php.net/manual/en/function.is-array.php
			// that tells you if an array is associative.
            return (is_array($arr) && (!count($arr) || count(array_filter(array_keys($arr),'is_string')) == count($arr)));
        }
		
		function GetRow () {
		    if ($this->filehandle) {
				return fgetcsv ($this->filehandle);
			}
		}
		
		function AddRow ($row) {
			// given an array of (name=>value, name=>value, ...), turn it into a CSV entry.
			if ($this->filehandle && is_array ($row)) {
				if (is_assoc ($row)) {
					/* array is associative. 
					    - detect current file column names
						- if no columns found (like if empty file), write columns, write row
						- if columns found, order array by columns, then write row
					*/
					
				} else {
					/* array is not associative.
					    write rows in their natural order, without checking corresponding column.
					*/
					fputcsv ($this->filehandle, $row);
				}
			}
		}
		
	}
?>