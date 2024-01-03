const API_BASE = '/api/cie10';

export const AppointmentService = {
    getAppointmentsBlobTypeByDateRangeAsync: async (startDate,finishDate) => {
        try {
            let { data: appointments } = await axios({
                method: 'get',
                url: '/api/cita/exportAppointment',
                params: {
                    startDate,
                    finishDate,
                },
                responseType: 'blob'
            })
            
            return appointments
        }catch (error) {
            console.log(error)
        }
    }

}
