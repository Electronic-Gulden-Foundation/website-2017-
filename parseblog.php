<?php

	$valid=explode("|","br|p|h1|h2|h3|h4|li|ul|u|b|i|hr|ol|center|intro|groen|rood|blauw|ZWART|GROEN|ROOD|BLAUW|markergeel|markerrood|markergroen|markerblauw");
	$O="";$style="";$i=0;
	for ($i=$start;$i<count($txt);$i++) {
		$line=trim($txt[$i]);
		if (substr($line,0,1)=="_"){
			$O.="<p><u>".substr($line,1)."</u><br>\n";
		}else{
			$O.=$line."\n";
		}
	}

	foreach ($valid as $tag) {
		$O=str_replace("<$tag>","[-$tag-]",$O);
		$O=str_replace("</$tag>","[-/$tag-]",$O);
	}
	$O=str_replace("<","",$O);
	$O=str_replace(">","",$O);
	foreach ($valid as $tag) {
		$O=str_replace("[-$tag-]","<$tag>",$O);
		$O=str_replace("[-/$tag-]","</$tag>",$O);
	}

	$O=str_replace("<rood>","<span style='color:red'>",$O);		$O=str_replace("</rood>","</span>",$O);
	$O=str_replace("<groen>","<span style='color:green'>",$O);	$O=str_replace("</groen>","</span>",$O);
	$O=str_replace("<blauw>","<span style='color:blue'>",$O);	$O=str_replace("</blauw>","</span>",$O);
	$O=str_replace("<ZWART>","<span style='color:black;font-weight:bold'>",$O);	$O=str_replace("</ZWART>","</span>",$O);
	$O=str_replace("<ROOD>","<span style='color:red;font-weight:bold'>",$O);	$O=str_replace("</ROOD>","</span>",$O);
	$O=str_replace("<GROEN>","<span style='color:green;font-weight:bold'>",$O);	$O=str_replace("</GROEN>","</span>",$O);
	$O=str_replace("<BLAUW>","<span style='color:blue;font-weight:bold'>",$O);	$O=str_replace("</BLAUW>","</span>",$O);
	$O=str_replace("<markerrood>","<span style='background-color:#FF8080'>",$O);	$O=str_replace("</markerrood>","</span>",$O);
	$O=str_replace("<markergroen>","<span style='background-color:#00FF00'>",$O);	$O=str_replace("</markergroen>","</span>",$O);
	$O=str_replace("<markerblauw>","<span style='background-color:#00FFFF'>",$O);	$O=str_replace("</markerblauw>","</span>",$O);
	$O=str_replace("<markergeel>","<span style='background-color:#FFFF00'>",$O);	$O=str_replace("</markergeel>","</span>",$O);
	$O=str_replace("<intro>","<div style='background-color:bisque;border: 1px solid #ccc;padding: 4px 6px;margin-bottom: 9px;border-radius: 3px;'>",$O);	$O=str_replace("</intro>","</div>",$O);
	
	$x=strpos($O,"{");
	while ($x!==false) {
		$y=strpos($O,"}",$x);
		if ($y!==false) {
			if ($x!=0) {$N=substr($O,0,$x);} else {$N="";}
			$a=explode(",",substr($O,$x+1,$y-$x-1));$w="";$alt="";
			if (isset($a[2])) {
				if (is_numeric($a[2])) {$w=" width={$a[2]} ";}
			}
			if (isset($a[3])) {$alt=" alt='".sane_title($a[3])."' ";}
			if (@$a[1]!="") {
				$N.="<a href='".sane_link($a[1])."' target='_blank'><img $w src='".sane_link($a[0])."' ".$alt."></a>".substr($O,$y+1);
			} else {
				$N.="<img $w src='".sane_link($a[0])."' ".$alt.">".substr($O,$y+1);
			}
			$O=$N;
		} else {
			$O=substr($O,0,$x-1);
		}
		$x=strpos($O,"{");
	}
	
	$x=strpos($O,"[");
	while ($x!==false) {
		$y=strpos($O,"]",$x);
		if ($y!==false) {
			if ($x!=0) {$N=substr($O,0,$x);} else {$N="";}
			$a=explode(",",substr($O,$x+1,$y-$x-1));
			$a[]="?";
			$N.="<a href='".sane_link($a[0])."'>{$a[1]}</a>".substr($O,$y+1);
			$O=$N;
		} else {$O=substr($O,0,$x-1);}
		$x=strpos($O,"[");
	}
	$O=faq($O);
	
function faq($faq) {

	$txt="";$i=0;
	$lines=explode("\n",$faq);
	$start=false;
	foreach ($lines as $line) {
		$line=trim($line);
		if (substr($line,0,2)=="#-"){
			$txt.="<div name=like></div></div>\n";
			$start=false;
		}elseif (substr($line,0,1)=="#"){
			if ($start){$txt.="<div name=like></div></div>\n";}
			$start=true;
			$i++;
			$line=substr($line,1);
			$style="";
			if (substr($line,0,1)=="#"){$line=substr($line,1);} else {$style="style='display:none'";}
			$txt.="<div name='faq' onclick='faq(this)'><a style='cursor:pointer' itemprop=url>\n<h4>$line</h4>\n</a></div>\n<div name='answer' $style><p>";
		}else{
			$txt.=$line."\n";
		}
	}	
	return($txt);
}
	
?>