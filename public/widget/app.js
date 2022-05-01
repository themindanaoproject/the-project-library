strawberry.create('app',function(){
  setTimeout(function(){
    $("#Loader").fadeOut()
    $("#Loader").html("")
    $("#main").fadeIn()
  },1500)
})

app.factory('Modules',function(){
  return {
    header:'/modules/header.htm',
    footer:'/modules/footer.htm',
  }
});

app.service('Utils',function($scope){
  return {
    text:{
      capFirst:function(str){
        return str.charAt(0).toUpperCase() + str.slice(1);
      },
      normalize:function(str){
        return str.replace('_',' ');
      }
    }
  }
});

app.service('SearchSvc',function($scope,$patch){
  return {
    keyword: null,
    items: {},
    suggest:function(){
      let keyword = $('#keyword').val();
      $scope.SearchSvc.search(keyword);
    },
    search:function(keyword){
      if (keyword.trim()==='') {
        $('#results').html('');
        return;
      }
      let results = '';
      for (var key in $scope.SearchSvc.items) {
        let skey = key.toLowerCase();
        let skeyword = keyword.toLowerCase();
        if (skey.includes(skeyword)) {
          let pathArr = $scope.SearchSvc.items[key].split('/');
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
      $('#results').html(results);
    },
    end:function(){
      $scope.$patchables['@Results'] = '';
      $patch('Results');
    }
  }
})

app.factory('CardModel',function(){
  return {
    images:{},
    logo:{},
    metrics:{},
    name:'',
    namespace:'',
    path:'',
    traceback:{region:'unknown',province:'unknown'},
    meta:{type:'unknown'},
    sublocations:{
      provinces:[],
      towns:[],
      cities:[]
    }
  }
})
