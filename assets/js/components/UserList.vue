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
 
        <div class="eight wide column ui form">
          <div class="field">
            <input
              v-model="searchInput"
              v-on:keyup.13="search()"
              type="text"
              name="search"
              placeholder="Search"
            />
          </div>
          
        </div>
        <!-- <div class="eight wide column">
          <select v-model="limit" @change="limiter()" class="ui fluid dropdown">
            <option>5</option>
            <option>10</option>
          </select>
        </div> -->
      </div>
      <transition name="fade">
        <div v-if="message !==''" class="ui green message">
          {{message}}
          <i @click="closeMessage()" class="close icon"></i>
        </div>
      </transition>
      <table class="ui celled table">
        <thead>
          <tr>
            <th>
              <a @click="sort('id')">Id</a>
            </th>
            <th>
              <a @click="sort('email')">Email</a>
            </th>
            <th>
              <a @click="sort('name')">Name</a>
            </th>
            <th>actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in userlist">
            <td data-label="Id">{{item.id}}</td>
            <td data-label="Email">{{item.email}}</td>
            <td data-label="Name">{{item.name}}</td>
            <td>
              <a href="#" @click="removeUser(item.id)">
                <i class="fa fa-close"></i>
              </a>
              <router-link :to="{ name: 'UserAdd', params: {id: item.id} }">
                <i class="fa fa-pencil"></i>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="ui pagination menu center">
        <a href="#" v-for="n in nbPages" :class="{active: n == currentPage}" @click="changePage(n)" class="item">{{n}}</a>
      </div>
    </div>
  </div>
</template>
<script>
import { UserApi } from "./UserApi";
import { router } from "./../app";
import { CookiesService } from './Cookies';


export default {
  created: function() {
    this.userQuery();
  },
  data: function() {
    return {
      message:'',  
      searchInput: "",
      userlist: [],
      userlistTotalCount:0,
      userApi: {},
      sorting: {
        email: "ASC",
        name: "ASC",
        id: "ASC"
      },
      limit: 5,
      offset: 0,
      nbPages: 0,
      currentPage:1,
      currentField: ""
    };
  },
  methods: {
      closeMessage: function(){
          this.message = '';
      },
    /**
     * @returns {undefined}
     */
    userQuery: function() {
      var that = this;


      if(CookiesService.get('token') == ""){
        router.push("Login");
      }

      UserApi.list(this.prepareFilters()).then(function(response) {

        if(response.data.success == true){
          that.userlist = response.data.list;
          that.userlistTotalCount = response.data.totalCount;
          that.nbPages = Math.ceil((that.userlistTotalCount / that.limit));
        }else{
          router.push("Login");
        }
      });
    },
    /**
     * 
     */
    changePage: function(page){
        this.currentPage = page;
        this.userQuery();
    },
    /**
     * @returns {undefined}
     */
    search: function() {
      this.userQuery();
    },
    /**
     * @param {type} field
     * @returns {undefined}
     */
    sort: function(field) {
      this.currentField = field;
      if (this.sorting[this.currentField] === "ASC") {
          this.sorting[this.currentField] = "DESC";
        } else {
          this.sorting[this.currentField] = "ASC";
        }

      this.userQuery();
    },
    /**
     * @returns {undefined}
     */
    limiter: function() {
      this.userQuery();
    },
    /**
     * @param {type} id
     * @returns {undefined}
     */
    removeUser: function(id) {
      var that = this;
      UserApi.remove(id).then(function(response) {
        console.log(response);
        if(response.data.success == true){
          that.userQuery();
          that.message = 'User removed with success';
        }else{
          router.push("Login");
        }        
      });
    },
    /**
     * @param {type} id
     * @returns {undefined}
     */
    editUser: function(id) {
      var that = this;
      UserApi.edit(id).then(function(response) {
        that.userQuery();
      });
    },
    /**
     * @returns {default.methods.prepareFilters.filters}
     */
    prepareFilters() {
      var filters = {};

      if (this.currentField !== "") {
        filters = {
          field: this.currentField,
          direction: this.sorting[this.currentField]
        };
      }

      if (this.limit !== 0) {
        filters.offset = this.offset = this.limit * ( this.currentPage - 1 );
        filters.limit = this.limit;
      }

      if (this.searchInput !== "") {
        filters.search = this.searchInput;
      }

      return filters;
    }
  }
};
</script>
<style>
a {
  cursor: pointer;
}
</style>