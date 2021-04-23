/**
 * Created by Admin on 11-11-15.
 */
window.URL = $('.header-main').data('url');
var btn_box = $('.p_btn_div');
var loading = $('#loading');
var tab_data = '';
function toDo($this, $tab, $target){
    $tab.removeClass('active');
    $this.addClass('active');
    //if ($this.is("[data-ajax]")) {
    $('.target').addClass('hide');
    //}
    $('#' + $target).removeClass('hide');
}

$(document).ready(function(){


    /*
     * Tabs
     *
     * @Description
     *
     * Add 'tab' and 'data-target' attributes to button.
     * In 'data-target' put 'id' of targeted block that will be shown on click.
     * Add 'target' and 'hide' class to target block. and also add 'id' that placed in 'data-target'
     *
     * */
    var $tab = $('.tab');
    $(document).on('click', '.tab', function(e){
        e.preventDefault();
        var $target = $(this).data('target');

        //if element has data-ajax, then send ajax request
        if($(this).is("[data-ajax]")){
            loading.show();
            var $isAjax  = $(this).data('ajax');
            var $user_id = $(this).parent().data('user');
            var $url     = $(this).parent().data('url');
            ajax($isAjax, $target, $user_id, $url);
        }
        toDo($(this), $tab, $target);
    });
});

function ajax($isAjax, $target, $user_id, $url){
    if($isAjax == true){
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
        if($('#' + $target).is(':empty')){
            $.ajax({
                type : 'POST',
                url : URL + $url,
                data : {template : $target, userId : $user_id},
                cache : true,
                dataType : 'html',
                success : function(data){
                    if(data == 'Unauthorized.'){
                        window.location.href = 'auth/login';
                    }
                    loading.hide();
                    $('#' + $target).html(data);
                }
            });
        }else{
            loading.hide();
        }
    }
};

$(window).load(function(){
    //if(window.location.hash){
    if(tab_data){
        var $tab     = $('.tab');
        var hash     = tab_data;//window.location.hash.substring(1);
       var target   = $("[data-target=" + hash + "]");

        var $user_id = target.parent().data('user');
        var $url     = target.parent().data('url');
        ajax(true, hash, $user_id, $url);
        toDo($(this), $tab, hash);
        target.addClass('active');
    }else{
        // Fragment doesn't exist
    }
});

$(function(){

    var appendthis = ("<div class='modal-overlay js-modal-close'></div>");

    $('span[data-modal-id]').click(function(e){
        e.preventDefault();
        var img = $(this).siblings('img');
        $('#photo_id').val(img.data('id'));
        var imgSrc = img.attr('src');
        $('#photo-in-popup').attr('src', imgSrc);
        $('#title').val($(this).data('title'));
        $('#description').val($(this).data('description'));

        $('#delete-url').attr('href', $(this).data('url'));
        var album_photo      = $('#popup1').data('photo');
        var current_photo_id = $(this).data('id');
        if(album_photo === current_photo_id){
            $('#cover-photo').prop('checked', true);
        }else{
            $('#cover-photo').prop('checked', false);
        }
        $('#add-photo').click(function(){
            $('#file-btn').trigger('click');
        });

        $('#file-btn').change(function(){
            $('#submit-photo').submit();
        });
        $("body").append(appendthis);
        $(".modal-overlay").fadeTo(500, 0.7);
        //$(".js-modalbox").fadeIn(500);
        var modalBox = $(this).attr('data-modal-id');
        $('#' + modalBox).fadeIn($(this).data());
    });

    $(".js-modal-close, .modal-overlay").click(function(){
        $(".modal-box, .modal-overlay").fadeOut(500, function(){
            $(".modal-overlay").remove();
        });

    });

    $(window).resize(function(){
        $(".modal-box").css({
            top : ($(window).height() - $(".modal-box").outerHeight()) / 3,
            left : ($(window).width() - $(".modal-box").outerWidth()) / 2
        });
    });

    $(window).resize();

    $('#add-photo').click(function(){
        $('#file-btn').trigger('click')
    })

    $('#file-btn').change(function(){
        $('#submit-photo').submit();
    });

    $('#delete-url').click(function(e){
        e.preventDefault();
        if(confirm('Are you sure?')){
            window.location = $(this).attr('href');
        }
    })

});

$(document).ready(function(){
    $(".friend-toggle").on('click', function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle(url, $this);
    });
    $(".friend-toggle-requests").on('click', function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle_requests(url, $this);
    });
    var btn = $('.friend-toggle');
    $('.profile-content').on('click', ".friend-toggle", function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle(url, $this);
    });
    btn_box.on('click', ".friend-toggle", function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle(url, $this);
    });
    //Remove Recommended people from list

    $('.btn-delet').click(function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
    });
});

