<?php
header('Access-Control-Allow-Origin: *');  
//error_reporting(0);
include 'dbfun.php';

$dbpost="0";
$dbopen="0";
$dbquery="0";
$dbqlogin="0";
$payload="0";  
$dbresponse="0";  
$dbexception="0";
try {
     $db    = connectToDB(0);
	 if ($db) {
				$dbopen= "Opened database successfully";
		}
		else{	$dbopen= "Error : Unable to open database";
			}
    if (isset($_POST['USERNAME'])&&isset($_POST['TOKEN'])) {

       
        $USERNAME = filter_var($_POST['USERNAME'],FILTER_SANITIZE_SPECIAL_CHARS);
        $TOKEN = filter_var( $_POST['TOKEN'],FILTER_SANITIZE_SPECIAL_CHARS);
        $dbpost="$USERNAME";
      
        if (userCheckToken($USERNAME,$TOKEN,$db,$dbquery,$dbresponse)) {
            $dbqlogin= "login successful";
            //gets bikehub data
                $sql = 'SELECT * FROM public."BIKETYPES" '
		 ;
                $ret = pg_query($db, $sql);
				 if (!$ret) {
                    echo pg_last_error($db);
                } else {
					$payload=pg_fetch_all($ret);
					$payload=json_encode($payload);
                }
            }
        else {
            $dbqlogin= "login failed";
        }
    } else {

        $dbpost= "No post ";
		if(isset($_POST['USERNAME']))
			$dbpost=$dbpost+    $USERNAME ;
		if(isset($_POST['TOKEN']))
						$dbpost=$dbpost+    $TOKEN ;


			
    }
}catch(Exception $e)   {
            $dbexception=  $e->getMessage();
    }
echo json_encode( 
    array( "dbresponse"=>"$dbresponse","dbpost"=>"$dbpost","dbopen"=>"$dbopen",
           "dbquery"=>"$dbquery","dbqlogin"=>"$dbqlogin",
           "payload"=>"$payload","dbexception"=>"$dbexception","dbtoken"=>"$dbtoken" )
     );
pg_close($db);
?>