app.service('PageSvc',function($scope,$patch,PageError){
    class PageSvc {
        constructor(){
            this.state = 'index';
            this.error = new PageError();
        }
        setState(state){
            this.state = state;
            return this;
        }
        displayError(PageError){
            if (undefined!==PageError) {
                this.error.code = PageError.code;
                this.error.message = PageError.message;
            }
            this.setState('error').update();
        }
        update(){
            $patch('PageView');
        }
    }
    return PageSvc;
});
