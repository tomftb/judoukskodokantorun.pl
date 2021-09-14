<head>
<title>JUDO UKS Kodokan Toruń</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="judo, uks, sport" >
<meta name="description" content="Strona całkowicie poświęcona klubowi UKS KODOKAN Toruń" >
<meta name="viewport" content="width=device-width">
<?php
define('DR',filter_input(INPUT_SERVER,"DOCUMENT_ROOT"));
$HTTP_HOST=$_SERVER['HTTP_HOST'];
$SERVER_NAME=$_SERVER['SERVER_NAME'];
$SERVER_PORT=$_SERVER['SERVER_PORT'];
$SERVER_FULL_FULL="HTTP://".$SERVER_NAME.":".$SERVER_PORT;
$DOCUMENT_ROOT=filter_input(INPUT_SERVER,"DOCUMENT_ROOT");
$css="/galeria.css";
$css_lokalizacja="css/";
echo '<link rel="stylesheet" href="'.$SERVER_FULL_FULL.'/'.$css_lokalizacja.$css.'?'.uniqid().'" type="text/css">'; 
?>
<link rel="shortcut icon" href="<?php echo $SERVER_FULL_FULL.'/'; ?>images/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo $SERVER_FULL_FULL.'/'; ?>images/favicon.ico" type="image/x-icon">
<script>
var galleryItemParm=[];
var galleryItems=[];
var divImgDimension=new Array();
var imgSource="";
var $iCounter=0;

function windowClose()
{
    window.close();
}

function boldText(id)
{
     document.getElementById("closeWindow").style.fontWeight = "bold";
     document.getElementById("closeWindow").style.cursor = "pointer";
}

function normalText(id)
{
     document.getElementById("closeWindow").style.fontWeight = "normal";
}

function counter()
{
	var tmpMarginTop="";
	$iCounter++;
	if(galleryItems.length<$iCounter)
	{
		//alert("Galery item lenght lower than counter");
		$iCounter=0;
	}
	if(galleryItems.length>$iCounter)
	{
		//alert("");
	}
	else
	{
		$iCounter=0;
	};
	//alert(galleryItems[$iCounter][0]+","+galleryItems[$iCounter][1]+","+galleryItems[$iCounter][2]+",DIV W:"+divImgDimension[0]+"DIV H: "+divImgDimension[1]);
	document.getElementById("nrZdjecie").innerHTML = "Aktualne zdjęcie - " + $iCounter;
	document.getElementById("myImg").style.marginLeft = "0px";
	tmpMarginTop= Math.round((divImgDimension[0]-galleryItems[$iCounter][2])/2);
	document.getElementById("myImg").style.marginTop = tmpMarginTop+"px";
	document.getElementById("myImg").src = imgSource+""+galleryItems[$iCounter][0];
}
function counterMinus()
{
	var tmpMarginTop="";
	$iCounter--;
	if($iCounter<0)
	{
		//alert("set - "+ galleryItems.length);
		$iCounter=galleryItems.length-1;
	};
	document.getElementById("nrZdjecie").innerHTML = "Aktualne zdjęcie - " + $iCounter;
	tmpMarginTop= Math.round((divImgDimension[0]-galleryItems[$iCounter][2])/2);
	
	document.getElementById("myImg").style.marginTop = tmpMarginTop+"px";
	document.getElementById("myImg").src = imgSource+""+galleryItems[$iCounter][0];
};

function setGalleryItem(imgLocation,imgParm,maxImgDimension)
{
	var index;
	var tmpNameObj="img";
	var tmpArrayParm=[];
	imgSource=imgLocation;
	divImgDimension=maxImgDimension.split(',');

	galleryItemParm=imgParm.split(';');
	
	for (index = 0; index < galleryItemParm.length; ++index)
	{
		
		if(galleryItemParm[index]!='')
		{
			tmpArrayParm=galleryItemParm[index].split(',');
			if(tmpArrayParm.length==3)
			{
				galleryItems[index]=[tmpArrayParm[0],tmpArrayParm[1],tmpArrayParm[2]];
				//alert(tmpArrayParm[0]+" - "+tmpArrayParm[1]+" - "+tmpArrayParm[2]);
			}
		};
	};
}

</script>
</head>


<!-- onload="getDataXml()"-->
<?php	
echo "asdasdsa<br/>";
$ciastko="";
require(DR."/.cfg/konfiguracja.php");
require(DR."/class/logToFile.php");
require(DR."/class/database.php");
require(DR."/pod_strony/_funkcje_/licznik_odwiedzin.php");	

