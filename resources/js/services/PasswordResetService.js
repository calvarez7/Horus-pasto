const API_BASE = '/api/password';

export const PasswordResetService = {
    create: async (email) => {
        try {
            let {data} = await axios.post(`${API_BASE}/create`, email);
            return data;
        } catch (error) {
            console.log(error)
        }
    },

    find: async (email) => {
        try {
            let {data} = await axios.post(`${API_BASE}/find`, email);
            return data;
        } catch (error){
            console.log(error)
        }
    },

    reset: async (datos) => {
        try {
            let {data} = await axios.post(`${API_BASE}/reset`, datos);
            console.log('assajd', data);
            return data;
        } catch (error){
            console.log(error)
        }
    }
}
