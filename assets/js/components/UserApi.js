'use strict';

import {ApiConfig} from './ApiConfig';

var ax = require('axios');

export const UserApi = {
    remove: function(id){
        console.log("call ... "+ApiConfig.user.remove);
        return ax.post(ApiConfig.user.remove, {'id': id});
    },
    edit: function(id){
        console.log("call ... "+ApiConfig.user.edit);
        return ax.post(ApiConfig.user.edit, {'id': id });
    },
    list: function(filters){
        console.log("call ... "+ApiConfig.user.list);
        return ax.post(ApiConfig.user.list, {'filters': filters});
    },
    find: function(id){
        console.log("call ... "+ApiConfig.user.find);
        return ax.post(ApiConfig.user.find, {'id': id});
    }
    ,
    login: function(email, password){
        console.log("call ... "+ApiConfig.user.login);
        return ax.post(ApiConfig.user.login, {'email': email, 'password': 'password'});
    }
}


