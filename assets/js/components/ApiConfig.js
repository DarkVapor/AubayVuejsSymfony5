
let server_url = 'http://127.0.0.1:8000';

export const ApiConfig = {
    user: {
        remove: server_url + '/user_remove',
        list:   server_url + '/user_list',
        edit:   server_url + '/user_edit',
        update: server_url + '/user_edit',
        add:    server_url + '/user_add',
        find:   server_url + '/user_find',
        login:  server_url + '/user_connection'
    }
}   