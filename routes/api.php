<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('imprimir-prueba', 'ImpresoraController@imprimir');

/** ruta de ChatBot */
Route::post('/chat-bot', 'ChatBot\ChatBotController@peticion');

// Usuarios
//  Route::get('/{bodega}/historico/dispensado/exportar',   'Bodegas\BodegaController@exphistoricoDispensado');
require __DIR__ . '/auth/auth.php';
require __DIR__ . '/auth/password.php';
require __DIR__ . '/usuarios/user.php';
require __DIR__ . '/roles/role.php';
require __DIR__ . '/permisos/permiso.php';
require __DIR__ . '/municipios/municipio.php';
require __DIR__ . '/departamentos/departamento.php';

// Citas
require __DIR__ . '/pacientes/paciente.php';
require __DIR__ . '/tipoagendas/tipoagenda.php';
require __DIR__ . '/especialidades/especialidad.php';
require __DIR__ . '/estados/Estado.php';
require __DIR__ . '/sedes/sede.php';
require __DIR__ . '/consultorios/consultorio.php';
require __DIR__ . '/citas/cita.php';
require __DIR__ . '/agendas/agenda.php';
require __DIR__ . '/citas/cobro.php';

// Tarifarios
require __DIR__ . '/manuales_tarifarios/tiposervicio.php';
require __DIR__ . '/manuales_tarifarios/tipoprestador.php';
require __DIR__ . '/manuales_tarifarios/cupservicio.php';
require __DIR__ . '/manuales_tarifarios/tipomanuales.php';
require __DIR__ . '/manuales_tarifarios/tipocodigos.php';
require __DIR__ . '/manuales_tarifarios/codesumi.php';
require __DIR__ . '/manuales_tarifarios/codigomanual.php';
require __DIR__ . '/manuales_tarifarios/tarifarios.php';
require __DIR__ . '/manuales_tarifarios/cups.php';
require __DIR__ . '/manuales_tarifarios/codepropios.php';
require __DIR__ . '/manuales_tarifarios/tipofamilia.php';
require __DIR__ . '/manuales_tarifarios/familia.php';
require __DIR__ . '/manuales_tarifarios/contrato.php';
require __DIR__ . '/manuales_tarifarios/salariominimo.php';

// Medicamentos
require __DIR__ . '/prestadores/prestador.php';
require __DIR__ . '/categorias/categoria.php';
require __DIR__ . '/sedeproveedores/sedeproveedor.php';
require __DIR__ . '/grupos/grupo.php';
require __DIR__ . '/grupos/subgrupo.php';
require __DIR__ . '/articulos/detallearticulo.php';
require __DIR__ . '/proveedores/catalogo.php';
require __DIR__ . '/transaciones/transacion.php';
require __DIR__ . '/transaciones/tipo.php';
require __DIR__ . '/transaciones/tipotransacion.php';
require __DIR__ . '/transaciones/movimiento.php';
require __DIR__ . '/bodegas/tipobodega.php';
require __DIR__ . '/bodegas/bodega.php';
require __DIR__ . '/cargos/cargo.php';

// REPS
require __DIR__ . '/reps/reps.php';

// RiPS
require __DIR__ . '/rips/rips.php';

// Historia Integral
require __DIR__ . '/historia/historia.php';

// historia vieja
require __DIR__ . '/historiaclinica/tipocita.php';
require __DIR__ . '/historiaclinica/examenfisico.php';
require __DIR__ . '/historiaclinica/conducta.php';
require __DIR__ . '/historiaclinica/diagnostico.php';
require __DIR__ . '/historiaclinica/orden.php';
require __DIR__ . '/historiaclinica/tipoOrden.php';
require __DIR__ . '/historiaclinica/cie10.php';
require __DIR__ . '/historiaclinica/pacienteantecedente.php';
require __DIR__ . '/historiaclinica/parentescoantecede.php';
require __DIR__ . '/historiaclinica/antecedente.php';
require __DIR__ . '/historiaclinica/estilovida.php';
require __DIR__ . '/historiaclinica/gineco.php';
require __DIR__ . '/historiaclinica/parentesco.php';
require __DIR__ . '/historiaclinica/labgestionriesgo.php';
require __DIR__ . '/historiaclinica/colegios.php';
require __DIR__ . '/historiaclinica/incapacidad.php';
require __DIR__ . '/historiaclinica/gethistoria.php';
require __DIR__ . '/historiaclinica/imagenologia.php';
require __DIR__ . '/historiaclinica/patologia.php';

