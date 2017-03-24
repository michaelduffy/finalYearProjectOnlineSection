$(document).ready(function()
{
	$("#dobId").datepick(
	{
		dateFormat: 'yyyy-mm-dd',
		minDate: new Date(1926, 1-1, 1)
	});
	
   /* $("#id1").mouseenter(function()
	{
		alert("You entered id1!");
		$(this).css("background-color", "#cccccc");
        });*/
});