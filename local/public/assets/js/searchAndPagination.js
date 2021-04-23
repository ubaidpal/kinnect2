/**
 * Created by Admin on 26-1-16.
 */

var searchTypeCheck;
var loader       = $('#loading-content');
var unAuthorized = 'Unauthorized.';
$(window).on('hashchange', function(){
    if(window.location.hash){
        var page = window.location.hash.replace('#', '');
        if(page == Number.NaN || page <= 0){
            return false;
        }else{
            //  getPosts( page );
        }
    }
});
$(document).ready(function(){
    $(document).on('click', '.pagination a, .pagination span', function(e){
        e.preventDefault();
        var $this  = $(this);
        var find   = $(document).find(".banner-links");
        var userId = 0;
        if(find.length){
            userId = find.data('user')
        }

        var target_wrapper = $('.paginate-data');
        var url            = target_wrapper.data('url');
        if(userId != 0){
            url = $this.parent().parent().parent().data('url')
        }
        $('.pagination li').removeClass('active');
        $(this).parent('li').addClass('active');
        var btnUrl = $(this).attr('href');

        if(btnUrl.toLowerCase().indexOf("search") >= 0){
            var field  = $('#' + searchTypeCheck + ' .search-peoples');
            var type   = $(field).data('type');
            var userId = $(field).data('userid');
            var value  = $(field).val();
            var data   = {srchType : type, userId : userId, key : value};
            var page   = btnUrl.split('page=')[1]
            var url    = '/search-friends?page=' + page;
            getData(data, url)
        }else{
            if(e.target.tagName == 'SPAN'){
                var page = $(this).text();
            }else{
                var page = btnUrl.split('page=')[1];
            }
            getPosts(url, page, userId, $this);
        }

    });
});
function getPosts(url, page, userId, $this){
    loader.show();
    var target_wrapper = $('.paginate-data');
    $.ajax({
        url : url + '?page=' + page + '&userId=' + userId, dataType : 'html',
    }).done(function(data){
        if(data == unAuthorized){
            window.location.reload();
        }
        if(userId != 0){
            $this.parent().parent().parent().html(data)
        }else{
            target_wrapper.html(data);
        }
        $("html, body").animate({ scrollTop: 0 }, "slow");
        loader.hide();
        location.hash = page;
    }).fail(function(data){
        loader.hide();
        if(data == unAuthorized){
            window.location.reload();
        }
        alert('Something goes wrong, Please try again');
    });
}

// Search
var searchCount = 0;
$(document).ready(function(){
    $(document).keypress(function(e){
        if(e.which == 13){
            loader    = $('#loading-content');
            var field = $('.search-peoples');
            if(field.is(':focus')){
                var focusedField = document.activeElement;
                var type         = $(focusedField).data('type');
                var userId       = $(focusedField).data('userid');
                var value        = $(focusedField).val();
                searchTypeCheck  = type;
                if(value != '' || searchCount != 0){
                    searchCount = 1;
                    var data    = {srchType : type, userId : userId, key : value};
                    var url     = '/search-friends';
                    getData(data, url)
                }
            }
        }
    });

    $(document).keyup(function(e){
        var field = $('.search-peoples');
        if(field.is(':focus')){
            var focusedField = document.activeElement;
            var value        = $(focusedField).val();
            if(value == '' && searchCount != 0){

                var type        = $(focusedField).data('type');
                var userId      = $(focusedField).data('userid');
                searchTypeCheck = type;
                searchCount     = 0;
                var data        = {srchType : type, userId : userId, key : value};
                var url         = '/search-friends';
                getData(data, url)
            }
        }
    })
});

function getData(values, url){
    loader.show();
    $.ajax({
        type : 'POST', url : URL + url, data : values, success : function(data){
            if(data == unAuthorized){
                window.location.reload();
            }
            $('#' + values.srchType + ' .paginate-data').html(data);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            loader.hide();
        }, error : function(data){
            if(data == unAuthorized){
                window.location.reload();
            }
            loader.hide();
            alert('Something goes wrong, Please try again');
        }
    })
}


