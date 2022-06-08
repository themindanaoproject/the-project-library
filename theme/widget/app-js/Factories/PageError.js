app.factory('PageError',function(){
    class PageError {
        constructor(code,message){
            this.code = code ?? 500;
            this.message = message ?? 'Internal Server Error';
        }
    }
    return PageError;
});