$(document).ready(function(){

    $('.trigger').on('click', function(event){

        event.stopPropagation();

        $(this).find('.drop').toggle();

    });
    btn_box.on('click', ".trigger", function(event){
        event.stopPropagation();

        $(this).find('.drop').toggle();
    });
    $(document).click(function(){

        $('.drop').hide();

    });
    /*$("#trigger").hover(
     function () {
     $('#drop').slideDown('medium');
     },
     function () {
     $('#drop').slideUp('medium');
     }
     );*/
});
function friend_toggle_requests(url, $this){
    var id = url.substring(url.lastIndexOf('/') + 1);
    $this.text('Please Wait...');
    $this.attr('href', '');
    $.ajax({
        type : 'GET', url : url, success : function(data){
            if(data == 'success'){
                $this.parent().parent().remove();
            }
        }
    });
}
function friend_toggle(url, $this){
    var id = url.substring(url.lastIndexOf('/') + 1);
    $this.text('Please Wait...');
    $this.attr('href', '');
    $.ajax({
        type : 'GET', url : url, success : function(data){
            if(data == 'success'){
                if(url.indexOf('add-friend') != - 1){
                    url = url.replace('add-friend', 'delete');
                    $this.text('Cancel Request');
                    $this.attr('href', url);
                    if($this.hasClass('is_refresh')){

                    }
                }else if(url.indexOf('confirm') != - 1){

                    /* $this.remove();
                     $this.sibling('.friend-toggle' ).remove();*/
                    $('.p_btn_div > .friend-toggle').remove();
                    btn_box.prepend('<span class="trigger-wrapper trigger"><a class="" href="#"><span class="check droptip">Friends</span></a><div class="drop" style="display: none;"><a class="friend-toggle" href="' + URL + '/friends/unfriend/' + id + '">Un Friend</a></div></span>')

                }else if(url.indexOf('delete') != - 1){

                    $this.prev('.friend-toggle').remove();
                    if(! $this.hasClass('noToggleBtn')){
                        $this.text('Add Kinnector');
                        url = url.replace("delete", "add-friend");
                        $this.attr('href', url);
                    }else{
                        $this.remove();
                    }
                }else if(url.indexOf('unfriend') != - 1){
                    /*$this.siblings().remove();
                     $this.parent().append('<a href="'+window.URL+'/friends/add-friend/" class="friend-toggle">'+
                     '<span class="check"> Add Friend</span>'+
                     '</a>');
                     $this.remove();*/
                    $this.parent().parent().remove();
                    if(! $this.hasClass('noToggleBtn')){
                        btn_box.prepend('<a class="friend-toggle" href="' + URL + '/friends/add-friend/' + id + '">' + ' <span class="check"> Add Friend</span> ' + '</a>');
                    }

                }else if(url.indexOf('unfollow') != - 1){
                    id = id.replace("unfollow", "follow");
                    $this.parent().parent().remove();
                    btn_box.prepend('<a class="friend-toggle" href="' + URL + '/' + id + '">' + '<span class="check">Follow</span>' + '</a>')

                }else if(url.indexOf('follow') != - 1){
                    id = id.replace("follow", "unfollow");
                    $this.remove();
                    btn_box.prepend('<span class="trigger-wrapper trigger">' + '<a class="" href="#">' + '<span class="check droptip">Following</span>' + '</a><div class="drop">' + '<a class="friend-toggle" href="' + URL + '/' + id + '">' + 'Un Follow' + '</a>' + '</div>' + '</span>');
                }else{
                    $this.text('Add Friend');
                    url = url.replace("delete", "add-friend");
                    $this.attr('href', url);
                }
            }else{
                //$this.text( 'Send Request' );
                //$this.attr( 'href', url );
            }
        }
    });
}
function un_follow(brand_id, ele){

    if($(ele).html() == 'Please wait..') return false;
    $(ele).html('Please wait..');
    $('#brand_' + brand_id).remove();
    var dataString = "brand_id=" + brand_id;
    $.ajax({
        type : 'GET', url : URL + '/unfollow', data : dataString, success : function(response){
            // $this.parent().remove();
            //window.location.reload();
        }
    });
}//un_follow(brand_id)
function remove_follower(user_id, ele){

    if($(ele).html() == 'Please wait..') return false;
    $(ele).html('Please wait..');
    $('#brand_' + user_id).remove();
    var dataString = "user_id=" + user_id + "&brand=1";
    $.ajax({
        type : 'GET', url : URL + '/unfollow', data : dataString, success : function(response){
            // $this.parent().remove();
            //window.location.reload();
        }
    });
}//un_follow(brand_id)
