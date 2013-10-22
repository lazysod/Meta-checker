<?php 
session_start();
include('includes/metaFunctions.php');
include('includes/rank.php.inc');
//

if(isset($_POST['urlSubmit']))
{
        
        $user_url = clean_input($_POST['url']);
        $user_url = rtrim($user_url,"/");
        header("location: result.php?url=$user_url");
}
if(!isset($_GET['url']))
{
        header('location: index.php');
}
else
{
        
        $s1 = 0;
        $url = clean_input($_GET['url']);
        
        $domain = 'http://'.$url;
        $domain = rtrim($domain,"/");
        $tags = get_meta_tags($domain);
        $ptitle = get_page_title('http://'.$url);
        if($ptitle =="")
        {
                $title_block='<img src="images/cross.png" alt="File Found Image" align="absmiddle"> No Title found';        
                
        }
        else 
        {
                $s1 = $s1+10;
                $title_block = $ptitle;        
        }
        if (urlcheck($domain) == false)
                {
                                
                                $error='<h2 align="center"><font color="#ff0000">Invalid URL: Try again</font></h2>';        
                                        
                }
         else
        {
                                $result = count($tags);
                                if(empty($result))
                                {
                                        $result='<img src="images/cross.png" alt="File Found Image" align="absmiddle"> No';        
                                }
                                $url_block .= '<a href="'.$domain.'" title="'.$ptitle.' homepage" target="_new">'.$url.'</a>';
                                $rank = GooglePageRankChecker::getRank($url); // Replace with your website url
                                if(empty($rank)){
                                        $rank='[ un-ranked ]';        
                                }
                                else
                                {
                                $rank_block='Your Google Rank is: '.$rank;
                                
                                }
                                $sitemap = remoteFileExists($domain.'/sitemap.xml');
                                if ($sitemap) {
                                        
                                        $sitemap_block='<img src="images/tick.png" alt="File Found Image" align="absmiddle"> Site map found';
                                        
                                        
                                } else {
                                
                                        $sitemap_block = '<img src="images/cross.png" alt="File Found Image" align="absmiddle"> Site Map not found';   
                                        
                                }
                        
                                $robots = remoteFileExists($domain.'/robots.txt');
                                if ($robots) {
                                        $score1 = $score1 +10;
                                        $robots_block='<img src="images/tick.png" alt="File Found Image" align="absmiddle"> Robots File Found';
                                        
                                } else {
                                        
                                        $robots_block= '<img src="images/cross.png" alt="File Not Found Image" align="absmiddle"> Robots File Not Found';   
                                        
                                }
                                
                // Images  Block 
                
                                        $domd = new DOMDocument();
                                        libxml_use_internal_errors(true);
                                        $domd->loadHTML(file_get_contents($domain));
                                        libxml_use_internal_errors(false);
                                        
                                        $items = $domd->getElementsByTagName("img");
                                        $data = array();
                                        $img_score = 0;
                                        foreach($items as $item) {
                                          $data[] = array(
                                                "src" => $item->getAttribute("src"),
                                                "alt" => $item->getAttribute("alt"),
                                                "title" => $item->getAttribute("title"),
                                          );
                                           
                                          
                                          $altTag = $item->getAttribute("alt");
                                                  if(empty($altTag))
                                                  {
                                                        $alt_block ='No Alt description found <img src="images/cross.png" alt="File NOT Found Image" align="abstop"> ';
                                                        
                                                  }
                                                  else
                                                  {
                                                        $alt_block =$altTag.' <img src="images/tick.png" alt="File NOT Found Image" align="abstop"> ';
                                                        
                                                  }
                                          $imageCount = count($data);
                                          
                                                  if($imageCount < 1)
                                                  {
                                                        $imageGFX = '<img src="images/cross.png" alt="File NOT Found Image" align="abstop"> ';        
                                            
                                                  }
                                                  else
                                                  {
                                                         $imageGFX = '<img src="images/tick.png" alt="File Found Image" align="abstop"> ';        
                                                         
                                                        
                                                  }
                                          
                                          $image_count = $imageGFX.count($data);
                                          $image_block.='<p>';
                                          $image_block.= 'Name: '.basename($item->getAttribute("src")).' <img src="images/tick.png" alt="File Found Image" align="abstop"><br>';
                                          $image_block.= 'Alt: '.$alt_block.'<br>';
                                          $image_block.='</p>';
                                        }
                // END IMAGES CHECK 
                
                // META TAG BLOCK 
                
                                $meta_score = 0;
                                $meta_block.= '<ul>';
                foreach ($tags as $key => $value) {
                                        $meta_score = $meta_score +10;
                                        $meta_block .= '<li class="correct"><strong>'.ucfirst($key).':</strong> '.$value.'</li>';
                                        
                                }
                                $meta_block.= '</ul>';
                                
                                $title = '<h3>Missing Tags</h3>';
                                
                                if(empty($tags['keywords']))
                                {
                                        $missing.= '<li class="missing">Keywords tag is missing</li>';
                                }
                                if(empty($tags['author']))
                                {
                                        $missing.=  '<li class="missing">Author tag is missing</li>';
                                }
                                if(empty($tags['description']))
                                {
                                        $missing.=  '<li class="missing">Description tag is missing</li>';
                                }
                                else
                                {
                                        $descLength = strlen($tags['description']);
                                        $advice_block.='<p>The Description is '.$descLength.' characters, most search engines use 160 characters</p>';        
                                        if($descLength > 160)
                                        {
                                                $overChar = $descLength - 160;
                                                $advice_block.='<p>You are '.$overChar.' over the suggested 160 character limit. <em>Perhaps consider revising?</em></p>';        
                                        }
                                        else if($descLength < 160)
                                        {
                                                $underChar = 160 - $descLength;
                                                $advice_block.='<p>You are '.$underChar.' under the suggested 160 character limit';
                                        }
                                        else
                                        {
                                                $advice_block.='<p>You are using 160 characters exactly';        
                                        }        
                                }
                                if(empty($tags['copyright']))
                                {
                                        $missing.=  '<li class="missing">Copyright tag is missing</li>';
                                }
                                if(empty($tags['robots']))
                                {
                                        $missing.=  '<li class="missing">Robots tag is missing</li>';
                                }
                
                
                // End Meta Tag Block        
                        
                }// end of main else
                
                $result = dns_get_record($url, DNS_ANY, $authns, $addtl);
        
        $count = sizeof($result);
        $i=0;
        $nameserver = array();
        $name_block.='<h3>Name Servers</h3>';
        $name_block.='<ol>';
                while($i < $count) 
        {
                $temp_array = $result[$i];
                
                if($temp_array['type'] == "NS") { array_push($nameserver, $temp_array ['target']); 
        }
        
                $i++;
        
        }
        $count = sizeof($nameserver);
        $i=0;
        
        while($i < $count) {
                   //This is just to show the contents of the nameserver array.
                $name_block.= "<li>$nameserver[$i]</li>";
                
                        $i++;
        
        }
        $name_block.='</ol>';
}// end isset
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Meta Checker</title>
<!--Meta tags go here -->

