strawberry.create('app',function(){
    setTimeout(function(){
        $("#Loader").fadeOut();
        $("#Loader").html("");
        $("#main").fadeIn();
    },1500)
});
