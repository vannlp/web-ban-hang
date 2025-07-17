import axios from "@/libs/axios"

class AddressApi {
    
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }
    
    async getCities (params) {
        return await axios.get(route('getCities', params));
    }
    
    async getDistricts (params) {
        return await axios.get(route('getDistricts', params));
    }
    
    async getWards (params) {
        return await axios.get(route('getWard', params));
    }
    
    async store(data = {}) {
        return await axios.post(route('address.store'), data);
    }
    
    async update(id, data = {}) {
        return await axios.put(route('address.update', {id: id}), data);
    }
    
    async getOne(id) {
        return await axios.get(route('address.getAddress', {id: id}));
    }
    
    async getListAddress(params) {
        return await axios.get(route('address.listAddress', params));
    }
    
    async getAddressDefault(params) {
        return await axios.get(route('address.getAddressDefault', params));
    }
    
    async updateAddressDefault(id, params = {}) {
        return await axios.put(route('address.updateDefault', {id: id}, params));
    }
    
    async delete(id) {
        return await axios.delete(route('address.delete', {id: id}));
    }
    
}

export default new AddressApi;