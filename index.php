<?php
header("Content-Type: text/html; charset=ISO-8859-1");

$lang=" ".strtolower(@$_SERVER['HTTP_ACCEPT_LANGUAGE']);
if ((strpos($lang,"nl")>0)||(strpos($lang,"be")>0)) {$doelgroep=true;} else {$doelgroep=false;}

$campagne="";
$x=strpos($_SERVER['REQUEST_URI'],"/index.php/campagne=");
if ($x!==false) {
	list($dummy,$campagne)=explode("=",$_SERVER['REQUEST_URI']);
	$_SERVER['REQUEST_URI']="/index.php/campagne_verantwoording";
}

$x=strpos($_SERVER['REQUEST_URI'],"/index.php");
$q=strtolower(substr($_SERVER['REQUEST_URI'],$x+11)); // skip /index.php/

if (strpos($q,"#")>0) {$q=substr($q,0,strpos($q,"#"));}
if (strpos($q,"?")>0) {$q=substr($q,0,strpos($q,"?"));}
if ($q=="home") {$q="";}
if ($q=="") {$q="de_e-gulden";}
if ($q=="campagne_oeruschild") {$q.="_vijfde_etappe";} 
$blog=$q;

// Download markup broncode
if (substr($q,-4)==".txt") {
	if (file_exists("blg/$q")) {
		$blog=file_get_contents("blg/$q");
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($q));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . strlen($blog));
		die ($blog);
	}
}

$frame=file_get_contents("templates/frame.txt");
$visible=" style='display:block' ";
$invisible=" style='display:none' ";
$oerukoek="";  //dummy cookie
$title="e-Gulden";

$frame=str_replace("#1#","e-Gulden",$frame);

/*
 blg/*.txt markdown broncode
 obj/* directory met gecompileerde bestanden
*/
if (!file_exists("obj/$blog")&&(!file_exists("blg/$blog.txt"))&&(!file_exists("blg/$blog"))) {
	$blog="de_e-gulden";
	$frame=str_replace("<!-- Body -->","<!-- Body -->\n".file_get_contents("templates/home.txt"),$frame);	
}

if (file_exists("blg/$blog.txt")) {
	$compile=false;
	if (file_exists("obj/$blog")) {
		if (filemtime("obj/$blog")<filemtime("blg/$blog.txt")){$compile=true;}
	} else {$compile=true;}
	if ($compile){
		$org=file_get_contents("blg/$blog.txt");
		$txt=explode("\n",str_replace("\r","",$org));
		$modules=$txt[0];
		$titel=trim(sane_title($txt[1]));
		$start=2;
		// Markdown parser
		include "parseblog.php";
		file_put_contents("obj/".strtolower($titel),"$modules\n$titel\n$O");
	}
}

if (file_exists("obj/$blog")) {
	$txt=file_get_contents("obj/$blog");
}else{
	$txt=file_get_contents("blg/$blog");
}

// Als er geen <h1> tag in het artikel staat dan wordt die aangemaakt uit de tweede regel van de markdown
if (strpos($txt,"<h1")>0) {$h1=true;$txt=str_replace("<h1","<h1 style='color:#004466'",$txt);} else {$h1=false;}
$lines=explode("\n",$txt);$body="";
$title=str_replace("_"," ",$lines[1]);
if (!$h1) {$body="<h1 style='color:#004466'>".str_replace("_"," ",$lines[1])."</h1>";} 

for ($i=2;$i<count($lines);$i++) {$body.=$lines[$i]."\n";}
$blogplus=@file_get_contents("templates/blog.txt");
$lines[1]=strtolower($lines[1]);
$blogplus=str_replace("#",$lines[1],$blogplus);
if ($blog=="veel_gestelde_vragen") {
	$disqus=file_get_contents("templates/sm_disqus.txt");
	$disqus=str_replace("PAGE_IDENTIFIER","'$title'",$disqus);
	$disqus=str_replace("PAGE_URL","'https://e-gulden.org/index.php/$q'",$disqus);
	$body.=$disqus;
}
$frame=str_replace("#2#"," $body\n$blogplus",$frame);

