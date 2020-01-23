<template>
    <div class="ui grid">
        <div class="four wide column">
            <div class="ui secondary vertical pointing fluid menu">
                <router-link class="item" to="/userlist">Display user list</router-link>
                <router-link class="item active" to="/adduser">Add</router-link>
            </div>
        </div>
        <div class="eleven wide column">
            
            <h2>User add</h2>
            <form class="ui form">
                <div class="field">
                    <label>Name</label>
                    <input v-model="user.name" type="text" name="name" placeholder="Name">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input v-model="user.email" type="email" name="email" placeholder="Email">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input v-model="user.password" type="password" name="password" placeholder="Password">
                </div>
                <div class="field">
                    <label>Retype Password</label>
                    <input v-model="user.repassword" type="password" name="repassword" placeholder="Retype Password">
                </div>
                <a v-on:click="save()" class="ui button">Save</a>
            </form>
        </div>
    </div>
</template>
<script>
    import {UserApi} from "./UserApi"
    export default{
        created: function () {},
        props: ['id'],
        data: function () {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                    repassword: ''
                }
            }
        },
        mounted() {
            if (this.id) {
                var that = this;
                UserApi.find(this.id).then(function (response) {
                    that.user = response.data;
                });
            }
        },
        methods: {
            save: function () {
                if (this.id) {
                    this.editUser(this.id);
                } else {
                    this.addUser();
                }
            },
            editUser: function (id) {
                var ax = require('axios');
                ax.post('http://127.0.0.1:8000/user_edit', {'user': this.user})
                        .then(function (response) {

                        });
            },
            addUser: function () {
                var ax = require('axios');
                ax.post('http://127.0.0.1:8000/user_add', {'user': this.user})
                        .then(function (response) {

                        });
            }
        }
    }
</script>
<style></style>
