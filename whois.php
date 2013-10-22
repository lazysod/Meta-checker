<?php 
$url = strip_tags($_GET['url']);
$url = stripslashes($url);
$url = trim($url);
//
function whois_query($domain) {
 
    // fix the domain name:
    $domain = strtolower(trim($domain));
    $domain = preg_replace('/^http:\/\//i', '', $domain);
    $domain = preg_replace('/^www\./i', '', $domain);
    $domain = explode('/', $domain);
    $domain = trim($domain[0]);
    $_domain = explode('.', $domain);
    $lst = count($_domain)-1;
    $ext = $_domain[$lst];
 
    $servers = array(
        "biz" => "whois.neulevel.biz",
        "com" => "whois.internic.net",
        "us" => "whois.nic.us",
        "coop" => "whois.nic.coop",
        "info" => "whois.nic.info",
        "name" => "whois.nic.name",
        "net" => "whois.internic.net",
        "gov" => "whois.nic.gov",
        "edu" => "whois.internic.net",
        "mil" => "rs.internic.net",
        "int" => "whois.iana.org",
        "ac" => "whois.nic.ac",
        "ae" => "whois.uaenic.ae",
        "at" => "whois.ripe.net",
        "au" => "whois.aunic.net",
        "be" => "whois.dns.be",
        "bg" => "whois.ripe.net",
        "br" => "whois.registro.br",
        "bz" => "whois.belizenic.bz",
        "ca" => "whois.cira.ca",
        "cc" => "whois.nic.cc",
        "ch" => "whois.nic.ch",
        "cl" => "whois.nic.cl",
        "cn" => "whois.cnnic.net.cn",
        "cz" => "whois.nic.cz",
        "de" => "whois.nic.de",
        "fr" => "whois.nic.fr",
        "hu" => "whois.nic.hu",
        "ie" => "whois.domainregistry.ie",
        "il" => "whois.isoc.org.il",
        "in" => "whois.ncst.ernet.in",
        "ir" => "whois.nic.ir",
        "mc" => "whois.ripe.net",
        "to" => "whois.tonic.to",
        "tv" => "whois.tv",
        "ru" => "whois.ripn.net",
        "org" => "whois.pir.org",
        "aero" => "whois.information.aero",
        "nl" => "whois.domain-registry.nl",
		"uk" => "whois.nic.uk",
		"it" => "whois.nic.it"
    );
 
    if (!isset($servers[$ext])){
        die('Error: No matching nic server found!');
    }
    $nic_server = $servers[$ext];
    $output = '';

    if ($conn = fsockopen ($nic_server, 43)) {
        fputs($conn, $domain."\r\n");
        while(!feof($conn)) {
            $output .= fgets($conn,128);
        }
        fclose($conn);
    }
    else { die('Error: Could not connect to ' . $nic_server . '!'); }
 
    return $output;
}

$content = whois_query($url);

if(empty($content))
{
	$content = 'Sorry: No matching nic server found!';	
}

?>
<h1>Alba Web Whois</h1>
<pre>
<?php echo $content; ?>
<pre>