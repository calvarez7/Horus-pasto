const API_BASE = '/api/tipoOrden';

export const DetalleEsquemaService = {
    getDetalleEsquema: async () => {
        try {
            let {data} = await axios.get(`${API_BASE}/getDetalleEsquema`);
            return data;
        } catch (error) {
            console.log(error)
        }
    },
}
