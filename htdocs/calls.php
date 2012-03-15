 <?php
 
 $password = "ClueCon";
 $port = "8021";
 $host = "127.0.0.1";
 
 function event_socket_create($host, $port, $password) {
     $fp = fsockopen($host, $port, $errno, $errdesc) 
       or die("Connection to $host failed");
     socket_set_blocking($fp,false);
     
     if ($fp) {
         while (!feof($fp)) {
            $buffer = fgets($fp, 1024);
            usleep(100); //allow time for reponse
            if (trim($buffer) == "Content-Type: auth/request") {
               fputs($fp, "auth $password\n\n");
               break;
            }
         }
         return $fp;
     }
     else {
         return false;
     }           
 }
 
 
 function event_socket_request($fp, $cmd) {
    
     if ($fp) {    
         fputs($fp, $cmd."\n\n");    
         usleep(100); //allow time for reponse
         
         $response = "";
         $i = 0;
         $contentlength = 0;
         while (!feof($fp)) {
            $buffer = fgets($fp, 4096);
            if ($contentlength > 0) {
               $response .= $buffer;
            }
            
            if ($contentlength == 0) { //if contentlenght is already don't process again
                if (strlen(trim($buffer)) > 0) { //run only if buffer has content
                    $temparray = split(":", trim($buffer));
                    if ($temparray[0] == "Content-Length") {
                       $contentlength = trim($temparray[1]);
                    }
                }
            }
            
            usleep(100); //allow time for reponse
            
            //optional because of script timeout //don't let while loop become endless
            if ($i > 10000) { break; } 
            
            if ($contentlength > 0) { //is contentlength set
                //stop reading if all content has been read.
                if (strlen($response) >= $contentlength) {  
                   break;
                }
            }
            $i++;
         }
         
         return $response;
     }
     else {
       echo "no handle";
     }
 }
 
 $fp = event_socket_create($host, $port, $password);
 $cmd = "api show channels";
 $line = event_socket_request($fp, $cmd);

#     do
#        {
#		 $line = event_socket_request($fp, $cmd);
#               #$line = fgets($socket,1024);
#              #if (strstr($line, "mevotel"))
#             {
#                    //$wrets .= $line . "<br>";
                        list($t3,$t8,$t9,$t10,$t12,$t24,)= split('[, ]+',$line,24);
                        $wrets .= "<tr><td>". $t3. "</td><td>".$t8. "</td><td>" .$t9 . "</td><td>" .$t10 . "</td><td>" .$t12 . "</td><td>" .$t24 . "</td></tr>";
#
#         }
#               if (strstr($line, "--END COMMAND--"))
#                       break;
#    }
# while (!feof($fp) );

                $wrets2= $wrets;

    fclose($fp);
      echo $wrets . "<br>";
                echo "<table>\n";
                   echo "<tr bgcolor=grey>";
                  echo "<td>Created</td>";
                  echo "<td>cid_num</td>";
                 echo "<td>ip_addr</td>";
                echo "<td>dest</td>";
		echo "<td>application_data</td>";
		 echo "<td>callstate</td>";
               echo "</tr>";
                  echo $wrets2 . "<br>";
         echo "</table>\n";


 echo $line; 
# fclose($fp);  
 
 ?>

