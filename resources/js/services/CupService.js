const API_BASE = '/api/cups';

export const CupService = {
    getCupsOtherServiceByUserRoleLevel: async () => {
        try {
            let {data} = await axios.post(`${API_BASE}/orden`)
            return data
        }catch (error) {
            console.log(error)
        }
    }
}
