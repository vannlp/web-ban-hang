import axios from "@/libs/axios"

class CartApi {
    
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async addToCart(params) {
        return await axios.post(route('client.addToCart'), params);
    }
    
}

export default new CartApi;