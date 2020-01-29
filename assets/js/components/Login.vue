<template>
  <div class="ui middle aligned center aligned grid">
    <div class="five wide column">
      <h2 class="ui teal image header">
        <div class="content">Log-in to your account</div>
      </h2>
      <div v-if="error!=''" class="ui error message">
        <span>{{error}}</span>
      </div>
      <form class="ui large form">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input v-model="login.email" type="text" name="email" placeholder="E-mail address" />
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input
                v-model="login.password"
                type="password"
                name="password"
                placeholder="Password"
              />
            </div>
          </div>
          <div @click="doLogin()" class="ui fluid large teal submit button">Login</div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { UserApi } from "./UserApi";
import { CookiesService} from "./Cookies";
import { router} from "./../app";

export default {
  created: function() {},
  data: function() {
    return {
      error: "",
      login: {
        email: "",
        password: ""
      }
    };
  },
  mounted() {},
  methods: {
    doLogin: function() {
      
      var that = this;

      UserApi.login(this.login.email, this.login.password).then(function(
        response
      ) {
          if(response.data.success == 'true'){
            CookiesService.set('token', response.data.token, 1);
            router.push("UserList");
          }
          that.error = response.data.message;
      });
    }
  }
};
</script>