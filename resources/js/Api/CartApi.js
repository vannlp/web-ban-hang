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
    
    async getCart() {
        return await axios.get(route('client.getCart'));
    }
    
    async updateCartDetail(params) {
        return await axios.put(route('client.updateCartDetail'), params);
    }
    
    async deleteCartDetail(id) {
        return await axios.delete(route('client.deleteCartDetail', {id: id}));
    }
    
}

export default new CartApi;