import Vue from 'vue'
import Swal from 'sweetalert2';

export default {
    install(Vue, options) {
        Vue.prototype.$alerWarning = async (title,text = ' ',buttons = true) => {
            const value = await Swal.fire({
                title: title,
                text: text,
                icon:"warning",
                showCloseButton: buttons,
                showCancelButton: buttons,
                showConfirmButton: buttons,
                confirmButtonAriaLabel: 'Confirmar',
                cancelButtonAriaLabel: 'Cancelar',
                timer: !buttons?2000:false,
            });
            return value;
        };
        Vue.prototype.$alerSuccess = async (title,text = ' ') => {
            const value = await Swal.fire({
                title: title,
                text: text,
                icon:"success",
                timer: 2000,
                showConfirmButton: false,
            });
            return value;
        };
        Vue.prototype.$alerError = async (title,text = ' ',buttons = true) => {
            const value = await Swal.fire({
                title: title,
                text: text,
                icon:"error",
                showConfirmButton: buttons,
                timer: !buttons?2000:false,
            });
            return value;
        };
        Vue.prototype.$alertInfo = async (title,text = ' ') => {
            const value = await Swal.fire({
                title: title,
                text: text,
                icon:"info",
                showCloseButton: true,
                confirmButtonAriaLabel: 'Confirmar',
            });
            return value;
        };
        Vue.prototype.$alertQuestion = async (title,text = ' ') => {
            const value = await Swal.fire({
                title: title,
                text: text,
                icon:"warning",
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonColor: '#5bb65f',
                confirmButtonText: '¡Si!',
                reverseButtons: true,
            });
            return value;
        };
        Vue.prototype.$alertHistoria = async (title,text = ' ') => {

            const value = await Swal.fire({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                title: title,
                text: text,
                icon: 'success',
                background: '#4caf50',
                });
            return value;
        };
    },
}