$removeknop=true;
if ($blog=="de_e-gulden") {$removeknop=false;}
$frame=str_replace("#body#",$visible,$frame);
$frame=str_replace("#side#",$visible,$frame);				

// maximaal 5 sidemenus 
$test_home=trim($lines[0]);
if ($test_home!="") {
	if (substr($test_home,0,10)!="index_kort") {$lines[0]="index_kort;$test_home";}
}

// De eerste regel bevat de op te nemen menus en modules (komma sep., niet te interpreteren als markdown)
$menus=explode(";",$lines[0]);
$side=0;
foreach ($menus as $sidemenu) {
	if (($sidemenu!="")&&file_exists("blg/$sidemenu")&&(substr($sidemenu,0,6)!="module")) {
		$side++;
		if ($side==1) {$frame=str_replace("#side$side#",$visible,$frame);}
		$frame=str_replace("#sidemenu$side#",file_get_contents("blg/$sidemenu"),$frame);
	}
}
$test="";
foreach ($menus as $sidemenu) {
	if (substr($sidemenu,0,6)=="module") {
		if (file_exists("blg/$sidemenu")) {
			$frame=str_replace($sidemenu,file_get_contents("blg/$sidemenu"),$frame);
		} elseif (file_exists("feed/".substr($sidemenu,6))) {
			$frame=str_replace($sidemenu,file_get_contents("feed/".substr($sidemenu,6)),$frame);			
		}

	}
}

if ($side==0) {$frame=str_replace("#side#",$invisible,$frame);}
while ($side<5) {$side++;$frame=str_replace("#side$side#",$invisible,$frame);}

//Geen index dan hele zijbalk maar weg
if (trim($lines[0])=="") {
	$frame.="\n<script>\naside=document.getElementById('aside');\naside.parentNode.removeChild(aside);\ndocument.getElementById('content').className='span12'\n</script>\n";
}

// Topmenu
$frame=str_replace("#0#",file_get_contents("templates/eheader.txt"),$frame);

$title=strip_tags($title);
$frame=str_replace("#5",$title,$frame);
$title=str_replace("_"," ",$title);
$frame=preg_replace('/<title>.*<\/title>/',"<title>$title</title>",$frame);

if ($blog=="de_e-gulden") {
	// Drie componenten:
	// 1. blockchain statistiek om de minuut; verzorgt poll zelf
	// 2. Premine statistiek wordt hier max om het uur opgehaald
	// 3. Grafiek info wordt met een iframe in beeld gehaald; Deze verzorgt zelf de update max 1x per dag.
	$stamp=@file_get_contents("feed/stamp_all");
	if ($stamp!=date("dmY")) {
		file_put_contents("feed/all",file_get_contents("http://calc.egulden.org/feed_all.php"));
		file_put_contents("feed/stamp_all",date("dmY"));
	}
}elseif ($blog=="vrienden_van_de_e-gulden") {
	$stamp=@file_get_contents("feed/stamp_vrienden");
	if ($stamp!=date("dmYH")) {
		file_put_contents("feed/vrienden",file_get_contents("https://e-gulden.org/feed_vrienden.php"));
		file_put_contents("feed/stamp_vrienden",date("dmYH"));
	}
	if ($doelgroep) {
		$frame=str_replace("module_vrienden",file_get_contents("blg/module_vrienden"),$frame);
	} else {
		$frame=str_replace("module_vrienden",file_get_contents("blg/module_vriendenex"),$frame);
	}

	$frame=str_replace("feed_vrienden",file_get_contents("feed/vrienden"),$frame);
}elseif ($blog=="nieuwsbrieven") {
	if ($doelgroep) {
		$module_nieuwsbriefaanmelden=file_get_contents("blg/module_nieuwsbriefaanmelden");
	} else {
		$module_nieuwsbriefaanmelden=file_get_contents("blg/module_doelgroep");
	}
	$frame=str_replace("module_nieuwsbriefaanmelden",$module_nieuwsbriefaanmelden,$frame);
}elseif ($blog=="premine") {
	$module_premine=file_get_contents("blg/module_premine").file_get_contents("http://calc.egulden.org/feed_premine2.php");
	$frame=str_replace("module_premine",$module_premine,$frame);
}elseif ($blog=="e-gulden_conditie") {
	$stamp=@file_get_contents("feed/stamp_blockstat");
	if ($stamp!=date("dmYH")) {
		$blockstat=file_get_contents("http://calc.egulden.org/feed_blockstat.php");
		file_put_contents("feed/blockstat",$blockstat);
		file_put_contents("feed/stamp_blockstat",date("dmYH"));
	} else {$blockstat=file_get_contents("feed/blockstat");}
	$frame=str_replace("module_blockstat",$blockstat,$frame);	
}elseif ($blog=="campagnes") {
	$frame=str_replace("module_campagnes",file_get_contents("http://calc.egulden.org/feed_campagnes.php"),$frame);
}elseif ($blog=="e-gulden_spaarplan") {
	$frame=str_replace("module_spaarplan",file_get_contents("blg/module_spaarplan"),$frame);
}elseif ($blog=="campagne_verantwoording") {
	$frame=str_replace("module_campagne",file_get_contents("http://calc.egulden.org/feed_campagne.php?campagne=$campagne"),$frame);
}

