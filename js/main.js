$(document).ready(function(){
    $("#q").show("slow");
    $("#q").focus();

    $("#q").keypress(function() {
        $("#s0").hide();
        $("#s1").show();
    });

    $("#favorites").click(function() {
        $("#favorites-inner").show("slow");
    });
});
