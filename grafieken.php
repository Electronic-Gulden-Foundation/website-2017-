<html><body>
<script type="text/javascript" src="https://www.google.com/jsapi?"></script>
<script>
      google.load("visualization", "1", {packages:["corechart"]});      
      google.setOnLoadCallback(drawcharts);
      function drawcharts() {drawchart5();}


function drawchart5() {
      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Datum');
      data.addColumn('number', 'EFL');
      data.addRows([
			<?
			// feed/all wordt door index.php geleverd
			$timeline=file("feed/all",FILE_IGNORE_NEW_LINES);
			$n=count($timeline);$sep="";
			for ($i=0;$i<$n;$i++) {
			      $line=$timeline[$i];
			      list($d_,$efl_,$nlg_,$ltc_,$btc_,$dummy)=explode(";",$timeline[$i]);
			      if ($d_=="04-07-2014") {break;}
			}
			$start=$efl_*$btc_;
			for ($i=$i;$i<$n;$i++) {
				$line=$timeline[$i];
				list($d,$efl,$nlg,$ltc,$btc)=explode(";",$line);
				if (is_numeric($efl)&&is_numeric($btc)){
				    $xefl=1000*($efl*$btc);
				    $d="new Date(".substr($d,6).",".(substr($d,3,2)-1).",".substr($d,0,2).")";
				    echo $sep."[$d,$xefl]";
				    $sep=",";				    
				}
			}
			?>
        ]);
        
        var options = {
	     curvetype: 'function',
            title: 'Europrijs 1000 EFL',
            hAxis: {title: 'Datum', format: 'MMM yy'},
	    trendlines: {0:{color:'red'}}
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }

</script>
<div id=chart_div></div>
<div id=chart_div2></div>
<div id=chart_div3></div>
<div id=chart_div4></div>
</body></html>