<link href="css/style1.css" rel="stylesheet" type="text/css" />
<style>
.inputbox {
        font-family: Verdana, Geneva, sans-serif;
        font-size: 18px;
        padding: 6px;
}
</style>
</head>

<body>
<div id="container3">
  <h1 align="center">Meta Tag Finder</h1>
  <div align="center">
    <form name="form1" method="post" action="">
  <label for="url"></label>
  
    <div align="center">
      <input name="url" type="text" class="inputbox" id="url" size="42" placeholder="Enter URL to check" required autofocus>
      <input type="submit" name="urlSubmit" id="urlSubmit" class="inputbox" value="Submit">
    </div>
  </form>
    <table width="900" border="0" align="center" cellpadding="6" cellspacing="8">
      <tr>
        <td valign="top"><?php echo $error; ?></td>
      </tr>
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td width="14%"><strong>URL: <br />
            </strong></td>
            <td width="86%"><?php echo $url_block; ?> <button type="button" onClick="window.open('http://lazysod.co.uk/metasniffer/whois.php?url=<?php echo $url; ?>','Alba Web Whois','width=600,height=460,menubar=no,location=no,status=no')">Check Whois</button></td>
          </tr>
          <tr>
            <td><strong>Title:<br />
            </strong></td>
            <td><?php echo $title_block; ?></td>
          </tr>
          <tr>
            <td><strong>Robots TxT:<br />
            </strong></td>
            <td><?php echo $robots_block; ?></td>
          </tr>
          <tr>
            <td><strong>Sitemap: </strong></td>
            <td><?php echo $sitemap_block; ?></td>
          </tr>
          <tr>
            <td><strong>Google Rank:</strong></td>
            <td><?php echo $rank; ?></td>
          </tr>
          <tr>
            <td valign="top"><strong>DNS Servers:</strong></td>
            <td><?php echo $name_block; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#333333" height="6"></td>
      </tr>
      <tr>
        <td><p><strong><?php echo $result; ?> meta tags found</strong></p>
        <p><?php echo $meta_block; ?></p><p><?php echo $missing; ?></p><p><?php echo $advice_block; ?></p></td>
      </tr>
      <tr>
        <td bgcolor="#333333" height="6"></td>
      </tr>
      <tr>
        <td><p><strong><?php echo $image_count; ?> Images Found</strong></p>
          <p>
            <?php echo $image_block; ?>
        </p></td>
      </tr>
      <tr>
        <td bgcolor="#333333" height="6"></td>
      </tr>
    </table>
   
  </div>

</div>
<div id="footer">Copyright <a href="http://www.barrysmith.org" title="My Homepage" target="_new">Barry Smith</a> <?php echo date('Y'); ?></div>
</body>
</html>