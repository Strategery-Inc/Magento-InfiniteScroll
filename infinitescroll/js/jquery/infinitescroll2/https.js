if("https:" == document.location.protocol)
{
	var child;
	jQuery('.pages ol li').each(function(index)
	{
		child = jQuery(this).children().first();
		if(child.attr('href'))
		{
			child.attr('href',child.attr('href').replace('http://','https://'));
		}
	});
}