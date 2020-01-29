import { ApiConfig } from "./ApiConfig";
import { CookiesService } from "./Cookies"; 

var ax = require("axios");

export const UserApi = {
  remove: function(id) {
    console.log("call ... " + ApiConfig.user.remove);
    return ax.post(ApiConfig.user.remove, {
      id: id,
      token: CookiesService.get('token')
    });
  },
  update: function(user){
    console.log("call ... " + ApiConfig.user.update);
    return ax.post(ApiConfig.user.update, {
      user: user,
      token: CookiesService.get('token')
    });
  },
  edit: function(id) {
    console.log("call ... " + ApiConfig.user.edit);
    return ax.post(ApiConfig.user.edit, {
      id: id,
      token: CookiesService.get('token')
    });
  },
  add: function(user) {
    console.log("call ... " + ApiConfig.user.add);
    return ax.post(ApiConfig.user.add, {
      user: user,
      token: CookiesService.get('token')
    });
  },
  list: function(filters) {
    console.log("call ... " + ApiConfig.user.list);
    return ax.post(ApiConfig.user.list, {
      filters: filters,
      token: CookiesService.get('token')
    });
  },
  find: function(id) {
    console.log("call ... " + ApiConfig.user.find);
    return ax.post(ApiConfig.user.find, {
      id: id,
      token: CookiesService.get('token')
    });
  },
  login: function(email, password) {
    console.log("call ... " + ApiConfig.user.login);
    return ax.post(ApiConfig.user.login, { email: email, password: password });
  }
};
