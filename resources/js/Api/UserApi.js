import axios from "@/libs/axios"

class UserApi {
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async store(data = {}) {
        return await axios.post(route('user.store'), data);
    }
    
    async delete(id) {
        return await axios.delete(route('user.delete', {id: id}));
    }
    
    async getUser(id) {
        return await axios.get(route('user.getUser', {id: id}));
    }
    
    async update(id, data = {}) {
        return await axios.put(route('user.update', {id: id}), data);    
    }

}

export default new UserApi;