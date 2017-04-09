$(document).ready(function()
{	//adding datepicker to input
	$("#dobId").datepick(
	{
		dateFormat: 'yyyy-mm-dd',
		minDate: new Date(1926, 1-1, 1)
	});
	
});