strawberry.create('app',function(){
  setTimeout(function(){
    $("#Loader").fadeOut()
    $("#Loader").html("")
    $("#main").fadeIn()
  },1500)
})

app.service('SearchSvc',function($scope){
  return {
    keyword: null,
    items: {},
    suggest:function(){
      let keyword = $('#keyword').val();
      $scope.SearchSvc.search(keyword);
    },
    search:function(keyword){
      let results = [];
      for (var key in $scope.SearchSvc.items) {
        let skey = key.toLowerCase();
        if (skey.includes(keyword)) {
          results.push(key);
        }
      }
      console.log(results);
    }
  }
})
