
addEvent(window, 'load', function()
{
	var counts = document.getElementsByClassName('maxlength');
	var i, count, matches, countHolder;
	
	for (i=0; i<counts.length; i++)
	{
		count = counts[i];	
		matches = count.className.match(/max_([0-9]+)/);
		count.maxVal = RegExp.$1;
		count.holder = document.getElementById(count.id + 'Count');
		if (count.holder)
		{
			count.holder.innerHTML = count.value.length;
			count.onkeyup = function()
			{
				if (this.value.length > this.maxVal)
					this.value = this.value.substring(0, this.maxVal);
	
				this.holder.innerHTML = this.value.length;		
			}
		}
	}	
});