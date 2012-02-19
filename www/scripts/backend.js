$(function()
{

    $('a', '#logout').click(function()
    {
        return confirm('Are you sure you want to log out?');
    });

    $(document).ready(function()
    	{
        $('.delete_image').live('click', function(){
            var self = $(this);
            var rel = self.data('rel');
            var image = "#PhotoPrev"+rel;
            var del = "#delete-"+rel;
            var fil = "#file-"+rel;
            $.get(self.data('href'), {}, function(data){
            	$(image).attr('src','');
            	$(del).hide();
            	$(fil).val('');
            });
        });
    	}
    );
    
});