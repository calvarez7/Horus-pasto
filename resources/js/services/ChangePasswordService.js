const API_BASE = '/api/password';

export const ChangePasswordService = {
    reset: async (datos) => {
        try {
            let {data} = await axios.post(`${API_BASE}/reset`, datos);
            return data;
        } catch (error){
            console.log(error)
        }
    }
}
