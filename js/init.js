//window.onload = init('s');
//window.onloadend = init('e');

function init(stat)
{
    var load=document.getElementById('loading');
    console.log(load);
    /*
     * s => start
     * e => end
     */
    if(stat==='e')
    {
        load.style.display='none';
    }
    else
    {
        load.style.display='block';
    }

 }

