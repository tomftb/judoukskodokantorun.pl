function getWidth()
{ 
    var windowsWidth=window.screen.width;
    return windowsWidth;
};
function getHeight(){ 
    var windowsHeight=window.screen.height;
    return windowsHeight;
}
function AlertFilesize()
{
    if(window.ActiveXObject)
    {
        var fso = new ActiveXObject("Scripting.FileSystemObject");
	var filepath = document.getElementById('fileInput').value;
	var thefile = fso.getFile(filepath);
	var sizeinbytes = thefile.size;
    }
    else
    {
	var sizeinbytes = document.getElementById('fileInput').files[0].size;
    }
    var fSExt = new Array('Bytes', 'KB', 'MB', 'GB');
    fSize = sizeinbytes; i=0;while(fSize>900){fSize/=1024;i++;}
    var myWindow = window.open("", "MsgWindow", "width=200,height=100");
    myWindow.document.write("<p>This is 'MsgWindow'. I am 200px wide and 100px tall!</p>");
    alert((Math.round(fSize*100)/100)+' '+fSExt[i]);
}

