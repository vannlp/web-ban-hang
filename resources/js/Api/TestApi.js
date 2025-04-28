import axios from "@/libs/axios"

class TestApi {
    async testApi() {
        return await axios.post(route('admin.dashboard.post'), {
            'a' : 'haha'
        });
    }

}

export default new TestApi;