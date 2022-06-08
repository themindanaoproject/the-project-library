const LIB_HOST = 'http://localhost:3300/';
strawberry.create('app',function(){
    setTimeout(function(){
        $("#Loader").fadeOut();
        $("#Loader").html("");
        if ($('.move-on-keyup').length>0) {
            $.ajax({
                method: 'GET',
                url: LIB_HOST+'data/paths.json',
                contentType:'application/json',
                success:function(response){
                    $('#home-search-box').keyup(function(){
                        let homeSearchBox = $('#home-search-box');
                        if (homeSearchBox.val().trim()==='') {
                            $('.move-on-keyup').css('margin-top','45%');
                            $('#home-search-results').html('');
                            return;
                        } else {
                            let keyword = $('#home-search-box').val();
                            let results = '';
                            for (var key in response) {
                                let skey = key.toLowerCase();
                                let skeyword = keyword.toLowerCase();
                                if (skey.includes(skeyword)) {
                                    let pathArr = response[key].split('/');
                                    let provincePath = 'null';
                                    let towncityPath = 'null';
                                    if (undefined!==pathArr[3]&&''!==pathArr[3].trim()) {
                                        provincePath = pathArr[3];
                                    }
                                    if (undefined!==pathArr[4]&&''!==pathArr[4].trim()) {
                                        towncityPath = pathArr[4];
                                    }
                                    results = results+'<div class="result-item"><a href="/card.html?region='+pathArr[2]+'&province='+provincePath+'&ct='+towncityPath+'&keyword='+key+'">'+key+'</a></div>';
                                }
                            }
                            $('#home-search-results').html(results);
                            $('.move-on-keyup').css('margin-top','0px');
                            return;
                        }
                    });
                },
                error:function(){

                }
            });

        }
        $("#main").fadeIn();
    },1500)
});
