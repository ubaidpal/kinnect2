$(document).ready(function() {

$('.feedback-img').click(function() {
    if($(this).css("margin-left") == "300px")
    {
        $('.feedback-left').animate({"margin-left": '-=300'});
        $('.feedback-img').animate({"margin-left": '-=300'});
        $(".feedback-img").delay().queue(function(){
	    	$(this).removeClass("active").dequeue();
		});
    }
    else
    {
        $('.feedback-left').animate({"margin-left": '+=300'});
        $('.feedback-img').animate({"margin-left": '+=300'});

        $(".feedback-img").delay().queue(function(){
	    	$(this).addClass("active").dequeue();
		});
    }
  });
/*
>>>>>>  Feedback-left Script == Ends
*/


/*
>>>>>>  Leaderboard Script == Starts
*/
  $('.leaderboard-img').click(function() {
    if($(this).css("margin-right") == '220px')
    {
        $('.leaderboard').animate({"margin-right": '-=220'});
        $('.leaderboard-img').animate({"margin-right": '-=220'});
        $(".leaderboard-img").delay().queue(function(){
        $(this).removeClass("active").dequeue();
    });
    }
    else
    {
        $('.leaderboard').animate({"margin-right": '+=220'});
        $('.leaderboard-img').animate({"margin-right": '+=220'});

        $(".leaderboard-img").delay().queue(function(){
        $(this).addClass("active").dequeue();
    });
    }
  });
/*
>>>>>>  Leaderboard Script == Ends
*/


/*>>>>>>  Leaderboard-tabs Script == Starts
*/
  $(".lb-tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).addClass("lb-btn-current");
        $(this).siblings().removeClass("lb-btn-current");
        var tab = $(this).attr("href");
        $(".lb-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
/*
>>>>>>  Leaderboard-tabs Script == Ends
*/


/*
>>>>>>  Leaderboard-tabs Script == Starts
*/
  $("span.sorting_list_bg li").click(function(event) {
        event.preventDefault();
        $(this).addClass("sort_active_link");
        $(this).siblings().removeClass("sort_active_link");
        var tab = $(this).attr("href");
        $(".lb-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
/*
>>>>>>  Leaderboard-tabs Script == Ends
*/

});
  