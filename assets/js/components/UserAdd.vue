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
      <transition name="fade">
        <div v-if="userSaved" class="ui green message">
          {{userSavedMessage}}
          <i @click="closeMessage()" class="close icon"></i>
        </div>
      </transition>
      <transition name="fade">
        <div v-if="userError" class="ui red message">
          {{userErrorMessage}}
          <i @click="closeError()" class="close icon"></i>
        </div>
      </transition>
      <form class="ui form">
        <div class="field" v-bind:class="{error: errors.name.notvalid }">
          <label>Name</label>

          <input v-model="user.name" type="text" name="name" placeholder="Name" />
          <p
            v-if="errors.name.message !==''"
            class="ui basic red pointing prompt label transition visible"
          >{{errors.name.message}}</p>
        </div>

        <div class="field" v-bind:class="{error: errors.email.notvalid }">
          <label>Email</label>
          <input v-model="user.email" type="email" name="email" placeholder="Email" />
          <p
            v-if="errors.email.message !==''"
            class="ui basic red pointing prompt label transition visible"
          >{{errors.email.message}}</p>
        </div>
        <div class="field" v-bind:class="{error: errors.password.notvalid}">
          <label>Password</label>
          <input v-model="user.password" type="password" name="password" placeholder="Password" />
          <p
            v-if="errors.password.message !==''"
            class="ui basic red pointing prompt label transition visible"
          >{{errors.password.message}}</p>
        </div>
        <div class="field" v-bind:class="{error: errors.repassword.notvalid }">
          <label>Retype Password</label>
          <input
            class="red"
            v-model="user.repassword"
            type="password"
            name="repassword"
            placeholder="Retype Password"
          />
          <p
            v-if="errors.repassword.message !==''"
            class="ui basic red pointing prompt label transition visible"
          >{{errors.repassword.message}}</p>
        </div>
        <a v-on:click="save()" class="ui button">Save</a>
      </form>
    </div>
  </div>
</template>
<script>
import { UserApi } from "./UserApi";
export default {
  created: function() {},
  props: ["id"],
  data: function() {
    return {
      errors: {
        name: {
          notvalid: false,
          message: ""
        },
        email: {
          notvalid: false,
          message: ""
        },
        password: {
          notvalid: false,
          message: ""
        },
        repassword: {
          notvalid: false,
          message: ""
        }
      },
      userError: false,
      userSaved: false,
      userSavedMessage: "",
      userErrorMessage: "",
      user: {
        name: "",
        email: "",
        password: "",
        repassword: ""
      }
    };
  },
  mounted() {
    if (this.id) {
      var that = this;
      UserApi.find(this.id).then(function(response) {
        that.user = response.data;
      });
    }
  },
  methods: {
    initForm: function() {
      this.errors = {
        name: {
          notvalid: false,
          message: ""
        },
        email: {
          notvalid: false,
          message: ""
        },
        password: {
          notvalid: false,
          message: ""
        },
        repassword: {
          notvalid: false,
          message: ""
        }
      };
    },
    closeMessage: function() {
      this.userSaved = false;
      this.userError = false;
    },
    closeError: function() {
      this.userSaved = false;
      this.userError = false;
    },
    save: function() {
      if (this.id) {
        this.editUser(this.id);
      } else {
        this.addUser();
      }
    },
    editUser: function(id) {
      
      this.user.id = this.id;
      let that = this;
      UserApi.update(this.user).then(function(response) {
        if (response.data.success == false) {
          that.userErrorMessage = response.data.message;
          that.userError = true;
          that.errors = response.data.errors;
        } else {
          that.userSavedMessage = response.data.message;
          that.userError = false;
          that.userSaved = true;
          that.initForm();
        }
      });
    },
    addUser: function() {
    
      let that = this;
      UserApi.add(this.user).then(function(response) {
        if (response.data.success == false) {
          that.userErrorMessage = response.data.message;
          that.userError = true;
          that.errors = response.data.errors;
        } else {
          that.userSavedMessage = response.data.message;
          that.userError = false;
          that.userSaved = true;
          that.initForm();
        }
      });
    }
  }
};
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
