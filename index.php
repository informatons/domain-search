<!DOCTYPE html>
<html>

<head>
<title>Domain Search</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="style.css" type="text/css" media="all" />
</head>

<body>

<h2>Domain Search</h2>

<?php 
/* muhammed zaim*/

$definitions = array(
"com" => array("whois.crsnic.net","No match for"),
"net" => array("whois.crsnic.net","No match for"),				
"org" => array("whois.pir.org","NOT FOUND"),					
"co.uk" => array("whois.nic.uk","No match"),
"mobi" => array("whois.dotmobiregistry.net","NOT FOUND")
);

function printForm()
{
global $keyword,$ext,$definitions;

$action = htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES);
$keyword = str_replace(" src", "", strtolower($keyword));
/* muhammed zaim*/
print <<<ENDHTM
<form method="post" action="$action">
<p>Start by entering a keyword.</p>
<p><input type="text" name="keyword" value="$keyword" /></p>
<p><input type="submit" value="Submit" /></p>
</form>

ENDHTM;
}


if(isset($_POST['keyword']) && strlen($_POST['keyword']) > 0)
{
$keyword = $_POST['keyword'];

$keyword = preg_replace('/[^0-9a-zA-Z\-]/','', $keyword);

	if(strlen($keyword) < 2)
	{
	print "<p class=\"error\">Error: The keyword \"$keyword\" is too short.</p>\n";
	printForm();
	exit(print "</body></html>\n");
	}
	if(strlen($keyword) > 63)/* muhammed zaim*/
	{
	print "<p class=\"error\">Error: The keyword is too long. Max 63 characters. You have ". strlen($keyword) ." characters.</p>\n";
	printForm();
	exit(print "</body></html>\n");
	}
	if(!preg_match("/^[a-zA-Z0-9\-]+$/", $keyword))
	{
	print "<p class=\"error\">Error: Keyword cannot contain special characters.</p>\n";
	printForm();
	exit(print "</body></html>\n");
	}
	if(preg_match("/^-|-$/", $keyword))/* muhammed zaim*/
	{
	print "<p class=\"error\">Error: Keywords cannot begin, or end with a hyphen.</p>\n";
	printForm();
	exit(print "</body></html>\n");
	}

	printForm();
	print "<table cellspacing=\"0\" class=\"data\">\n";

	foreach($definitions as $key => $value)
	{
	$ext = $key;
	$server = $definitions[$ext][0];
	$nomatch = $definitions[$ext][1];

		if(!$server_conn = @fsockopen($server, 43))
		{
		print "<tr><td class=\"short error\">Error</td><td class=\"error\">Could not connect to whois server at: ". $server ."</td></tr>\n";
		}
		else
		{
		$response = "";

		fputs($server_conn, "$keyword.$ext\r\n");

			while(!feof($server_conn))
			{
			$response .= fgets($server_conn, 128);/* muhammed zaim*/
			}

		fclose($server_conn);

			if(preg_match("/$nomatch/", $response))
			{
			print "<tr><td class=\"short\">Available</td><td>$keyword.$ext</td></tr>\n";/* muhammed zaim*/
			}
			else
			{
			print "<tr><td class=\"short\">Registered</td><td><a href=\"http://$keyword.$ext\">http://$keyword.$ext</a></td></tr>\n";
			}
		}
	}

print "</table>";
}
else {
printForm();
}/* muhammed zaim*/

?>

</body>
</html>

