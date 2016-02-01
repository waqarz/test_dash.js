<!DOCTYPE html>
<html>
<head>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>    
<script src="http://code.jquery.com/jquery-latest.js"></script>
<link rel="stylesheet" type="text/css" href="TestFramework.css">
</head>
<body> 
    <h2> Test Automation </h2>
<script>
    var resultDivNum = 0;
	var playerLink = "http://192.168.161.131/dash.js_wz/samples/dash-if-reference-player/index.html?url="
	var maxtests = 4;
	
	window.onload = function()
	{	
		 document.getElementById('vectors').value = <?php $file = file_get_contents( 'DefaultVectorList.txt' ); echo json_encode( $file ); ?>;
		 document.getElementById('statusContent').innerHTML="Ready";
	}
    
    function newtab(mpdfile)
    {
        var testWin = window.open(playerLink + mpdfile);
		//console.log("Opening: " + playerLink + mpdfile + ", conf: " + playerLink + ", MPD: " + mpdfile);
		//testWin.blur();
		return testWin;
    }
	
	function testing2()
    {
		document.getElementById('statusContent').innerHTML= "Running"
		var vectorstr = document.getElementById('vectors').value;
        if (vectorstr!='')
        {
            var vectors = vectorstr.split("\n");
            console.log(vectors);
        }
		
		var numTotalTests = 0;
		var openedTab = [];
		var newTabProcess;
		
		function checkNextLoad(tabToCheck)
		{
			if(tabToCheck.document.readyState === "complete")
			{
				loadWindows();
			}
		}
		
		function loadWindows()
		{
			if(openedTab.length < maxtests && numTotalTests < vectors.length)
			{
				var newTab = newtab(vectors[numTotalTests]);
				newTab.addEventListener('readystatechange', function() { checkNextLoad(newTab); }   , true);
				openedTab.push(newTab);
				numTotalTests++;
			}	
			else
				runTests();
		}
		
		function runTests()
		{
			if(numTotalTests < vectors.length)
			{
				for(index = 0 ; index < openedTab.length ; )
				{
					if(openedTab[index].closed)
					{
						openedTab.splice(index, 1);
					}
					else
						index++;
				}
				
				if(openedTab.length < maxtests)
				{
					loadWindows();
				}
				else
					setTimeout( function() { runTests(); }, 300 );
			}
			else
			{
				//window.clearTimeout(newTabProcess);
				document.getElementById('statusContent').innerHTML= "Completed"				
			}				
		}
		
		runTests();
		
		//newTabProcess = setInterval( function() { runTests(); }, 300 );
	}
    
</script>



<br>
<p id="Testvectors">Test vectors :</p><br>
<textarea name="Text1" cols="110" rows="20" id='vectors'></textarea>
<br><input type=button id="Start" value="Start Testing" onclick="testing2()">  
<div id="tick" style="position: absolute; left: 900px"></div>
<p id="status">Status :</p>
<p id="statusContent"></p>
<p id="results">Results :</p>
<input type="checkbox" id="Checkbox">
<p id="ChecboxTitle">Create Reference</p>
<p id="RefMsg"></p>
</body>

</html>

