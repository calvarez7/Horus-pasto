import Layout from '../../views/modules/Layout.vue';
import store from '../../store/index';
import agenda from './agenda';
import cita from './cita';
import contratacion from './contratacion';
import medicamento from './medicamento';
import bodega from './bodega';
import autorizacion from './autorizacion';
import transcripcion from './transcripcion';
import auditoria from './auditoria';
import tutela from './tutela';
import panelMedico from './panelMedico';
import historiaClinica from './historiaClinica';
import imagenologia from './imagenologia';
import referencia from './referencia';
import medicalBills from './medicalBills';
import teleconcepto from './teleconcepto';
import oncologia from './oncologia';
import rips from './rips';
import fias from './fias';
import reportes from './reportes';
import sumintranet from './sumintranet';
import resultados from './resultados';
import covi from './covi';
import admin from './admin';
import helpdesk from './helpdesk';
import pqrsf from './pqrsf';
import vagout from './vagout';
import domiciliaria from './domiciliaria';
import evento from './evento';
import caracterizacion from './caracterizacion';
import saludocupacional from './saludocupacional';
import empalme from './empalme';
import red from './red';
import aseguramiento from './aseguramiento';
import sarlaft from './sarlaft';
import solicitudes from './solicitud';
import historiaIntegral from './historiaIntegral';
import gestionriesgo from './gestionriesgo';
import gestionhumana from './gestionhumana';
import lideres from './lideres';
import desarrollo from  './desarrollo'
import concurrencia from './concurrencia'

import m202 from  './202'


const home = {
    path: '/',
    component: Layout,
    name: '',
    children: [{
            path: '',
            component: () =>
                import('./../../views/modules/dashboard/DashBoard.vue'),
        },
        agenda,
        cita,
        contratacion,
        medicamento,
        bodega,
        autorizacion,
        transcripcion,
        auditoria,
        tutela,
        panelMedico,
        historiaClinica,
        imagenologia,
        referencia,
        medicalBills,
        teleconcepto,
        caracterizacion,
        saludocupacional,
        empalme,
        oncologia,
        rips,
        fias,
        reportes,
        aseguramiento,
        resultados,
        covi,
        admin,
        helpdesk,
        pqrsf,
        sumintranet,
        vagout,
        domiciliaria,
        evento,
        red,
        sarlaft,
        solicitudes,
        historiaIntegral,
        gestionriesgo,
        gestionhumana,
        lideres,
        m202,
        desarrollo,
        concurrencia
    ]
};

export default home;