// url adres Opschoonwerk
$frame=str_replace("https://e-gulden.org/index.php","index.php",$frame);
$frame=str_replace("https://e-gulden.org/images/","images/",$frame);
$frame=str_replace("src='/images/","src='images/",$frame);

echo $frame;

$ip=$_SERVER['REMOTE_ADDR'];
if (@$_SERVER['HTTP_X_FORWARDED_FOR']!="") {
	$w8ipx=explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
	$ip=$w8ipx[0];
}
if (strpos($ip,":")!==false) {
	$v6=explode(":",$ip);
	if (count($v6)==8) {
		if ( (strlen($v6[5])==4)&&(strlen($v6[6])==4)&&(strlen($v6[7])==4)) {
			$ip=(256+hexdec(substr($v6[6],0,2))).".".hexdec(substr($v6[6],3)).".".hexdec(substr($v6[7],0,2)).".".hexdec(substr($v6[7],3));
		} 
	}
}

if ($q=="") {$q="home";}
?>
<script>
<?php if ($removeknop) { ?>
try {document.getElementById('id_start').style.display='none'} catch(e){}
<?php } ?>
</script>

<?php
function getip() {
    $Ip = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') {
	$Ip = $_SERVER['HTTP_CLIENT_IP'];
    }   elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
	$Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
    	$Ip = $_SERVER['REMOTE_ADDR'];
    }
    if (($CommaPos = strpos($Ip, ',')) > 0) {$Ip = substr($Ip, 0, ($CommaPos - 1));}
    return($Ip);
}
function inschrijving($ip){}
function inschrijving_2($login){}
function inschrijving_3($login){}
function inschrijving_4($login){}
function inschrijving_5($login){}
function sane_date($txt) {
	$x=preg_replace('/[^0-9]/', ' ', $txt);
	return(substr(preg_replace('/\s+/', ' ',$x),0,8));
}
function sane_link($txt) {
	return(preg_replace('/[\"\'!$%^*+{} ]/', ' ', $txt));
}
function sane_title($txt) {
	$x=preg_replace('/[^a-zA-Z0-9\- ]/', ' ', $txt);
	$first=substr(preg_replace('/\s+/', ' ',$x),0,60);
	return(preg_replace('/\s/', '_',$x));
}

/* TODO
- mailadressen overhevelen van server19 naar e-gulden.org
> komen in- en uitschrijvingen aan op e-gulden.org?
> compile laatste mailing
> test nieuwe inschrijvingen en voeg toe aan compile
> Wis alles van e-gulden.org dat niet voorkomt in compile
> Voeg alles dat voorkomt in compile toe aan e-gulden.org
- cookie baseren op inlog (key+sign)
- op server: - login-dir = cookie>key
- op server: - mail-dir = mail>key
- op server: - inschrijving-dir = key>mail
- bepaal op basis van key:
>ambassadeur of nieuw
>saldo
>if ambassadeur: gekoppelde keys + saldo gekoppelde keys
*/
?>

