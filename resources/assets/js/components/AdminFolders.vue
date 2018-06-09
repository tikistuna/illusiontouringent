<template>
    <div class="card mb-3" id="events-table">
        <div class="card-header">
            <i class="fa fa-table"></i> Folders
            <a class="float-right text-dark" href="/admin/folders/create">New Folder</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row p-2 pb-3">
                    <div class="col-4">
                        <span class="pl-3 align-text-button pr-1">Show</span>
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-toggle="dropdown">
                            {{showing}}
                        </button>
                        <span class="pl-1 align-text-button">folders</span>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" @click="showing = 5">5</button>
                            <button class="dropdown-item" @click="showing = 10">10</button>
                            <button class="dropdown-item" @click="showing = 15">15</button>
                            <button class="dropdown-item" @click="showing = 20">20</button>
                            <button class="dropdown-item" @click="showing = 25">25</button>
                        </div>
                    </div>
                    <div class="col-3 ml-auto">Showing {{firstRecord}} to {{lastRecord}} of {{folders.length}} folders</div>
                </div>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            Id
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': idDesc}" @click="sortFolders('-id')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': idAsc}" @click="sortFolders('id')"></i>
                        </th>
                        <th>
                            Folder
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': nameDesc}" @click="sortFolders('-name')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': nameAsc}" @click="sortFolders('name')"></i>
                        </th>
                        <th>
                            # of Posters
                            <i class="fa fa-arrow-down float-right" v-bind:class="{'text-dark': postersCountDesc}" @click="sortFolders('-postersCount')"></i>
                            <i class="fa fa-arrow-up float-right" v-bind:class="{'text-dark': postersCountAsc}" @click="sortFolders('postersCount')"></i>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="folder in foldersShowing">
                        <tr>
                            <td><a class="text-dark" :href="'/admin/folders/' + folder.id + '/edit'">{{folder.id}}</a></td>
                            <td>{{folder.name}}</td>
                            <td>{{folder.postersCount}}</td>
                            <td>
                                <button class="btn btn-link" @click="deleteFolder(folder)">Delete</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Folder</th>
                        <th># of Posters</th>
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
        <div class="card-footer small text-muted">Last folder created {{lastCreated}}.</div>
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

            axios.get('/api/admin/folders').then(response => {
                this.folders = response.data;
            }, response => {
                // Use sweetalert!
                swal('Oh no!', "An error occurred with the API", "error");
            });

            axios.get('/api/admin/folders/lastCreated')
                .then(response => {
                    this.lastCreated = response.data.lastCreated;
                })
                .catch(response => {
                    swal('Oh no!', "An error occurred with the API", "error");
                });

        },

        data(){
            return{
                folders: [],
                sort: 'name',
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

            postersCountAsc: function(){
                return (this.sort === 'postersCount-asc');
            },

            postersCountDesc: function(){
                return (this.sort === 'postersCount-desc');
            },

            foldersShowing: function(){
                let beginning = this.showing * (this.page - 1);
                if(beginning > this.folders.length){
                    this.pageOutOfRange();
                    return this.folders.slice(this.showing* (this.lastPage - 1), this.showing* (this.lastPage - 1) + this.showing);
                }
                return this.folders.slice(beginning, beginning + this.showing);
            },
            lastPage: function(){
                return Math.ceil(this.folders.length / this.showing);
            },
            lastRecord: function(){
                return Math.min(this.firstRecord + this.showing - 1, this.folders.length);
            }
        },

        methods: {
            sortFolders: function(property){
                this.folders.sort(this.dynamicSort(property));
            },
            pageOutOfRange: function(){
                this.page = this.lastPage;
            },

            deleteFolder: function(folder){
                let self = this;
                let index = this.folders.indexOf(folder);
                if(index === -1){
                    swal("Error", "Couldn't find index of this folder", "error");
                }else{
                    swal({
                        title: "Warning",
                        text: "Delete " + folder.name + "?",
                        dangerMode: true,
                        buttons: true,
                        icon: 'warning'
                    }).then((willDelete) => {
                        if(willDelete){
                            axios.post('/admin/folders/' + folder.id, {
                                _method: 'DELETE',
                                _token: this.token
                            }).then(function(response){
                                swal("Success", "Folder was deleted", "success");
                                self.folders.splice(index, 1);
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
