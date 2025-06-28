import axios from "@/libs/axios"

class CategoryApi {
    
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async get(params = {}) {
        return await axios.get(route('admin.category.getCategories', params));
    }
    
    async getClient(params = {}) {
        return await axios.get(route('client.category.getCategories', params));
    }
    
    async updateStatus(id, params) {
        return await axios.put(route('admin.category.update-status', {id: id}), params);
    }
    
    async store(data) {
        return await axios.post(route('category.store'), data);
    }
    
    async getOne(id) {
        return await axios.get(route('category.getOne', {id: id}));
    }
    
    async update(id, data) {
        return await axios.put(route('category.update', {id: id}), data);
    }
    
    async delete(id) {
        return await axios.delete(route('category.delete', {id: id}));
    }
}

export default new CategoryApi;