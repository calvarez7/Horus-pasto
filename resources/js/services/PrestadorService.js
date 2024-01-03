const API_BASE = '/api/sedeproveedore';

export const PrestadorService = {
    getEnablePrestadores: async () => {
        try {
            let {data} = await axios.get(`${API_BASE}/allproveedores`);
            return data;
        } catch (error) {
            console.log(error)
        }
    },
    getPrestadores: async () => {
        try {
            let {data} = await axios.get(`${API_BASE}/getProveedores`);
            return data;
        } catch (error) {
            console.log(error)
        }
    },
}