$db= NEW database();
$db->loadDb();

			$ID_REK=filter_input(INPUT_GET,"ID");
			$TABLE=filter_input(INPUT_GET,"TB");
                        
			if(preg_match('/^[a-zA-Z]+$/',$TABLE))
			{
				try
                                {
                                    if($TABLE==='KLASA')
                                    {
                                        $imgDir='klasa_sp';
                                        $TABLE_IMG="ID_".$TABLE;
                                        $WSK_U='WSK_U';
                                        $NAZW_I='NAZW_I';
                                    }
                                    else if($TABLE==='OBOZ')
                                    {
                                        $imgDir='obozy';
                                        $TABLE_IMG="ID_".$TABLE."U";
                                        $WSK_U='WSK_US';
                                        $NAZW_I='NAZWA_IMG';
                                    }
                                    else
                                    {
                                        $imgDir='';
                                    }
				$REK_DATA=mysqli_fetch_row($db->query("SELECT `OPIS`,`KATALOG` FROM ".$TABLE." WHERE ID=".$ID_REK));	
				$REK_DATA_2=mysqli_fetch_row($db->query("SELECT MAX(`WIDTH`),MAX(`HEIGHT`) FROM `".$TABLE."_IMG` WHERE `".$TABLE_IMG."`=".$ID_REK." AND `".$WSK_U."`=0"));	
				$REK_DATA_2[1]=$REK_DATA_2[1]-225; // max width height of windows div img
				$SEL_REK=$db->query("SELECT `".$NAZW_I."`,`WIDTH`,`HEIGHT` FROM `".$TABLE."_IMG` WHERE `".$TABLE_IMG."`=".$ID_REK." AND `".$WSK_U."`=0");
				
				
				$imgParm="";
				$imgTmpData=array();
				$maxWidth=0;
				$maxHeight=0;
				$firstImg=array(false,'imgName','width','height');
				
					while($REK_DATA_4=mysqli_fetch_array($SEL_REK))
					{
						$imgTmpData=getimagesize("HTTP://".$_SERVER["HTTP_HOST"]."/zdjecia/".$imgDir."/".$REK_DATA[1]."/".$REK_DATA_4[0]);
						$imgParm.=$REK_DATA_4[0].','.$imgTmpData[0].','.$imgTmpData[1].';';
                                                if($maxWidth<$imgTmpData[0]) {$maxWidth=$imgTmpData[0];};
                                                if($maxHeight<$imgTmpData[0]) {$maxHeight=$imgTmpData[0];};
						if($firstImg[0]==false)
						{
                                                    $firstImg[0]=true;
                                                    $firstImg[1]="HTTP://".$_SERVER["HTTP_HOST"]."/zdjecia/".$imgDir."/".$REK_DATA[1]."/".$REK_DATA_4[0];
                                                    $firstImg[2]=$imgTmpData[0];
                                                    $firstImg[3]=$imgTmpData[1];
						};
						
					};
				
				//foreach()
				
			}
			catch(Exception $e)
			{
				//echo " catch " ;
				echo $e->getMessage()."<br/>";
			}
			}
			else
			{
				// WRONG TABLE
				$maxHeight=0;
				$maxWidth=0;
				$firstImg=array("","","");
			}
			
			?>
<?php
if($SEL_REK)
{
?>
	<body onload="setGalleryItem('<?php echo $SERVER_FULL_FULL."/zdjecia/".$imgDir."/".$REK_DATA[1]."/"; ?>','<?php echo $imgParm; ?>','<?php echo $maxWidth.','.$maxHeight; ?>')">
	<div class="D_MAIN">
		<div class="D_NG" style="min-width:<?php echo $maxWidth; ?>px;"><p class="p_naglowek">
			<?php echo $REK_DATA[0]; ?><span id="closeWindow" class="close" onclick="windowClose()" onmouseover="boldText('closeWindow')" onmouseout="normalText('closeWindow')">Zamknij X</span></p></div>
			<div class="D_IMG" style="background-color: black;width:<?php echo $maxWidth."px"; ?>;height:<?php echo $maxHeight."px"; ?>;"><center>
			<img id="myImg" src="<?php echo $firstImg[1]; ?>" alt="brak foto" style="margin-top:<?php echo round((($maxHeight-$firstImg[3])/2),0);?>px"/>
			</center></div>
			<div class="D_INFO">
			<p id="nrZdjecie">Aktualne zdjęcie - 0</p>
			</div>
			<div class="przyciski" style="position: fixed; bottom: 0; width:100%">
			<button class="IMG_BUTTON" style="width:50%;" onclick="counterMinus()">Poprzednie</button>
			<button class="IMG_BUTTON" style="width:50%;"  onclick="counter()">Następne</button>
		</div>
	</div>
	</body>
<?php
}
?>