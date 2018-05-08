export default{
    computed: {
        leftPage: function(){
            if(this.lastPage === 1 || this.lastPage === 2 || this.lastPage === 3){
                return 1;
            }else if(this.lastPage === this.page){
                return this.lastPage - 2;
            }else{
                return Math.max(1, this.page - 1);
            }
        },

        middlePage: function(){
            return this.leftPage + 1;
        },

        rightPage: function(){
            return this.leftPage + 2;
        },

        leftActive: function(){
            return this.page === this.leftPage;
        },

        middleActive: function(){
            return this.page === this.middlePage;
        },

        rightActive: function(){
            return this.page === this.rightPage;
        },

        isFirstPage: function(){
            return this.page === 1;
        },

        isLastPage: function(){
            return this.page === this.lastPage;
        },

        firstRecord: function(){
            return ((this.page - 1) * this.showing) + 1;
        },
    },

    methods: {
        previousPage: function(){
            if(this.page > 1){
                this.page--;
            }
        },

        nextPage: function(){
            if(this.page < this.lastPage){
                this.page++;
            }
        },
    }
}