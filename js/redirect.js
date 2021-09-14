function redirect(page,timeout)
{ 
    console.log('---redirect()---\nPAGE => '+page+'\nTIMEOUT => '+timeout);
    timeout=parseInt(timeout,10)
    setTimeout(document.location=page, timeout);
}


