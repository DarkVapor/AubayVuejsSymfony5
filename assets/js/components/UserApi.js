'use strict';

import {ApiConfig} from './ApiConfig';

var ax = require('axios');

export const UserApi = {
    remove: function(id){
        return ax.post(ApiConfig.user.remove, {'id': id});
    },
    edit: function(id){
        return ax.post(ApiConfig.user.edit, {'id': id });
    },
    list: function(filters){
        return ax.post(ApiConfig.user.list, {'filters': filters});
    },
    find: function(id){
        return ax.post(ApiConfig.user.find, {'id': id});
    }
}


