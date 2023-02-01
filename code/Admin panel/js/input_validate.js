function onlyDecimalNumeric(e, t) 
{	 
    try 
	{
        if (window.event) 
		{
            var charCode = window.event.keyCode;
        }
        else if (e) 
		{
            var charCode = e.which;
        }
        else { return true; }
        if(charCode == 46)
        {
        	var string = t.value;
        	dotCounter = 0;
        	for(var i=0;i<string.length;i++)
        	{
        		if(string.charAt(i) == ".")
        		{
        			dotCounter++;
        		}
        	}
        	if(dotCounter<1)
        		return true;
        	else
        		return false;
        }
        else if (charCode >= 48 && charCode <= 57 || charCode <= 13)
        {    return true;	}
        else
		{	return false;	}
    }
    catch (err) 
	{
        alert(err.Description);
	}
}
function onlyNumeric(e, t) 
{	 
    try 
	{
        if (window.event) 
		{
            var charCode = window.event.keyCode;
        }
        else if (e) 
		{
            var charCode = e.which;
        }
        else { return true; } 
        if ((charCode >= 48 && charCode <= 57) || charCode <= 13)
        {    return true;	}
        else
		{	return false;	}
    }
    catch (err) 
	{
        alert(err.Description);
	}
}
function onlyAlphabets(e, t) {
    try 
    {
        if (window.event) 
        {
            var charCode = window.event.keyCode;
        }
        else if (e) 
        {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode <= 13)
            return true;
        else
           return false;
    }
    catch (err) 
    {
        alert(err.Description);
    }
}
function onlyAlphaNumeric(e, t) 
{
    try 
    {
        if (window.event) 
        {
            var charCode = window.event.keyCode;
        }
        else if (e) 
        {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode < 31 || (charCode >= 48 && charCode <= 57) || charCode <= 13)
            return true;
         else
            return false;
    }
    catch (err) 
    {
        alert(err.Description);
    }
}
function onlyAlphaSpace(e, t){
    try 
    {
        if (window.event) 
        {
            var charCode = window.event.keyCode;
        }
        else if (e) 
        {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode <= 13)
            return true;
        else
           return false;
    }
    catch (err) 
    {
        alert(err.Description);
    }
}