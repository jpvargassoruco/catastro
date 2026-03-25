<?php

$table[0] = "satnombr";
$table[1] = "satinmus";
$table[2] = "satliqin";
$error = $error_reg = false;
$no_de_errores_reg = 0;
$i = 0;
while ($i < 3) {
   $file_path = "c:/wsiim98/data/";
   $file_name = $file_path.$table[$i].".dbf";

	 if (@dbase_open($file_name, 0)) { 
      pg_query("DELETE FROM $table[$i]");
      $dbf = @dbase_open($file_name, 0);
      $fields = dbase_get_header_info($dbf);
			echo $fields;
      $x = 0;
      $fields_num = 0;
      foreach($fields as $field)
      {
         if($x++ != 0)
         switch($field['type'])
         {
            case 'character' : $type = 'CHAR'; $length = $field['length'] > 1 ? "({$field['length']})" : ""; break;
            case 'number' : $type = 'NUMERIC'; $length = "({$field['length']}" . ($field['precision'] > 0 ? ", {$field['precision']})": ")");
            break;
         }
         $fields_num++;
      } 
      $records = @dbase_numrecords($dbf) or die("Error en leer el número de campos en el archivo DBF");
      for($x = 1; $x <= $records; $x++)
      {
         $record = dbase_get_record($dbf, $x);
         $sql = "INSERT INTO $table[$i] VALUES (";
         $f = 0;
         foreach($record as $field)
         {
            if($f != 0) $sql = $sql.", ";
            if(strcmp(str_repeat(' ', $fields[$f]['length']), $field) != 0)
            {
               if($fields[$f]['type'] == 'character') $sql = $sql."'";
						   $field = utf8_encode ($field);
               $sql = $sql.$field;
							 
               if($fields[$f]['type'] == 'character') $sql = $sql."'";
            }
            else
               $sql = $sql."NULL";
            if(++$f >= $fields_num) break;
         }
         $sql = $sql.");";
		
		     if (!pg_query($sql)) {
				 		  
			      $sql = substr ($sql, 27, strlen($sql)-27);   
			      $sql_reg[$no_de_errores_reg] = $sql;	 
			      $error_reg = true;
						$no_de_errores_reg++;
		     }
      }
   } else {
      $error = true;
   }
   $i++;
} #END_OF_WHILE

?>
