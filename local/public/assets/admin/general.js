/**
 * Created by   :  Muhammad Yasir
 * Project Name : kinnect2
 * Product Name : PhpStorm
 * Date         : 08-Mar-2016 1:49 PM
 * File Name    :
 */
$(document).ready(function(){
    var h = false;
    $("#profileLink").click(function(){
        if(h == false){
            $("#popUp").fadeIn('fast');
            $("#popUpText").fadeIn(function(){
                h = true;
            });
        }
        if(h == true){
            $("#popUp").fadeOut('fast');
            $("#popUpText").fadeOut(function(){
                h = false
            });
        }
    });
});
