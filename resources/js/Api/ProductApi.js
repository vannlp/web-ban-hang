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
}

export default new ProductApi;