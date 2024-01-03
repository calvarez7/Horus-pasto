const API_BASE = '/api/user';

export const UserService = {
    all: async () => {
        try {
            let { data } = await axios.get(`${API_BASE}/all`);
            return  data;
        } catch (error) {
            console.log(error);
        }
    },
    enableByRole: async () => {
        try {
            let { data } = await axios.get(`${API_BASE}/activos`);
            return data;
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
    },
    update: async function (userId, data) {
        try {
            return await axios.put(`${API_BASE}/edit/${userId}`, data);
        } catch (error) {
            console.log(error)
        }
    }

};