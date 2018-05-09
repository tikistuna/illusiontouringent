<template>
    <div class="card mb-3" id="events-table">
        <div class="card-header">
            <i class="fa fa-table"></i> Ticket Sellers
            <a class="float-right text-dark" href="/admin/ticket_sellers/create">New Ticket Seller</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row p-2 pb-3" id="table-header-info">
                    <div class="col-4">
                        <span class="pl-3 align-text-button pr-1">Show</span>
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown">
                            {{showing}}
                        </button>
                        <span class="pl-1 align-text-button">Ticket Sellers</span>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" @click="showing = 5">5</button>
                            <button class="dropdown-item" @click="showing = 10">10</button>
                            <button class="dropdown-item" @click="showing = 15">15</button>
                            <button class="dropdown-item" @click="showing = 20">20</button>
                            <button class="dropdown-item" @click="showing = 25">25</button>
                        </div>
                    </div>
                    <div class="input-group col-4" >
                        <input id="search-query" type="text" class="form-control" placeholder="Search for a Ticket Seller...">
                        <span class="input-group-btn">
                            <button id="search-submit" class="btn btn-dark" type="button">Search</button>
                        </span>
                    </div>
                    <div class="col-3 ml-auto">Showing {{firstRecord}} to {{lastRecord}} of {{ticket_sellers.length}} Ticket Sellers</div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Name
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': nameDesc}" @click="sortTicketSellers('-name')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': nameAsc}" @click="sortTicketSellers('name')"></i>
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Address
                        </th>
                        <th>
                            Website
                        </th>
                        <th>
                            Hours
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="ticketSeller in ticketSellersShowing">
                        <tr>
                            <td><a class="text-dark" :href="'/admin/ticket_sellers/' + ticketSeller.id + '/edit'">{{ticketSeller.name}}</a></td>
                            <td>{{ticketSeller.phone}}</td>
                            <td>{{ticketSeller.address}}</td>
                            <td>{{ticketSeller.website}}</td>
                            <td>{{ticketSeller.hours}}</td>
                            <td>
                                <button class="btn btn-link" @click="deleteTicketSeller(ticketSeller)">Delete</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Website</th>
                        <th>Hours</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0 pt-1">
                        <li class="page-item" @click="previousPage" :class="{'disabled': isFirstPage}">
                            <button class="page-link">Previous</button>
                        </li>
                        <li class="page-item" :class="{'active': leftActive}" @click="page = leftPage">
                            <button class="page-link">{{leftPage}}</button>
                        </li>
                        <template v-if="lastPage > 1">
                            <li class="page-item" :class="{'active': middleActive}" @click="page = middlePage">
                                <button class="page-link">{{middlePage}}</button>
                            </li>
                        </template>
                        <template v-if="lastPage > 2">
                            <li class="page-item" :class="{'active': rightActive}" @click="page = rightPage">
                                <button class="page-link">{{rightPage}}</button>
                            </li>
                        </template>
                        <li class="page-item" @click="nextPage" :class="{'disabled': isLastPage}">
                            <button class="page-link">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="card-footer small text-muted">Last Ticket Seller created {{lastCreated}}.</div>
    </div>
</template>

<script>
    import axios from 'axios';
    import pagination from '../mixins/pagination';
    import dynamicSorting from '../mixins/dynamicSorting';
    import swal from 'sweetalert';
    export default{

        mixins: [pagination, dynamicSorting],

        created(){

            this.token = document.querySelector("meta[name='csrf-token']").content;
            axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
            };

            axios.get('/api/admin/ticket_sellers').then(response => {
                this.ticket_sellers = response.data;
            }, response => {
                // Use sweetalert!
                swal('Oh no!', "An error occurred with the API", "error");
            });

            axios.get('/api/admin/ticket_sellers/lastCreated')
                .then(response => {
                    this.lastCreated = response.data.lastCreated;
                })
                .catch(response => {
                    swal('Oh no!', "An error occurred with the API", "error");
                });

        },

        data(){
            return{
                ticket_sellers: [],
                sort: 'name',
                showing: 10,
                page: 1,
                token: ''
            }
        },

        computed: {
            nameAsc: function(){
                return (this.sort === 'name');
            },

            nameDesc: function(){
                return (this.sort === '-name');
            },

            ticketSellersShowing: function(){
                let beginning = this.showing * (this.page - 1);
                if(beginning > this.ticket_sellers.length){
                    this.pageOutOfRange();
                    return this.ticket_sellers.slice(this.showing* (this.lastPage - 1), this.showing* (this.lastPage - 1) + this.showing);
                }
                return this.ticket_sellers.slice(beginning, beginning + this.showing);
            },
            lastPage: function(){
                return Math.ceil(this.ticket_sellers.length / this.showing);
            },
            lastRecord: function(){
                return Math.min(this.firstRecord + this.showing - 1, this.ticket_sellers.length);
            }
        },

        methods: {
            sortTicketSellers: function(property){
                this.ticket_sellers.sort(this.dynamicSort(property));
            },
            pageOutOfRange: function(){
                this.page = this.lastPage;
            },

            deleteTicketSeller: function(ticketSeller){
                let self = this;
                let index = this.ticket_sellers.indexOf(ticketSeller);
                if(index === -1){
                    swal("Error", "Couldn't find index of this Ticket Seller", "error");
                }else{
                    swal({
                        title: "Warning",
                        text: "Delete " + ticketSeller.name + "?",
                        dangerMode: true,
                        buttons: true,
                        icon: 'warning'

                    }).then((willDelete) => {
                        if(willDelete){
                            axios.post('/admin/ticket_sellers/' + ticketSeller.id, {
                                _method: 'DELETE',
                                _token: this.token
                            }).then(function(response){
                                swal("Success", "Ticket Seller was deleted", "success");
                                self.ticket_sellers.splice(index, 1);
                            }).catch(function(response){
                                swal("Oh no!", "Looks like something went wrong.", "error");
                            });
                        }
                    });
                }
            }
        }

    }
</script>

<style scoped>
    #table-header-info{
        font-size: 0.9rem;
    }
    td{
        font-size: 0.85rem;
    }
</style>
