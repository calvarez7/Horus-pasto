const API_BASE = '/api/cie10';

export const Cie10Service = {
    all: async () => {
        try {
            let { data } = await axios.get(`${API_BASE}/all`)
            return data
        }catch (error) {
            console.log(error)
        }
    }

}
