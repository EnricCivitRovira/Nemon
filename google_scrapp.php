<html>
<head>
<h1>Google Results</h1>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
function delete_urls(){
    $.ajax({
        url:"delete_urls.php",
        type:"GET"
    });

    alert("Url Deleted, Execute a New Search");

    $.ajax({
        url:"delete_urls.php",
        type:"GET"
    });

}
</script>
</head>
<style type="text/css">
.buttonRed {
  background-color: #f44336; /* Green */
  border: none;
  color: white;
  padding: 3px 2px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
	
</style>
<table border="1" width="90%">
   <tr> <td><b>URL</b></td>
        <td><b>Results</b></td>

<?php
	
	include('simple_html_dom.php');

	$term = $_GET['search'];
	$url = 'http://www.google.com/search?q='.urlencode($term).'';
	$scrape = file_get_contents_curl($url);

	$domResult = new simple_html_dom();
	$domResult ->load($scrape);

	#We Use this foreach to get the instant class of the url's, this class changes thru the time and the search.
	foreach ($domResult->find('a[href^=/url?]') as $block) {		
		foreach($block->getElementsByTagName('div') as $div) {
			$notprint = 0;
			foreach ($div->getElementsByTagName('div') as $style) {
				$notprint = 1;
			}
			foreach ($div->getElementsByTagName('span') as $span) {
				$notprint = 1;
			}
			foreach ($div->getElementsByTagName('img') as $img) {
				$notprint = 1;
			}
			if($notprint == 0){
				if(strpos($div, 'www.')){
					$exploded = explode("\"", $div);
					$class = "\"" .$exploded[1] . "\"";					
					break;
				}
			}				
		}
	}		

	$mysqli = new mysqli("localhost", "root", "", "nemon");

	if ($mysqli->connect_errno) {
    	echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	$i = 0;
	foreach ($domResult->find('a[href^=/url?]') as $block) {		
		foreach($block->getElementsByTagName('div') as $div) {
			if(strpos($div, $class)){
				$webAndDescription[$i] = explode(">", $div);
				$link[$i] = explode(" ",$webAndDescription[$i][1]);			
				$url = $link[$i][0];

				$query = "CALL nemon.insertUpdateUrl('" . $url . "')";				
				if (!$mysqli->query($query)) {
    				echo $mysqli->errno;
				}
			}
		}
	}	

	$query = 'CALL nemon.getUrls()';

	if ($result_set = $mysqli->query($query))
   {
      printf('');
      while($row=$result_set->fetch_object())
      {
         printf("<tr><td>%s</td><td>%s</td></tr>\n",
                  $row->Url, $row->UrlCount);
      }
   }
   else // Query failed - show error
   {
      printf("<p>Error retrieving stored procedure result set:%d (%s) %s\n",
             mysqli_errno($mysqli),mysqli_sqlstate($mysqli),mysqli_error($mysqli));
      $mysqli->close();
      exit();
   }  

	function file_get_contents_curl($url) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}
?>
</table>
		<button  onclick="location.href = 'web.html';" type="button">New Search</button>  
		<button  onclick="delete_urls()" type="button" class="buttonRed" >Delete Urls</button>  
	</body>
</html> 