$(function(){
     $(Window).scroll(function(){
        var topo = $(Window).scrollTop();

        if(topo > 100)
        {
            $('#janela').fadeOut('1000');
        }
        else
        {
            $('#janela').fadeIn('1000');
        }


    });

})