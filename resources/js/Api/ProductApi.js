import axios from "@/libs/axios"

class ProductApi {
    
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async store(data = {}) {
        return await axios.post(route('product.store'), data);
    }
    
    async update(id, data = {}) {
        return await axios.put(route('product.update', {id: id}), data);
    }
    
    async delete(id) {
        return await axios.delete(route('product.delete', {id: id}));
    }
}

export default new ProductApi;