/**
 * Created by Admin on 17-Feb-2016.
 */
var loading = $('#loading');
var ajax    = function($isAjax, $target, $user_id, $url){
    if($isAjax == true){
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
        if($('#' + $target).is(':empty')){
            $.ajax({
                type : 'POST',
                url : $url,
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

toDo = function($this, $tab, $target){
    $tab.removeClass('active');
    $this.addClass('active');
    //if ($this.is("[data-ajax]")) {
    $('.target').addClass('hide');
    //}
    $('#' + $target).removeClass('hide');
};

friend_toggle = function(url, $this){
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
};

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
            var $user_id = $(this).parents('.sub-nav-container').data('user');
            var $url     = $(this).parents('.sub-nav-container').data('url');
            ajax($isAjax, $target, $user_id, $url);
        }
        toDo($(this), $tab, $target);
    });

    $(".friend-toggle").on('click', function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle(url, $this);
    });

    $('.profile-content').on('click', ".friend-toggle", function(e){
        e.preventDefault();
        var url   = $(this).attr('href');
        var $this = $(this);
        friend_toggle(url, $this);
    });

    $(window).load(function(){
        if(window.location.hash){
            var $tab     = $('.tab');
            var hash     = window.location.hash.substring(1);
            var target   = $("[data-target=" + hash + "]");
            var $user_id = target.parents('.sub-nav-container').data('user');
            var $url     = target.parents('.sub-nav-container').data('url');
            ajax(true, hash, $user_id, $url);
            toDo($(this), $tab, hash);
            target.addClass('active');
        }else{
            // Fragment doesn't exist
        }
    });
});

