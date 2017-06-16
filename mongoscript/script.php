<?php
if($_POST['action']=='updatedb'){
	$target_dir = "uploads/";
	$insert		=	true;
	$target_file = $target_dir . basename($_POST['filename']);	
	convertFormToJson($target_file,$insert);
}
$csvArr = files_import($_FILES, $url);

?>
<?php
global $returnArray;
$returnArray	=	array();
function files_import($FILES = array(), $posturl){
	global $returnArray;
	$dataArr= array();
	$row = 0;
	$csv_info= array();
	$mergedArr = array();
	
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["files"]["name"][0]);	
	$uploadOk = 1;
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

	if($fileType!=='csv') {
		$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		$returnArray['status']	=	'failure';
		$returnArray['message']	=	'Please Upload A CSV FILE';
		echo json_encode($returnArray); die;		
	}else{
		if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $target_file)) {
			//echo "The file ". basename( $_FILES["files"]["name"]). " has been uploaded.";
			$returnArray['status']	=	'success';
			$returnArray['files']['name']	=	$_FILES["files"]["name"][0];
			convertFormToJson($target_file);
		} else {
			echo $target_file;
			echo "Sorry, there was an error uploading your file.";
		}		
	}
	
	
}
function csvstring_to_array(&$string, $CSV_SEPARATOR = ';', $CSV_ENCLOSURE = '"', $CSV_LINEBREAK = "\n") { 
  $o = array(); 

  $cnt = strlen($string); 
  $esc = false; 
  $escesc = false; 
  $num = 0; 
  $i = 0; 
  while ($i < $cnt) { 
    $s = $string[$i]; 

    if ($s == $CSV_LINEBREAK) { 
      if ($esc) { 
        $o[$num] .= $s; 
      } else { 
        $i++; 
        break; 
      } 
    } elseif ($s == $CSV_SEPARATOR) { 
      if ($esc) { 
        $o[$num] .= $s; 
      } else { 
        $num++; 
        $esc = false; 
        $escesc = false; 
      } 
    } elseif ($s == $CSV_ENCLOSURE) { 
      if ($escesc) { 
        $o[$num] .= $CSV_ENCLOSURE; 
        $escesc = false; 
      } 

      if ($esc) { 
        $esc = false; 
        $escesc = true; 
      } else { 
        $esc = true; 
        $escesc = false; 
      } 
    } else { 
      if ($escesc) { 
        $o[$num] .= $CSV_ENCLOSURE; 
        $escesc = false; 
      } 

      $o[$num] .= $s; 
    } 

    $i++; 
  } 

//  $string = substr($string, $i); 

  return $o; 
} 
function correctKeys($dataArr){
	$returnArray		=	array();
	foreach($dataArr as $key=>$val){
		$temparray			=	$val;
		if(!isset($temparray['Size'])){
			if(isset($temparray['size'])){
				$temparray['Size']	=	$temparray['size'];
				unset($temparray['size']);
			}else{
				$temparray['Size']	=	'null';
			}
		}
		if(!isset($temparray['Wildcard'])){
			if(isset($temparray['wildcard'])){
				$temparray['Wildcard']	=	$temparray['wildcard'];
				unset($temparray['wildcard']);
			}else{
				$temparray['Wildcard']	=	'null';
			}
		}
		if(!isset($temparray['Paper'])){
			if(isset($temparray['paper'])){
				$temparray['Paper']	=	$temparray['paper'];
				unset($temparray['paper']);
			}else{
				$temparray['Paper']	=	'null';
			}
		}		
		if(!isset($temparray['weight'])){
			if(isset($temparray['Weight'])){
				$temparray['weight']	=	$temparray['Weight'];
				unset($temparray['Weight']);
			}else{
				$temparray['weight']	=	'null';
			}
		}
		if(!isset($temparray['Cover'])){
			if(isset($temparray['cover'])){
				$temparray['Cover']	=	$temparray['cover'];
				unset($temparray['cover']);
			}else{
				$temparray['Cover']	=	'null';
			}
		}		
		if(!isset($temparray['Page'])){
			if(isset($temparray['page'])){
				$temparray['Page']	=	$temparray['page'];
				unset($temparray['page']);
			}else{
				$temparray['Page']	=	'null';
			}
		}
		
		$returnArray[]				=	$temparray;
	}
	return $returnArray;
}
function fixArrayKey(&$arr)
{
    $arr = array_combine(
        array_map(
            function ($str) {
                return trim($str);
            },
            array_keys($arr)
        ),
        array_values($arr)
    );

    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            fixArrayKey($arr[$key]);
        }
    }
}
function removeUeff($str){
	return $str = preg_replace(
  '/
    ^
    [\pZ\p{Cc}\x{feff}]+
    |
    [\pZ\p{Cc}\x{feff}]+$
   /ux',
  '',
  $str
);
}
function convertFormToJson($target_file,$insert=false){
	global $returnArray;
	ini_set("auto_detect_line_endings", true);
if (($handle = fopen($target_file, "r")) !== FALSE) {
	
	$buffer = fgets($handle, 4096);
	$headers = csvstring_to_array($buffer, ',', '"', "\r");
	$headers = array_map('removeUeff', $headers);
		while (($buffer = fgets($handle, 4096)) !== false) {
			$data = csvstring_to_array($buffer, ',', '"', "\r");
			$num = count($data);
			$dataArr[]= array_combine($headers, $data);
		 }
		 $dataArr		=	correctKeys($dataArr);
		foreach($dataArr as $value => $datasingle){
			
			foreach($datasingle as $val => $data){
				if(!is_numeric($val)){	
					if($val=='Gloss'){
						continue;
					}else if($val=='sku'){
						$charval[$val] = $_REQUEST['sku'];
					}else{
					$charval[$val] = trim($data);
					}
				}else{
					$numericval[$val] = $data;
				}
			}
			$count = count($numericval);
			
			foreach($numericval as $number => $numval){
				if($numval==null || $numval=="null" || $numval=='N/A' || $numval=='n/a' || $numval=='na' ) {continue;}
				$logicArr1 = array('quantity' => $number);
				$logicArr2 = array('price' => $numval);
				$array = $charval+$logicArr1+$logicArr2;
				$mergedArr[$value][] = $charval+$logicArr1+$logicArr2;
				
			}
		}
		
		fclose($handle);
		if(!$insert){
			$returnArray['output']	=	array_slice($mergedArr,0,2);
			echo json_encode($returnArray);die;
		}	
		$res	=	curl_mongopost($mergedArr);
		echo $res;die;
		return $return;
		//you can return $mergedArr to process it as json
	}	
}

function curl_mongopost($mergedArr){
	//$apiData		=	json_encode($mergedArr);
	$requestUrl		=	'http://138.68.1.67:3000/api/productsflats/replaceOrCreate';
    if (!function_exists('curl_init')){
        die('Adios! No curl found');
    }
   foreach($mergedArr as $key=>$val){ 
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $requestUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($val));
		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array();
		$headers[] = "Content-Type: application/json";
		$headers[] = "Accept: application/json";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
			curl_close ($ch); 
			die;
		}
		curl_close ($ch);
		//post is returned you can return $postoutput (curl result)
	}
	return $result;
}

?>
