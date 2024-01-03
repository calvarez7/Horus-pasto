const API_BASE = '/api/tipoOrden';

export const TipoOrdenService = {
    getEnableEsquemas: async () => {
        try {
            let {data} = await axios.get(`${API_BASE}/enableEsquemas`);
            return data;
        } catch (error) {
            console.log(error)
        }
    },
}
