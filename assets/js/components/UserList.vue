<template>
    <div class="ui grid">
        <div class="four wide column">
            <div class="ui secondary vertical pointing fluid menu">
                <router-link class="item active" to="/userlist">Display user list</router-link>
                <router-link class="item" to="/adduser">Add</router-link>
            </div>
        </div>
        <div class="eleven wide column">
            <h2>User list</h2>

            <div class="ui grid">
                <div class="eight wide column">
                    <select v-model="limit" @change="limiter()" class="ui fluid dropdown">
                        <option>5</option>
                        <option>10</option>
                    </select>
                </div>
                <div class="eight wide column ui form">
                    <div class="field">
                        <input v-model="searchInput" v-on:keyup.13="search()" type="text" name="search" placeholder="Search">
                    </div>
                </div>
            </div>
            <table class="ui celled table">
                <thead>
                    <tr><th><a @click="sort('id')">Id</a></th>
                        <th><a @click="sort('email')">Email</a></th>
                        <th><a @click="sort('name')">Name</a></th>
                        <th>actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in userlist">
                        <td data-label="Id">{{item.id}}</td>
                        <td data-label="Email">{{item.email}}</td>
                        <td data-label="Name">{{item.name}}</td>
                        <td>
                            <a href="#" @click="removeUser(item.id)"><i class="fa fa-close"></i></a>
                <router-link :to="{ name: 'UserAdd', params: {id: item.id} }"><i class="fa fa-pencil"></i></router-link>
                </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    import {UserApi} from "./UserApi"
    export default{
        created: function () {
            this.userQuery();
        },
        data: function () {
            return {
                searchInput: '',
                userlist: [],
                userApi: {},
                sorting: {
                    email: 'ASC',
                    name: 'ASC',
                    id: 'ASC',
                },
                limit: 0,
                currentField: '',
            }
        },
        methods: {
            /**
             * @returns {undefined}
             */
            userQuery: function () {
                var that = this;
                UserApi.list(this.prepareFilters()).then(function (response) {
                    that.userlist = response.data;
                });
            },
            /**
             * @returns {undefined}
             */
            search: function () {
                this.userQuery();
            },
            /**
             * @param {type} field
             * @returns {undefined}
             */
            sort: function (field) {
                this.currentField = field;
                this.userQuery();
            },
            /** 
             * @returns {undefined}
             */
            limiter: function () {
                this.userQuery();
            },
            /**
             * @param {type} id
             * @returns {undefined}
             */
            removeUser: function (id) {
                var that = this;
                UserApi.remove(id).then(function (response) {
                    that.userQuery();
                });
            },
            /**
             * @param {type} id
             * @returns {undefined}
             */
            editUser: function (id) {
                var that = this;
                UserApi.edit(id).then(function (response) {
                    that.userQuery();
                });
            },
            /**
             * @returns {default.methods.prepareFilters.filters}
             */
            prepareFilters() {

                var filters = {};

                if (this.currentField !== '') {

                    if (this.sorting[this.currentField] === 'ASC') {
                        this.sorting[this.currentField] = 'DESC';
                    } else {
                        this.sorting[this.currentField] = 'ASC';
                    }

                    filters = {
                        field: this.currentField,
                        direction: this.sorting[this.currentField]
                    };
                }

                if (this.limit !== 0) {
                    filters.offset = 0;
                    filters.limit = this.limit;
                }

                if (this.searchInput !== '') {
                    filters.search = this.searchInput;
                }

                return filters;


            }
        }
    }
</script>
<style>
    a{
        cursor: pointer;
    }
</style>