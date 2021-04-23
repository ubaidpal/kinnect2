( function( $ ) {
$( document ).ready(function() {


$(".tabs-menu a").click(function(event) {
	event.preventDefault();
	$(this).parent().addClass("current");
	$(this).parent().siblings().removeClass("current");
	var tab = $(this).attr("href");
	$(".tab-content-main").not(tab).css("display", "none");
	$(tab).fadeIn();
});

  
$('#csssubmenu ul > li > a').click(function() {
  $('#csssubmenu li').removeClass('active');
  $(this).closest('#csssubmenu > ul > li').addClass('active');	
	  
	var checkElement = $(this).next();
  if((checkElement.is('#csssubmenu > ul')) && (checkElement.is(':visible'))) {
    $(this).closest('#csssubmenu > li').removeClass('active');
    checkElement.slideUp('normal');
  }
  
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('#csssubmenu ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});

$('.csssub > li > a').click(function() {
  	$('.csssub li').removeClass('active');
  	$(this).closest('li').addClass('active');	
});

$('#cssmenu > ul > li > a').click(function() {
  	$('#cssmenu li').removeClass('active');
  	$(this).closest('li').addClass('active');	
});

});
})( jQuery );
