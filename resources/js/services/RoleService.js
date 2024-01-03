const API_BASE = '/api/role';

export const RoleService = {
    all: async () => {
        try {
            let { data } = await axios.get(`${API_BASE}/all`);
            return  data;
        } catch (error) {
            console.log(error);
        }
    },
    create: async (data) => {
        try {
            return await axios.post(`${API_BASE}/create`, data);
        } catch (error) {
            console.log(error);
        }
    }

};