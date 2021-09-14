function displayWindow(url)
{
        var Win = window.open(url+'?SC_WIDTH='+getWidth()+'&SC_HEIGHT='+getHeight(),'_blank');
};
	function getHeight()
	{ 
		var windowsHeight=window.screen.height;
		return windowsHeight;
	}
	function getWidth()
	{ 
		var windowsWidth=window.screen.width;
		return windowsWidth;
	};

