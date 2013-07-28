<?

function clean_input($data){
	
	$badwords = array("http://", "https://");
 	$data = str_replace($badwords, "", "$data");
 	trim($data); 
	return $data;
}

function get_name($data){
	
	$sql ="SELECT * FROM users WHERE id='$data'";
	$results= mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($results);
	
	$name = $row['name'];
	return $name;
	
}

function is_it_wordpress($url){
	
	$curl = curl_init($url);
 curl_setopt($curl, CURLOPT_NOBODY, true);
 //Check connection only
 $result = curl_exec($curl);
 //Actual request
 $ret = false;
 if ($result !== false) {
  $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  //Check HTTP status code
  if ($statusCode == 200) {
   $ret = true;   
  }
 }
 curl_close($curl);
 return $ret;
}	


function remoteFileExists($url) {
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

        if ($statusCode == 200) {
            $ret = true;   
        }
    }

    curl_close($curl);

    return $ret;
}


function urlcheck($url) {

	$c=curl_init();
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_HEADER,1);//get the header
    curl_setopt($c,CURLOPT_NOBODY,1);//and *only* get the header
    curl_setopt($c,CURLOPT_RETURNTRANSFER,1);//get the response as a string from curl_exec(), rather than echoing it
    curl_setopt($c,CURLOPT_FRESH_CONNECT,1);//don't use a cached version of the url
    if(!curl_exec($c)){
       // echo $url.' inexists';
        return false;
    }else{
       // echo $url.' exists';
        return true;
    } 
}
// get title
function get_page_title($domain){

	if( !($data = file_get_contents($domain)) ) return false;

	if( preg_match("#<title>(.+)<\/title>#iU", $data, $t))  {
		return trim($t[1]);
	} else {
		return '<strong>Not Found</strong>';
	}
}	


?>