//Autorizaciones
require __DIR__ . '/autorizaciones/autorizacion.php';
require __DIR__ . '/pdf/pdf.php';
require __DIR__ . '/files/file.php';

//Tutelas
require __DIR__ . '/tutelas/tutelas.php';
require __DIR__ . '/juzgados/juzgado.php';
require __DIR__ . '/tiporequerimientos/tiporequerimiento.php';

//HORUS 1
//Referencia
require __DIR__ . '/referencia/referencia.php';
require __DIR__ . '/teleconcepto/teleconcepto.php';

//Conteo Inventario
require __DIR__ . '/conteoinventario/conteo.php';

//Oncologia
require __DIR__ . '/historiaclinica/tipoOrden.php';
require __DIR__ . '/esquemas/esquemas.php';

//Helpdesk
require __DIR__ . '/helpdesk/helpdesk.php';

//Redvital
require __DIR__ . '/redvital/redvital.php';

//Cuentas Medicas
require __DIR__ . '/medicalBills/medicalBills.php';
require __DIR__ . '/medicalBills/codigoglosas.php';
require __DIR__ . '/medicalBills/emailcmedicas.php';

//Pqrsf
require __DIR__ . '/pqrsfs/pqrsf.php';

//DOMICILIARIA
require __DIR__ . '/domiciliaria/domiciliaria.php';

//VAGOUT
require __DIR__ . '/vagout/productos.php';
require __DIR__ . '/vagout/tipofacturas.php';
require __DIR__ . '/vagout/categoriaproductos.php';
require __DIR__ . '/vagout/facturas.php';
require __DIR__ . '/vagout/inventario.php';

//ENTIDADES
require __DIR__ . '/entidades/entidades.php';

//Imagenologia
require __DIR__ . '/imagenologia/imagenologia.php';
require __DIR__ . '/eventos/evento.php';

//Empleados
require __DIR__ . '/empleados/empleado.php';

//SUMINTRANET
require __DIR__ . '/sumintranet/intranet.php';

//Covid
require __DIR__ . '/covid/covid.php';
require __DIR__ . '/covid/seguimiento.php';

//Caracterizacion
require __DIR__ . '/caracterizacion/caracterizacion.php';

//sarlaff
require __DIR__ . '/sarlaft/sarlaft.php';

//SeguimientoRed
require __DIR__ . '/seguimiento/red.php';

//Adjuntos
require __DIR__ . '/adjuntos/adjunto.php';

//Solicitudes
require __DIR__ . '/solicitudes/solicitud.php';

//202
require __DIR__ . '/m202/m202.php';

//Fias
require __DIR__ . '/fias/fias.php';

//Configuraciones

require __DIR__ . '/configuraciones/configuracion.php';
//Gestion de desarrollo
require __DIR__.'/desarrollo/desarrollo.php';

//Estadistica
require __DIR__ . '/estadisticas/estadistica.php';

//Asistencia educcativa
require __DIR__ . '/Asistencia/asistencia.php';

//Remision Programas PyP
require __DIR__ . '/remision/remision.php';

//Mi diagnostico
require __DIR__ . '/miDiagnostico/miDiagnostico.php';

//reporte citas
require __DIR__ . '/reporteCitas/reporteCitas.php';
require __DIR__ . '/reporteMedicamentos/reporteMedicamentos.php';

// tipo anexos
require __DIR__ . '/tipoAnexo/tipoAnexo.php';
require __DIR__ . '/adjuntoAnexo/adjuntoAnexo.php';

//clasificaciones

require __DIR__ . '/clasificaciones/clasificacion.php';


// cargue historias salud ocupacional
require __DIR__ . '/tipoAnexoSaludOcupacional/tipoAnexoSaludOcupacional.php';
require __DIR__ . '/adjuntoAnexoSaludOcupacional/adjuntoAnexoSaludOcupacional.php';

//recomendaciones cups
require __DIR__ . '/recomendacionCups/recomendacionCup.php';

