import axios from "@/libs/axios"

class OrderApi {
    
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async checkout () {
        return await axios.post(route('client.checkout'));
    }
    
    async getOrder (id) {
        return await axios.get(route('client.getOrder', {id: id}));
    }
    
    async updateOrderStatus (data) {
        return await axios.post(route('order.updateOrderStatus'), data);
    }
}

export default new OrderApi;