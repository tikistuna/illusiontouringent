<template>
    <div class="card mb-3" id="events-table">
        <div class="card-header">
            <i class="fa fa-table"></i> venues
            <a class="float-right text-dark" href="/admin/venues/create">New venue</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row p-2 pb-3" id="table-header-info">
                    <div class="col-4">
                        <span class="pl-3 align-text-button pr-1">Show</span>
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown">
                            {{showing}}
                        </button>
                        <span class="pl-1 align-text-button">venues</span>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" @click="showing = 5">5</button>
                            <button class="dropdown-item" @click="showing = 10">10</button>
                            <button class="dropdown-item" @click="showing = 15">15</button>
                            <button class="dropdown-item" @click="showing = 20">20</button>
                            <button class="dropdown-item" @click="showing = 25">25</button>
                        </div>
                    </div>
                    <div class="input-group col-4" >
                        <input id="search-query" type="text" class="form-control" placeholder="Search for an event...">
                        <span class="input-group-btn">
                            <button id="search-submit" class="btn btn-dark" type="button">Search</button>
                        </span>
                    </div>
                    <div class="col-3 ml-auto">Showing {{firstRecord}} to {{lastRecord}} of {{venues.length}} venues</div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Id
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': idDesc}" @click="sortVenues('-id')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': idAsc}" @click="sortVenues('id')"></i>
                        </th>
                        <th>
                            Venue
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': nameDesc}" @click="sortVenues('-name')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': nameAsc}" @click="sortVenues('name')"></i>
                        </th>
                        <th>
                            City
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': cityNameDesc}" @click="sortVenues('-cityName')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': cityNameAsc}" @click="sortVenues('cityName')"></i>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="venue in venuesShowing">
                        <tr>
                            <td><a class="text-dark" :href="'/admin/venues/' + venue.id + '/edit'">{{venue.id}}</a></td>
                            <td>{{venue.name}}</td>
                            <td>{{venue.cityName}}</td>
                            <td>
                                <button class="btn btn-link" @click="deleteVenue(venue)">Delete</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Venue</th>
                        <th>City</th>
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
        <div class="card-footer small text-muted">Last event created yesterday at 11:59 PM</div>
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

            axios.get('/api/admin/venues').then(response => {
                this.venues = response.data;
            }, response => {
                // Use sweetalert!
                swal('Oh no!', "An error occurred with the API", "error");
            });

        },

        data(){
            return{
                venues: [],
                sort: 'name-asc',
                showing: 10,
                page: 1,
                token: ''
            }
        },

        computed: {
            idAsc: function(){
                return (this.sort === 'id-asc');
            },

            idDesc: function(){
                return (this.sort === 'id-desc');
            },

            nameAsc: function(){
                return (this.sort === 'name-asc');
            },

            nameDesc: function(){
                return (this.sort === 'name-desc');
            },

            cityNameAsc: function(){
                return (this.sort === 'cityName-asc');
            },

            cityNameDesc: function(){
                return (this.sort === 'cityName-desc');
            },

            venuesShowing: function(){
                let beginning = this.showing * (this.page - 1);
                if(beginning > this.venues.length){
                    this.pageOutOfRange();
                    return this.venues.slice(this.showing* (this.lastPage - 1), this.showing* (this.lastPage - 1) + this.showing);
                }
                return this.venues.slice(beginning, beginning + this.showing);
            },
            lastPage: function(){
                return Math.ceil(this.venues.length / this.showing);
            },
            lastRecord: function(){
                return Math.min(this.firstRecord + this.showing - 1, this.venues.length);
            }
        },

        methods: {
            sortVenues: function(property){
                this.venues.sort(this.dynamicSort(property));
            },
            pageOutOfRange: function(){
                this.page = this.lastPage;
            },
            deleteVenue: function(venue){
                let self = this;
                let index = this.venues.indexOf(venue);
                if(index === -1){
                    swal("Error", "Couldn't find index of this venue", "error");
                }else{
                    swal({
                        title: "Warning",
                        text: "Delete " + venue.name + "?",
                        dangerMode: true,
                        buttons: true,
                        icon: 'warning'
                    }).then((willDelete) => {
                        if(willDelete){
                            axios.post('/admin/venues/' + venue.id, {
                                _method: 'DELETE',
                                _token: this.token
                            }).then(function(response){
                                swal("Success", "Venue was deleted", "success");
                                self.venues.splice(index, 1);
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