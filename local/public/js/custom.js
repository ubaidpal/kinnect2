$(document).ready(function() {

/*Left and Rigt panel Stick = Start*/
function fixMe(id) {
    var e = $(id);
    var lastScrollTop = 0;
    var firstOffset = e.offset().top;
    var lastA = e.offset().top;
    var isFixed = false;
    $(window).scroll(function (event){
        if (isFixed) {
            return;
        }
        var a = e.offset().top;
        var b = e.height();
        var c = $(window).height() -50;
        var d = $(window).scrollTop();
        if (b <= c - a) {
            e.css({position: "fixed"});
            isFixed = true;
            return;
        }			
        if (d > lastScrollTop){ // scroll down
            if (e.css("position") !== "fixed" && c + d >= a + b) {
                e.css({position: "fixed", bottom: 50, top: "auto"});
            }
            if (a - d >= firstOffset) {
                e.css({position: "absolute", bottom: "auto", top: lastA});
            }
        } else { // scroll up
            if (a - d >= firstOffset) {
                if (e.css("position") !== "fixed") {
                    e.css({position: "fixed", bottom: "auto", top: firstOffset});
                }
            } else {
                if (e.css("position") !== "absolute") {
                    e.css({position: "absolute", bottom: "auto", top: lastA});
                }               
            }
        }
        lastScrollTop = d;
        lastA = a;
    });
}

fixMe("#stick");
fixMe("#stickAdd");
/*Left and Rigt panel Stick = End*/

/*
>>>>>>  Feedback-left Script == Starts
*/

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

/*
>>>>>>  Leaderboard-tabs Script == Starts
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

/*Profile Tooltip*/
	var h = false;
	$("#profileLink").click(function(){
		if (h == false){
			$("#popUp").fadeIn('fast');
			$("#popUpText").fadeIn(function(){h = true;});
		}
		if (h == true){
			$("#popUp").fadeOut('fast');
			$("#popUpText").fadeOut(function(){h = false});
		}
	});
});
  