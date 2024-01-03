const API_BASE = '/api/adjunto';

export const AdjuntoService = {
    get: async (ruta) => {
        try {
            let {
                data: type
            } = await axios.post(`${API_BASE}/getType`, {
                ruta: ruta
            });
            let {
                data: adjunto
            } = await axios.post(`${API_BASE}/get`, {
                ruta: ruta
            }, {
                responseType: "arraybuffer"
            });
            return [type, adjunto];
        } catch (error) {
            console.log(error)
        }
    },
}
