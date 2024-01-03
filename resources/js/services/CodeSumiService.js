const API_BASE = '/api/codesumi';

export const CodeSumiService = {
    getCodeSumiEsquemas: async (esquemaId) => {
        try {
            let { data } = await axios.get(`${API_BASE}/codesumisEsquema/${esquemaId}`);
            return data;
        } catch (error) {
            console.log(error)
        }
    },
}
