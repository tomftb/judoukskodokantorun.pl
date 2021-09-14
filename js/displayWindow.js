function displayWindow(url, width, height) 
{
		var left = (screen.width-width)/2;
		var top = (screen.height-height)/2;
        var Win = window.open(url,"displayWindow",'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + 'resizable=0,scrollbars=yes,menubar=no' );
};


