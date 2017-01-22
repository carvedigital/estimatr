$(document).ready(function () {

    addPagePadding();

    $(window).on('resize',function(){
        addPagePadding();
    });

});

function addPagePadding()
{
    $('body').css('padding-bottom',parseInt( $('footer').outerHeight() ));
}