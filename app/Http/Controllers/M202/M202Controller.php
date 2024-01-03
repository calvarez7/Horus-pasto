<?php

namespace App\Http\Controllers\M202;

use App\Formats\Certificate202;
use App\Modelos\DetailsPackages_202;
use App\Modelos\IntentosCargue202;
use App\Modelos\Ocupacione;
use App\Modelos\Packages_202;
use App\Modelos\Paise;
use App\Modelos\Prestadore;
use App\Modelos\Sedeproveedore;
use Illuminate\Http\Request;
//use App\Formats\Certificate202;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\FastExcel;


class M202Controller extends Controller
{

    private $item = [];
    private $red;
    private $fechaDesde;
    private $fechaHasta;
    private $redAliada;
    private $m202 = [];
    private $inconsistencyMessages = [];


    private function setFechaDesde($desde)
    {
        $this->fechaDesde = $desde;
    }

    private function setFechaHasta($hasta)
    {
        $this->fechaHasta = $hasta;
    }


    private function setM202($m202)
    {
        $this->m202 = $m202;
    }

    public function validator(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        try {
            $fechaDesde = null;
            $fechaHasta = null;
            switch ($request->trimestre) {
                case '1':
                    $fechaDesde = $request->anio . '-01-01';
                    $fechaHasta = $request->anio . '-03-31';
                    break;
                case '2':
                    $fechaDesde = $request->anio . '-04-01';
                    $fechaHasta = $request->anio . '-06-30';
                    break;
                case '3':
                    $fechaDesde = $request->anio . '-07-01';
                    $fechaHasta = $request->anio . '-09-30';
                    break;
                case '4':
                    $fechaDesde = $request->anio . '-10-01';
                    $fechaHasta = $request->anio . '-12-31';
                    break;
            }

            $this->fillFileNames($request->all());
            $this->setFechaDesde($fechaDesde);
            $this->setFechaHasta($fechaHasta);

            $PrestadorPrincipal = Prestadore::select(['sp.*','prestadores.Nit'])
                ->join('sedeproveedores as sp','sp.Prestador_id','prestadores.id')
                ->where('sp.id',$request->ips)->first();

            IntentosCargue202::create([
                'codigo' => $PrestadorPrincipal->Codigo,
                'fecha_inicial' =>  $fechaDesde,
                'fecha_final' => $fechaHasta,
                'user_id' => auth()->user()->id
            ]);

            $info = [
                'provider' => $request->ips,
                'start_date' => $this->m202['content'][0][2],
                'finish_date' => $this->m202['content'][0][3]
            ];
            $this->checkFileStructure();

            if (!$this->areThereAnyInconsistencyMessages()) $this->checkFileContent();
            $files = [
                '202' => $this->m202,
                'red' => $PrestadorPrincipal
            ];

            if (count($this->m202['success']) > 0 && $this->areThereAnyInconsistencyMessages()) {

                return response()->json([
                    'message' => 'Hay inconsistencias en la validación',
                    'errors' => $this->inconsistencyMessages,
                    'provider' => $PrestadorPrincipal,
                    'files' => $files,
                    'parcial' => 1,
                    'info' => $info
//                'warning' => $this->waningMessages
                ],400);
            }

            if ($this->areThereAnyInconsistencyMessages()) {

                return response()->json([
                    'message' => 'Hay inconsistencias en la validación',
                    'errors' => $this->inconsistencyMessages,
                    'provider' => $PrestadorPrincipal,
                    'files' => $files,
                    'parcial' => 0,
                    'info' => $info
//                'warning' => $this->waningMessages
                ],400);
            }

            $files = [
                '202' => $this->m202,
                'red' => $PrestadorPrincipal
            ];

            return response()->json([
                'message' => 'Validación exitosa',
                'provider' => $PrestadorPrincipal,
                'files' => $files,
                'parcial' => 0,
                'info' => $info
//            'warning' => $this->waningMessages
            ], 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }

    private function fillFileNames($data)
    {
        $currentFile = [];
        $file = $data['files'];
        list($fileName, $ext) = explode('.', $file->getClientOriginalName());
        $open_file = fopen($file, 'r');
        if (filesize($file) > 0) {
            $content = fread($open_file, filesize($file));
            $rows = preg_split('/\r\n/', $content);

            $i = 0;
            $cols = [];
            $newCols = [];
            foreach ($rows as $row) {
                if ($row) {
                    $cols = explode('|', $row);
                    $j = 0;
                    foreach ($cols as $col) {
                        $newCols[$j] = utf8_encode(trim($col));
                        $j++;
                    }
                    $this->item[$fileName][$i] = $newCols;
                    $i++;
                }
                $currentFile['content'] = $this->item[$fileName];
                $currentFile['fileName'] = $fileName;
                $currentFile['size'] = $file->getSize();
                $currentFile['success'] = [];
                $this->setM202($currentFile);
            }
        } else {
            $msg = 'Archivo ' . $fileName . ' invalido';
            $this->pushErrorMessage($msg);
        }
    }

    private function checkFileStructure()
    {
        $line = 1;
        $fileName = $this->m202['fileName'];
        if (strlen($fileName) != 35) {
            $this->pushInconsistencyMessage('El nombre del archivo no cumple las especificaciones para el nombre del archivo.');
        }
        foreach ($this->m202['content'] as $row) {
            if ($line === 1) {
                if (count($row) != 5) {
                    $this->pushInconsistencyMessage('El registro de control no cumple las especificaciones de tipo de archivo plano', $fileName, $line, '', 'Error 000');
                }
            } else {
                if (count($row) != 119) {
                    $this->pushInconsistencyMessage('El registro de control no cumple las especificaciones de tipo de archivo plano', $fileName, $line, '', 'Error 000');
                }
            }

            $line++;
        }
    }

    private function checkFileContent()
    {
        $line = 0;
        $fileName = $this->m202['fileName'];
        foreach ($this->m202['content'] as $row) {
            $flagSuccess = 1;
            $flagComodin = true;
            if ($line === 0) {
                $flagComodin = 0;
                if (intval($row[0]) != 1) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Tipo de registro”.', $fileName, "cabezera", 1, 'Error 000');
                }
                if ($row[1] != 'RES004' && $row[1] != 'EAS027') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Código de las entidades que reportan”.', $fileName, "cabezera", 2, 'Error 000');
                }
                if (!$this->checkValidDateFormat($row[2])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Fecha inicial del período de la Información reportada”.', $fileName, "cabezera", 3, 'Error 000');
                } else {
                    if ($this->fechaDesde != $row[2]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Valor campo “Fecha inicial del período de la Información reportada” no coincide con el campo "Fecha Inicial" del formulario de carga.', $fileName, "cabezera", 3, 'Error 000');
                    }
                }
                if (!$this->checkValidDateFormat($row[3])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Fecha final del período de la información reportada”.', $fileName, "cabezera", 4, 'Error 000');
                } else {
                    if ($this->fechaHasta != $row[3]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Valor campo “Fecha final del período de la Información reportada” no coincide con el campo "Fecha Final" del formulario de carga.', $fileName, "cabezera", 3, 'Error 000');
                    }
                }
                if ($this->isThereAny('char', $row[4]) || $this->isThereAny('letter', $row[4])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Número total de registros de detalle contenidos en el archivo”.', $fileName, "cabezera", 5, 'Error 000');
                } else {
                    $totalRegistros = count($this->m202['content']) - 1;
                    if (intval($row[4]) !== $totalRegistros) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('La cantidad de registros en el campo “Número total de registros de detalle contenidos en el archivo” no coincide con la cantidad de registro en el archivo.', $fileName, "cabezera", 5, 'Error 000');
                    }
                }
            } else {
                $age = 0;
                $ageMonth = 0;
                $ageDay = 0;
                if (intval($row[0]) !== 2) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tipo de registro”.', $fileName, $line, 1, 'Error 001 Este campo no puede estar Nulo');
                }
//                if($line === 5){
////                    quitar esta
//                    $flagSuccess = 0;
//                    $this->pushInconsistencyMessage('Campo “Consecutivo”.', $fileName, $line, 2, 'Error 002 Este campo no puede estar Nulo');
//                }
                if ($row[1] == '') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Consecutivo”.', $fileName, $line, 2, 'Error 002 Este campo no puede estar Nulo');
                }
                $ipsPrimary = Sedeproveedore::where('Codigo', $row[2])->first();
                if (!$ipsPrimary) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de habilitación IPS primaria”.', $fileName, $line, 3, 'Error 021 La IPS primaria no existe');
                }
                if ($row[3] == '' || ($row[3] !== 'RC' && $row[3] !== 'TI' && $row[3] !== 'CE' && $row[3] !== 'CC' && $row[3] !== 'PA' && $row[3] !== 'MS' && $row[3] !== 'CD' && $row[3] !== 'AS' && $row[3] !== 'CN' && $row[3] !== 'MS' && $row[3] !== 'PE' && $row[3] !== 'SC') ) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tipo de identificación del usuario”.', $fileName, $line, 4, 'Error 003 Este campo tiene un valor no permitido');
                }
                if ($row[4] == '' || $this->isThereAny('char', $row[4])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Número de identificación del usuario”.', $fileName, $line, 5, 'Error 220 Número de identificación con caracteres no permitidos');
                } else {
                    if (!$this->checkValidDocumentNumberByDocumentType($row[3], $row[4])) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Número de identificación del usuario”.', $fileName, $line, 5, 'Error 676 La longitud del número de identificación no corresponde con el tipo de identificación');
                    }
                    $radicacionPeriodoVigente = DetailsPackages_202::join('packages_202s as p','details_packages_202s.package_202_id','p.id')
                        ->where('p.start_date',$this->fechaDesde)
                        ->where('p.final_date',$this->fechaHasta)
                        ->where('4',$row[4])
                        ->first();
                    if($radicacionPeriodoVigente){
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Número de identificación del usuario”.', $fileName, $line, 5, 'Error 000 El usuario ya cuenta con un registro previo del mismo periodo');
                    }
                }
                if ($row[5] == '' || $this->isThereAny('charExcludeSpace', $row[5])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Primer apellido del usuario”.', $fileName, $line, 6, 'Error 004 El campo “Primer apellido del usuario” tiene un valor no permitido');
                }
                if ($row[6] == '' || $this->isThereAny('charExcludeSpace', $row[6])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Segundo apellido del usuario”.', $fileName, $line, 7, 'Error 005 El campo “Segundo apellido del usuario” tiene un valor no permitido');
                }
                if ($row[7] == '' || $this->isThereAny('charExcludeSpace', $row[7])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Primer nombre del usuario”.', $fileName, $line, 8, 'Error 006 El campo “Primer nombre del usuario” tiene un valor no permitido');
                }
                if ($row[8] == '' || $this->isThereAny('charExcludeSpace', $row[8])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Segundo nombre del usuario”.', $fileName, $line, 9, 'Error 007 Este campo no puede estar Nulo');
                }
                if ($row[9] == '') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de Nacimiento”.', $fileName, $line, 10, 'Error 020 La fecha de nacimiento es requerida');
                } else {
                    if (!$this->checkValidDateFormat($row[9])) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de Nacimiento”.', $fileName, $line, 10, 'Error 421 Contenido en el campo FechaNacimiento  no válido');
                    } else {
                        if (!$this->checkValidDateFormat($this->m202['content'][0][3])) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Existe un valor no permitido en el campo “Fecha final del período de la información reportada”.', $fileName, $line, 10, 'Error 000');
                        } else {
                            $data = $this->actualAge($row[9]);
                            $age = $data['year'];
                            $ageMonth = $data['month'];
                            $ageDay = $data['day'];
                            if ($row[9] > $this->m202['content'][0][3]) {
                                $flagSuccess = 0;
                                $this->pushInconsistencyMessage('Campo “Fecha de Nacimiento”.', $fileName, $line, 10, 'Error 120 Fecha Nacimiento es mayor a la fecha de corte');
                            }
                        }
                    }
                }
                if ($row[10] == '' || ($row[10] !== 'F' && $row[10] !== 'M')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Sexo”.', $fileName, $line, 11, 'Error 008 Este campo tiene un valor no permitido');
                }
                if ($row[11] == '' || intval($row[11]) > 6 || intval($row[11]) < 1) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código pertenencia étnica”.', $fileName, $line, 12, 'Error 009 Este campo tiene un valor no permitido');
                }
                if ($row[12] == '') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de ocupación”.', $fileName, $line, 13, 'Error 010 Este campo no puede estar Nulo');
                }else{
                    $ocupacion = Ocupacione::where('codigo',$row[12])->where('estado_id',1)->first();
                    if (!$ocupacion) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Código de ocupación”.', $fileName, $line, 13, 'Error 010 El valor no registra en la lista de ocupacion');
                    }
                }
                if ($row[13] == '' || intval($row[13]) < 1 || intval($row[13]) > 13) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de nivel educativo”.', $fileName, $line, 14, 'Error 011 Este campo tiene un valor no permitido');
                }
                $flagComodin = $this->isComodin($row[14]);
                if ($row[14] == '') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Gestación” no puede estar vacio.', $fileName, $line, 15, 'Error 000');
                } else {
                    if ((intval($row[14]) === 1 || intval($row[14]) === 2 || intval($row[14]) === 21) && strtoupper($row[10]) !== 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Gestación”.', $fileName, $line, 15, 'Error 030 Si registra 1, 2 ó 21 en la variable Gestante, el sexo debe ser F');
                    }
                    if (($age < 10 || $age >= 60) && intval($row[14]) !== 0) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Gestación”.', $fileName, $line, 15, 'Error 222 Si la edad es menor de 10 años o mayor o igual de 60 años, Gestación No aplica');
                    }
                    if (strtoupper($row[10]) === 'F' && $age >= 10 && $age < 60 && intval($row[14]) === 0) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Gestación”.', $fileName, $line, 15, 'Error 223 Si el sexo es Femenino y la edad está entre 10 años hasta 59 años, debe registrar un valor diferente de 0-no aplica en Gestante.');
                    }
                    if (intval($row[14]) !== 1) {
                        if (intval($row[23]) !== 0 || $row[33] !== '1845-01-01' || intval($row[35]) !== 0 || $row[56] !== '1845-01-01' || $row[58] !== '1845-01-01' || intval($row[59]) !== 0 || intval($row[60]) !== 0 || intval($row[61]) !== 0) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Gestación”.', $fileName, $line, 15, 'Error 244 Si no es gestante, las variables relacionadas con la gestación deben registrar el valor equivalente a "No aplica"');
                        }
                    }
                    if (intval($row[14]) === 1) {
                        if (intval($row[23]) === 0 || $row[33] === '1845-01-01' || intval($row[35]) === 0 || $row[56] === '1845-01-01' || $row[58] === '1845-01-01' || intval($row[59]) === 0 || intval($row[60]) === 0 || intval($row[61]) === 0) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Gestación”.', $fileName, $line, 15, 'Error 379 Si es gestante, las variables relacionadas con la gestación deben registrar un dato diferente a "No aplica"');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[15]);
                if (intval($row[15]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Sífilis Gestacional o congénita”.', $fileName, $line, 16, 'Error 500 Error en valores permitidos -Sífilis Gestacional o congénita');
                }
                $flagComodin = $this->isComodin($row[16]);
                if (intval($row[16]) !== 4 && intval($row[16]) !== 5 && intval($row[16]) !== 21 && intval($row[16]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de prueba mini-mental state”.', $fileName, $line, 17, 'Error 504 Error en valores permitidos -Resultado del test minimental state');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ((intval($row[16]) === 4 || intval($row[16]) === 5) && $row[52] < '1900-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba mini-mental state”.', $fileName, $line, 17, 'Error 503 Si registra Resultado prueba mini-mental state, debe registrar una Fecha de consulta de valoración integral válida');
                    }
                    if (((intval($row[16]) === 4 || intval($row[16]) === 5 || intval($row[16]) === 21) && $calculatedAgeYear < 60) || (intval($row[16]) === 0 && $calculatedAgeYear >= 60)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba mini-mental state”.', $fileName, $line, 17, 'Error 227 El valor del Resultado prueba mini-mental state registrado no es acorde al rango de edad ');
                    }
                }
                $flagComodin = $this->isComodin($row[17]);
                if (intval($row[17]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Hipotiroidismo Congénito”.', $fileName, $line, 18, 'Error505 Error en valores permitidos - Hipotiroidismo Congénito');
                }
                $flagComodin = $this->isComodin($row[18]);
                if (intval($row[18]) === 1 && (intval($row[113]) === 4 || $row[112] === '1845-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Sintomático respiratorio (toda la población)”.', $fileName, $line, 19, 'Error 232 Si es sintomático respiratorio, el resultado de baciloscopia diagnóstica debe ser diferente a 4 y registrar fecha válida o comodín de no realización');
                }
                if (intval($row[18]) === 2 && (intval($row[113]) !== 4 || $row[112] !== '1845-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Sintomático respiratorio (toda la población)”.', $fileName, $line, 19, 'Error 506 Si no es Sintomático respiratorio, registre no en el  Resultado de baciloscopia de diagnóstico y registre no aplica en la Fecha de toma');
                }
                if (intval($row[18]) === 21 && (intval($row[113]) !== 21 || $row[112] !== '1800-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Sintomático respiratorio (toda la población)”.', $fileName, $line, 19, 'Error 507 Riesgo no evaluado en Sintomático respiratorio, registre riesgo no evaluado en resultado baciloscopia y sin dato en fecha baciloscopia');
                }
                $flagComodin = $this->isComodin($row[19]);
                if ($age < 12 && intval($row[19]) !== 98) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Consumo de tabaco”.', $fileName, $line, 20, 'Error 508 Si la edad es menor a 12 años, el registro de Consumo de tabaco no aplica.');
                }
                $flagComodin = $this->isComodin($row[20]);
                if (intval($row[20]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Lepra”.', $fileName, $line, 21, 'Error 509 Error en valores permitidos -Lepra');
                }
                $flagComodin = $this->isComodin($row[21]);
                if (intval($row[21]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Obesidad o Desnutrición Proteico-Calórica”.', $fileName, $line, 22, 'Error 510 Error en valores permitidos -Obesidad o Desnutrición Proteico Calórica');
                }
                $flagComodin = $this->isComodin($row[22]);
                if (intval($row[22]) !== 0 && intval($row[22]) !== 4 && intval($row[22]) !== 5 && intval($row[22]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 514 Error en valores permitidos - Resultado tacto rectal');
                } else {
                    if ((intval($row[22]) === 4 || intval($row[22]) === 5) && strtoupper($row[10]) !== 'M') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 037 Si registra 4, 5 o 21 en Resultado del tacto rectal, el sexo debe ser M');
                    }
                    if ($age < 40 && strtoupper($row[10]) === 'M' && (intval($row[22]) !== 0 || $row[64] !== '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 038 Si es hombre menor de 40 años, debe registrar  no aplica en el resultado y la fecha del tacto rectal');
                    }
                    if ($row[64] > '1845-01-01' && (strtoupper($row[10]) !== 'M' || $age < 40 || (intval($row[22]) !== 4 && intval($row[22]) !== 5))) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 237 Si registra fecha del tacto rectal, debe registrar el resultado del tacto rectal, el sexo debe ser M y la edad mayor o igual a 40 años');
                    }
                    if (intval($row[22]) === 21 && ($row[64] !== '1800-01-01' && $row[64] !== '1805-01-01' && $row[64] !== '1810-01-01' && $row[64] !== '1825-01-01' && $row[64] !== '1830-01-01' && $row[64] !== '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 512 Si Resultado de tacto rectal es riesgo no evaluado, en la fecha debe registrar alguno de los comodines que indican no realización o sin dato');
                    }
                    if (strtoupper($row[10]) === 'F' && (intval($row[22]) !== 0 || $row[64] !== '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado del tacto rectal”.', $fileName, $line, 23, 'Error 513 Si es sexo F, debe registrar no aplica en la fecha y el resultado de tacto rectal');
                    }
                }
                $flagComodin = $this->isComodin($row[23]);
                if (intval($row[23]) !== 0 && intval($row[23]) !== 1 && intval($row[23]) !== 2 && intval($row[23]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Ácido fólico preconcepcional”.', $fileName, $line, 24, 'Error 515 Error en valores permitidos - Acido fólico preconcepcional');
                }
                $flagComodin = $this->isComodin($row[24]);
                if (intval($row[24]) !== 0 && intval($row[24]) !== 4 && intval($row[24]) !== 5 && intval($row[24]) !== 6 && intval($row[24]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 25, 'Error 519 Error en valores permitidos - Resultado test de sangre oculta en materia fecal');
                } else {
                    if ($row[67] > '1900-01-01' && (intval($row[24]) !== 4 && intval($row[24]) !== 5 && intval($row[24]) !== 6)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 25, 'Error 516 Si registró Fecha de la prueba sangre oculta en materia fecal válida, debe registrar Resultado de la prueba sangre oculta en materia fecal');
                    }
                    if (($row[67] == '1800-01-01' || $row[67] == '1805-01-01' || $row[67] == '1810-01-01' || $row[67] == '1825-01-01' || $row[67] == '1830-01-01' || $row[67] == '1835-01-01') && ($age < 50 || $age > 76 || intval($row[24]) !== 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 25, 'Error 517 Si registró comodín de no realización o sin dato en Fecha prueba sangre oculta en materia fecal, la edad debe ser entre 50-75 y el Resultado debe ser 21');
                    }
                    if ($row[67] == '1845-01-01' && (intval($row[24]) !== 0 || ($age >= 50 && $age <= 75))) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 25, 'Error 518 Registre no aplica en Fecha de prueba sangre oculta en materia fecal, a los menores de 50 años ó mayores de 75 años sin riesgo identificado');
                    }
                }
                $flagComodin = $this->isComodin($row[25]);
                if (intval($row[25]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Enfermedad Mental”.', $fileName, $line, 26, 'Error 520 Error en valores permitidos -Enfermedad mental');
                }
                $flagComodin = $this->isComodin($row[26]);
                if (intval($row[26]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Cáncer de Cérvix”.', $fileName, $line, 27, 'Error 521 Error en valores permitidos -Cáncer de Cérvix');
                }
                $flagComodin = $this->isComodin($row[27]);
                if (intval($row[27]) !== 0 && intval($row[27]) !== 3 && intval($row[27]) !== 4 && intval($row[27]) !== 5 && intval($row[27]) !== 6 && intval($row[27]) !== 7 && intval($row[27]) !== 8 && intval($row[27]) !== 9 && intval($row[27]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo izquierdo”.', $fileName, $line, 28, 'Error 527 Error en valores permitidos - Agudeza visual lejana ojo izquierdo');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[62] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[62]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[62] <= '1900-01-01' && (intval($row[27]) === 3 || intval($row[27]) === 4 || intval($row[27]) === 5 || intval($row[27]) === 6 || intval($row[27]) === 7 || intval($row[27]) === 8 || intval($row[27]) === 9)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo izquierdo”.', $fileName, $line, 28, 'Error 524 Si registra Agudeza visual lejana ojo izquierdo, debe registrar Fecha de agudeza visual válida');
                    }
                    if (intval($row[27]) !== 21 && ($row[62] == '1800-01-01' || $row[62] == '1805-01-01' || $row[62] == '1810-01-01' || $row[62] == '1825-01-01' || $row[62] == '1830-01-01' || $row[62] == '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo izquierdo”.', $fileName, $line, 28, 'Error 525 Si registró un comodín que indica no realización o sin dato en Fecha de agudeza visual, registre 21 en Agudeza visual lejana ojo izquierdo');
                    }
                    if ($calculatedAgeYear < 3 && (intval($row[27]) !== 0 || $row[62] != '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo izquierdo”.', $fileName, $line, 28, 'Error 526 Si es menor a 3 años, debe registrar no aplica en las variables de agudeza visual lejana ojo izquierdo y fecha de agudeza visual');
                    }
                    if ($calculatedAgeYear >= 3 && (intval($row[27]) === 0 || $row[62] == '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo izquierdo”.', $fileName, $line, 28, 'Error 526 Si es menor a 3 años, debe registrar no aplica en las variables de agudeza visual lejana ojo izquierdo y fecha de agudeza visual');
                    }
                }
                $flagComodin = $this->isComodin($row[28]);
                if (intval($row[28]) !== 0 && intval($row[28]) !== 3 && intval($row[28]) !== 4 && intval($row[28]) !== 5 && intval($row[28]) !== 6 && intval($row[28]) !== 7 && intval($row[28]) !== 8 && intval($row[28]) !== 9 && intval($row[28]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo derecho”.', $fileName, $line, 29, 'Error 531 Error en valores permitidos - Agudeza visual lejana ojo derecho ');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[62] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[62]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[62] <= '1900-01-01' && (intval($row[28]) === 3 || intval($row[28]) === 4 || intval($row[28]) === 5 || intval($row[28]) === 6 || intval($row[28]) === 7 || intval($row[28]) === 8 || intval($row[28]) === 9)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Agudeza visual lejana ojo derecho”.', $fileName, $line, 29, 'Error 528 Si registra Agudeza visual lejana ojo derecho, debe registrar Fecha de agudeza visual válida');
                    }
                    if (intval($row[28]) !== 21 && ($row[62] == '1800-01-01' || $row[62] == '1805-01-01' || $row[62] == '1810-01-01' || $row[62] == '1825-01-01' || $row[62] == '1830-01-01' || $row[62] == '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Campo “Agudeza visual lejana ojo derecho”.', $fileName, $line, 29, 'Error 529 Si registró un comodín que indica no realización o sin dato en Fecha de agudeza visual, registre 21 en Agudeza visual lejana ojo derecho');
                    }
                    if ($calculatedAgeYear < 3 && (intval($row[28]) !== 0 || $row[62] != '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Campo “Agudeza visual lejana ojo derecho”.', $fileName, $line, 29, 'Error 530 Si es menor a 3 años, debe registrar no aplica en las variables de agudeza visual lejana ojo derecho y fecha de agudeza visual');
                    }
                }
                $flagComodin = $this->isComodin($row[29]);
                if (!$this->checkValidDateFormat($row[29])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Campo “Fecha del peso (Toda la población)”.', $fileName, $line, 30, 'Error 422 contenido en el campo FechaPeso  no válido');
                } else {
                    if ($row[29] > $this->m202['content'][0][3]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Campo “Fecha del peso (Toda la población)”.', $fileName, $line, 30, 'Error 121 Fecha peso es mayor a la fecha de corte');
                    }
                    if ($row[29] !== '1800-01-01') {
                        if ($row[29] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Campo “Fecha del peso (Toda la población)”.', $fileName, $line, 30, 'Error 171 Fecha peso es menor a la fecha de nacimiento');
                        }
                    } else {
//                            $this->pushWarningMessage('Campo “Campo “Fecha del peso (Toda la población)”.', $fileName, $line,30, 'alerta 674');
                    }
                }
                $flagComodin = $this->isComodin($row[30]);
                if (strlen($row[30]) > 5 || $this->isThereAny('charExclude.',$row[30])){
                    $this->pushInconsistencyMessage('Campo “Peso en Kilogramos (Toda la población)”.', $fileName, $line, 30, 'Error 041 valor no permitido en variable peso');
                }else{
                    if (floatval($row[30]) == 999 && $row[29] !== '1800-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Peso en Kilogramos (Toda la población)”.', $fileName, $line, 30, 'Error 041 Si no registra el peso de la persona no debe registrar fecha de medición');
                    }
                    if (floatval($row[30]) !== 999 && (floatval($row[30]) <= 0.2 || floatval($row[30]) > 250)) {
                        //   $this->pushWarningMessage('Campo “Peso en Kilogramos (Toda la población)”.', $fileName, $line, 'alerta 040');
                    }
                }
                $flagComodin = $this->isComodin($row[31]);
                if (!$this->checkValidDateFormat($row[31])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de la talla (Toda la población)”.', $fileName, $line, 32, 'Error 423 contenido en el campo FechaTalla  no válido');
                } else {
                    if ($row[31] > $this->m202['content'][0][3]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de la talla (Toda la población)”.', $fileName, $line, 32, 'Error 122 Fecha talla es mayor a la fecha de corte');
                    }
                    if ($row[31] !== '1800-01-01') {
                        if ($row[31] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de la talla (Toda la población)”.', $fileName, $line, 32, 'Error 172 Fecha talla es menor a la fecha de nacimiento');
                        }
                    } else {
//                            $this->pushWarningMessage('Campo “Fecha de la talla (Toda la población)”.', $fileName, $line, 'alerta 675');
                    }
                }
                $flagComodin = $this->isComodin($row[32]);
                if(strlen($row[32]) > 3 || intval($row[32]) <= 0){
                    $this->pushInconsistencyMessage('Campo “Talla en centímetros (Toda la población)”.', $fileName, $line, 33, 'Error 043 valor no permitido en variable talla');
                }else{
                    if ($row[32] == 999 && $row[31] !== '1800-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Talla en centímetros (Toda la población)”.', $fileName, $line, 33, 'Error 043 Si no registra la talla de la persona no debe registrar fecha de medición');
                    }
                    if ($row[32] != 999 && (floatval($row[32]) <= 20 || floatval($row[32]) > 225)) {
//                            $this->pushWarningMessage('Campo “Campo “Talla en centímetros (Toda la población)”.', $fileName, $line, 'alerta 042');
                    }
                }
                $flagComodin = $this->isComodin($row[33]);
                if (!$this->checkValidDateFormat($row[33])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha probable de parto”.', $fileName, $line, 34, 'Error 424 contenido en el campo FechaProbableParto no válido');
                } else {
                    if ($row[33] !== '1800-01-01' && $row[33] !== '1845-01-01') {
                        if ($row[33] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha probable de parto”.', $fileName, $line, 34, 'Error 170 Fecha probable parto es menor a la fecha de nacimiento');
                        }
                        $fechaCorte280 = date('Y-m-d', strtotime($this->m202['content'][0][3] . "+ 280 days"));
                        if ($row[33] > $fechaCorte280) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha probable de parto”.', $fileName, $line, 34, 'Error 242 Si registra fecha probable de parto no debe ser mayor a la fecha de corte más 280 días');
                        }
                        if ($row[33] > '1900-01-01' && $row[56] > '1900-01-01' && $row[33] <= $row[56]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha probable de parto”.', $fileName, $line, 34, 'Error 243 Fecha probable de parto es menor o igual a fecha de primera consulta prenatal');
                        }
                    }

                }
                $flagComodin = $this->isComodin($row[34]);
                if(intval($row[34]) !== 999){
                    $codigoPais = Paise::where('codigo',$row[34])->first();
                    if(!$codigoPais){
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Código país”.', $fileName, $line, 35, 'Error 532 El valor no registra en la lista de paises');
                    }else {
                        if (intval($row[34]) === 170 && ($row[3] === 'CE' || $row[3] === 'PA' || $row[3] === 'CD' || $row[3] === 'PE' || $row[3] === 'SC' || $row[3] === 'DE')) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Código país”.', $fileName, $line, 35, 'Error 532 El código país no es coherente con el tipo de identificación');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[35]);
                if (intval($row[35]) !== 0 && intval($row[35]) !== 4 && intval($row[35]) !== 5 && intval($row[35]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Clasificación del riesgo gestacional”.', $fileName, $line, 36, 'Error 533 Error en valores permitidos - Clasificación del riesgo gestacional');
                } else {
                    if ($row[56] < '1900-01-01' && $row[58] < '1900-01-01' && (intval($row[35]) === 4 || intval($row[35]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Clasificación del riesgo gestacional”.', $fileName, $line, 36, 'Error 534 Si registró clasificación de riesgo gestac., registre una fecha válida en la primera consulta prenatal o en el último control prenatal de seguimiento');
                    }
                }
                $flagComodin = $this->isComodin($row[36]);
                if (intval($row[36]) !== 0 && intval($row[36]) !== 2 && intval($row[36]) !== 3 && intval($row[36]) !== 4 && intval($row[36]) !== 5 && intval($row[36]) !== 6 && intval($row[36]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de colonoscopia tamizaje”.', $fileName, $line, 37, 'Error 538 Error en valores permitidos - Resultado colonoscopia de tamizaje');
                } else {
                    if ($row[66] > '1900-01-01' && (intval($row[36]) === 0 || intval($row[36]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de colonoscopia tamizaje”.', $fileName, $line, 37, 'Error 535 Si registra Fecha de colonoscopia válida, registre el resultado de colonoscopia de tamizaje');
                    }
                    if (($row[66] == '1800-01-01' || $row[66] == '1805-01-01' || $row[66] == '1810-01-01' || $row[66] == '1825-01-01' || $row[66] == '1830-01-01' || $row[66] == '1835-01-01') && (($age < 50 || $age > 75) || intval($row[36]) !== 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de colonoscopia tamizaje”.', $fileName, $line, 37, 'Error 536 Si registró comodín de no realización o sin dato en Fecha de colonoscopia, la edad debe estar entre 50 y 75 y resultado de colonoscopia debe ser 21');
                    }
                    if ($row[66] == '1845-01-01' && (intval($row[36]) !== 0 || ($age >= 50 && $age <= 75))) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de colonoscopia tamizaje”.', $fileName, $line, 37, 'Error 537 Si registró comodín de no aplica en Fecha de colonoscopia, el resultado de colonoscopia debe ser 0 y la edad debe ser <= 50 ó > 75 años ');
                    }
                }
                $flagComodin = $this->isComodin($row[37]);
                if (intval($row[37]) !== 0 && intval($row[37]) !== 4 && intval($row[37]) !== 5 && intval($row[37]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de tamizaje auditivo neonatal”.', $fileName, $line, 38, 'Error 540 Error en valores permitidos - Resultado de tamizaje auditivo neonatal');
                } else {
                    if ($row[69] <= '1900-01-01' && (intval($row[37]) === 4 || intval($row[37]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje auditivo neonatal”.', $fileName, $line, 38, 'Error 539 Si registra Resultado de tamizaje auditivo neonatal, debe registrar fecha de tamizaje');
                    }
                    if ($row[69] > '1900-01-01' && $ageDay > 30 && (intval($row[37]) === 4 || intval($row[37]) === 5)) {
                        //                            $this->pushWarningMessage('Campo “Resultado de tamizaje auditivo neonatal”.', $fileName, $line, 'alerta 541');
                    }
                    if (intval($row[37]) === 21 && ($row[69] != '1800-01-01' && $row[69] != '1805-01-01' && $row[69] != '1810-01-01' && $row[69] != '1825-01-01' && $row[69] != '1830-01-01' && $row[69] != '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje auditivo neonatal”.', $fileName, $line, 38, 'Error 542 Si Resultado de tamizaje auditivo neonatal es 21, debe registrar un comodín que indique no realización o sin dato en fecha de tamizaje');
                    }
                }
                $flagComodin = $this->isComodin($row[38]);
                if (intval($row[38]) !== 0 && intval($row[38]) !== 4 && intval($row[38]) !== 5 && intval($row[38]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de tamizaje visual neonatal”.', $fileName, $line, 39, 'Error 544 Error en valores permitidos - Resultado de tamizaje visual neonatal');
                } else {
                    if ($row[75] <= '1900-01-01' && (intval($row[38]) === 4 || intval($row[38]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje visual neonatal”.', $fileName, $line, 39, 'Error 543 Si registra Resultado de tamizaje visual neonatal, debe registrar fecha de tamizaje');
                    }
                    if ($row[75] > '1900-01-01' && $ageDay > 30 && (intval($row[38]) === 4 || intval($row[38]) === 5)) {
                        //                            $this->pushWarningMessage('Campo “Resultado de tamizaje visual neonatal”.', $fileName, $line, 'alerta 545');
                    }
                    if (intval($row[38]) === 21 && ($row[75] != '1800-01-01' && $row[75] != '1805-01-01' && $row[75] != '1810-01-01' && $row[75] != '1825-01-01' && $row[75] != '1830-01-01' && $row[75] != '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje auditivo neonatal”.', $fileName, $line, 39, 'Error 546 Si Resultado de tamizaje visual neonatal es riesgo no evaluado, debe registrar un comodín que indique no realización o sin dato en fecha del tamizaje');
                    }
                }
                $flagComodin = $this->isComodin($row[39]);
                if (intval($row[39]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “DPT menores de 5 años”.', $fileName, $line, 40, 'Error 574 Error en valores permitidos - DPT menores de 5 años');
                }
                $flagComodin = $this->isComodin($row[40]);
                if (intval($row[40]) !== 0 && intval($row[40]) !== 4 && intval($row[40]) !== 5 && intval($row[40]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de tamizaje VALE”.', $fileName, $line, 41, 'Error 549 Error en valores permitidos - Resultado de tamizaje VALE');
                } else {
                    if ((intval($row[40]) === 4 || intval($row[40]) === 5) && ($age >= 13 || $row[63] <= '1900-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje VALE”.', $fileName, $line, 41, 'Error 548 Si registra Resultado de tamizaje VALE, debe registrar Fecha de tamizaje VALE y ser menor a 13 años (hasta 12 años, 11 meses y 29 días)');
                    }
                    if (intval($row[40]) == 0 && ($age < 13 || $row[63] != '1845-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje VALE”.', $fileName, $line, 41, 'Error 550 Si Resultado de tamizaje VALE no aplica, debe registrar Fecha de tamizaje VALE  no aplica y ser mayor de 12 años');
                    }
                    if (intval($row[40]) == 21 && ($age >= 13 || ($row[63] != '1800-01-01' && $row[63] != '1805-01-01' && $row[63] != '1810-01-01' && $row[63] != '1825-01-01' && $row[63] != '1830-01-01' && $row[63] != '1835-01-01'))) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje VALE”.', $fileName, $line, 41, 'Error 551 Si Resultado de tamizaje VALE es 21, debe ser menor a 13 años y registrar comodín que indique no realización o sin dato en fecha del tamizaje');
                    }
                }
                $flagComodin = $this->isComodin($row[41]);
                if (intval($row[41]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Neumococo”.', $fileName, $line, 42, 'Error 552 Error en valores permitidos -Neumococo');
                }
                $flagComodin = $this->isComodin($row[42]);
                if (intval($row[42]) !== 0 && intval($row[42]) !== 4 && intval($row[42]) !== 5 && intval($row[42]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de tamizaje para hepatitis C”.', $fileName, $line, 43, 'Error 557 Error en valores permitidos - Resultado de tamizaje para hepatitis C');
                } else {
                    if ($row[110] > '1900-01-01' && (intval($row[42]) === 0 || intval($row[42]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje para hepatitis C”.', $fileName, $line, 43, 'Error 553 Si registrar Fecha de tamizaje de hepatitis C, debe registrar un resultado de tamizaje para hepatitis C');
                    }
                    if (intval($row[42]) !== 21 && ($row[110] == '1800-01-01' || $row[110] == '1805-01-01' || $row[110] == '1810-01-01' || $row[110] == '1825-01-01' || $row[110] == '1830-01-01' || $row[110] == '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje para hepatitis C”.', $fileName, $line, 43, 'Error 554 Si registra un comodín que indique no realización o sin dato en Fecha de tamizaje, registre 21 en Resultado de tamizaje para hepatitis C');
                    }
                    if ($row[9] > '1996-01-01' && (intval($row[42]) !== 0 || $row[110] !== '1845-01-01')) {
                        //                            $this->pushWarningMessage('Campo “Resultado de tamizaje para hepatitis C”.', $fileName, $line, 'alerta 555');
                    }
                    if ($row[110] == '1845-01-01' && intval($row[42]) !== 0) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamizaje para hepatitis C”.', $fileName, $line, 43, 'Error 556 Si Fecha de tamizaje de Hepatitis C no aplica, Resultado de tamizaje no aplica.');
                    }
                }
                $flagComodin = $this->isComodin($row[43]);
                if (intval($row[43]) !== 0 && intval($row[43]) !== 3 && intval($row[43]) !== 4 && intval($row[43]) !== 5 && intval($row[43]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad gruesa”.', $fileName, $line, 44, 'Error 559 Error en valores permitidos -Resultado de escala abreviada de desarrollo área de motricidad gruesa');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[52] <= '1900-01-01' && (intval($row[43]) === 3 || intval($row[43]) === 4 || intval($row[43]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad gruesa”.', $fileName, $line, 44, 'Error 558 Si registra Resultado de escala abreviada de desarrollo área de motricidad gruesa, debe registrar una fecha de consulta de valoración integral válida');
                    }
                    if (((intval($row[43]) === 3 || intval($row[43]) === 4 || intval($row[43]) === 5 || intval($row[43]) === 21) && $calculatedAgeYear >= 8) || (intval($row[43]) === 0 && $calculatedAgeYear < 8)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad gruesa”.', $fileName, $line, 44, 'Error 560 Verifique el resultado de escala abreviada de desarrollo área de motricidad gruesa, de acuerdo a la edad');
                    }
                    if (intval($row[43]) === 21 && $calculatedAgeYear < 8 && ($row[52] != '1800-01-01' && $row[52] != '1805-01-01' && $row[52] != '1810-01-01' && $row[52] != '1825-01-01' && $row[52] != '1830-01-01' && $row[52] != '1835-01-01')) {
                        //                            $this->pushWarningMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad gruesa”.', $fileName, $line, 'alerta 561');
                    }
                }
                $flagComodin = $this->isComodin($row[44]);
                if (intval($row[44]) !== 0 && intval($row[44]) !== 3 && intval($row[44]) !== 4 && intval($row[44]) !== 5 && intval($row[44]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad fino-adaptativa”.', $fileName, $line, 45, 'Error 563 Error en valores permitidos -Resultado de escala abreviada de desarrollo área personal social');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[52] <= '1900-01-01' && (intval($row[44]) === 3 || intval($row[44]) === 4 || intval($row[44]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad fino-adaptativa”.', $fileName, $line, 45, 'Error 562 Si registra Resultado escala abreviada desarrollo área motricidad finoadaptativa, debe registrar una fecha de consulta de valoración integral válida');
                    }
                    if (((intval($row[44]) === 3 || intval($row[44]) === 4 || intval($row[44]) === 5 || intval($row[44]) === 21) && $calculatedAgeYear >= 8) || (intval($row[44]) === 0 && $calculatedAgeYear < 8)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad fino-adaptativa”.', $fileName, $line, 45, 'Error 564 Verifique el resultado de escala abreviada de desarrollo área de motricidad finoadaptativa, de acuerdo a la edad');
                    }
                    if (intval($row[44]) === 21 && $age < 8 && ($row[52] != '1800-01-01' && $row[52] != '1805-01-01' && $row[52] != '1810-01-01' && $row[52] != '1825-01-01' && $row[52] != '1830-01-01' && $row[52] != '1835-01-01')) {
                        //                            $this->pushWarningMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad fino-adaptativa”.', $fileName, $line, 'alerta 565');
                    }
                }
                $flagComodin = $this->isComodin($row[45]);
                if (intval($row[45]) !== 0 && intval($row[45]) !== 3 && intval($row[45]) !== 4 && intval($row[45]) !== 5 && intval($row[45]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área personal social”.', $fileName, $line, 46, 'Error 567 Error en valores permitidos -Resultado de escala abreviada de desarrollo área personal social');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[52] <= '1900-01-01' && (intval($row[45]) === 3 || intval($row[45]) === 4 || intval($row[45]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área personal social”.', $fileName, $line, 46, 'Error 566 Si registra Resultado de escala abreviada de desarrollo área personal social, debe registrar una fecha de consulta de valoración integral válida');
                    }
                    if (((intval($row[45]) === 3 || intval($row[45]) === 4 || intval($row[45]) === 5 || intval($row[45]) === 21) && $calculatedAgeYear >= 8) || (intval($row[45]) === 0 && $calculatedAgeYear < 8)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área personal social”.', $fileName, $line, 46, 'Error 568 Verifique el resultado de escala abreviada de desarrollo área personal social, de acuerdo con la edad');
                    }
                    if (intval($row[45]) === 21 && $calculatedAgeYear < 8 && ($row[52] != '1800-01-01' && $row[52] != '1805-01-01' && $row[52] != '1810-01-01' && $row[52] != '1825-01-01' && $row[52] != '1830-01-01' && $row[52] != '1835-01-01')) {
                        //                            $this->pushWarningMessage('Campo “Resultado de escala abreviada de desarrollo área personal social”.', $fileName, $line, 'alerta 569');
                    }
                }
                $flagComodin = $this->isComodin($row[46]);
                if (intval($row[46]) !== 0 && intval($row[46]) !== 3 && intval($row[46]) !== 4 && intval($row[46]) !== 5 && intval($row[46]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad audición lenguaje”.', $fileName, $line, 47, 'Error 571 Error en valores permitidos -Resultado de escala abreviada de desarrollo área de motricidad audición lenguaje');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[52] <= '1900-01-01' && (intval($row[46]) === 3 || intval($row[46]) === 4 || intval($row[46]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad audición lenguaje”.', $fileName, $line, 47, 'Error 570 Si registra Resultado escala abreviada de desarrollo área motricidad audición lenguaje, registre una fecha de consulta de valoración integral válida');
                    }
                    if (((intval($row[46]) === 3 || intval($row[46]) === 4 || intval($row[46]) === 5 || intval($row[46]) === 21) && $calculatedAgeYear >= 8) || (intval($row[46]) === 0 && $calculatedAgeYear < 8)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad audición lenguaje”.', $fileName, $line, 47, 'Error 572 Verifique el resultado de escala abreviada de desarrollo área de motricidad audición lenguaje, de acuerdo a la edad');
                    }
                    if (intval($row[46]) === 21 && $calculatedAgeYear < 8 && ($row[52] != '1800-01-01' && $row[52] != '1805-01-01' && $row[52] != '1810-01-01' && $row[52] != '1825-01-01' && $row[52] != '1830-01-01' && $row[52] != '1835-01-01')) {
                        //                            $this->pushWarningMessage('Campo “Resultado de escala abreviada de desarrollo área de motricidad audición lenguaje”.', $fileName, $line, 'alerta 573');
                    }
                }

                $flagComodin = $this->isComodin($row[47]);
                if (intval($row[47]) !== 0 && intval($row[47]) !== 6 && intval($row[47]) !== 7 && intval($row[47]) !== 8 && intval($row[47]) !== 9 && intval($row[47]) !== 10 && intval($row[47]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tratamiento ablativo o de escisión posterior a la realización de la técnica de inspección visual (Crioterapia o LETZ)”.', $fileName, $line, 48, 'Error 576 Error en valores permitidos -Tratamiento ablativo o de escisión posterior a la realización técnica de inspección visual (Crioterapia o LETZ)');
                } else {
                    if (intval($row[86]) === 3 && intval($row[88]) === 19 && intval($row[47]) !== 0) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Tratamiento ablativo o de escisión posterior a la realización de la técnica de inspección visual (Crioterapia o LETZ)”.', $fileName, $line, 48, 'Error 673 Si el resultado de la técnica de inspección visual es positivo, debe registrar tratamiento.');
                    }
                    if (intval($row[88]) !== 19 && intval($row[47]) !== 0) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Tratamiento ablativo o de escisión posterior a la realización de la técnica de inspección visual (Crioterapia o LETZ)”.', $fileName, $line, 48, 'Error 575 Si el resultado de la técnica de inspección visual es negativo o usó otra técnica, debe registrar no aplica en tratamiento ablativo o escisión.');
                    }
                    if (intval($row[47]) !== 0 && $row[10] !== 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Tratamiento ablativo o de escisión posterior a la realización de la técnica de inspección visual (Crioterapia o LETZ)”.', $fileName, $line, 48, 'Error 047 Si reporta Tratamiento ablativo o de escisión posterior a la realización técnica de inspección visual (Crioterapia o LETZ) , el sexo debe ser F');
                    }
                }
                $flagComodin = $this->isComodin($row[48]);
                if (intval($row[48]) !== 0 && intval($row[48]) !== 4 && intval($row[48]) !== 5 && intval($row[48]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de tamización con oximetría pre y post ductal”.', $fileName, $line, 49, 'Error 579 Error en valores permitidos - Resultado de tamización con oximetría pre y post ductal');
                } else {
                    if ($row[65] <= '1900-01-01' && (intval($row[48]) === 4 || intval($row[48]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamización con oximetría pre y post ductal”.', $fileName, $line, 49, 'Error 578 Si registra Resultado de tamización con oximetría pre y post ductal, debe registrar la fecha del tamizaje');
                    }
                    if ($row[65] > '1900-01-01' && $ageDay > 30 && (intval($row[48]) === 4 || intval($row[48]) === 5)) {
                        // $this->pushWarningMessage('Campo “Resultado de tamización con oximetría pre y post ductal”.', $fileName, $line, 'alerta 580');
                    }
                    if (intval($row[48]) === 21 && ($row[65] != '1800-01-01' && $row[65] != '1805-01-01' && $row[65] != '1810-01-01' && $row[65] != '1825-01-01' && $row[65] != '1830-01-01' && $row[65] != '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de tamización con oximetría pre y post ductal”.', $fileName, $line, 49, 'Error 581 Si Resultado de tamización con oximetría pre y pos ductual es 21, registre un comodín que indique no realización o sin dato en la fecha del tamizaje');
                    }
                }
                $flagComodin = $this->isComodin($row[49]);
                if (!$this->checkValidDateFormat($row[49])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 425 contenido en el campo FechaPartoCesarea no válido');
                } else {
                    if ($row[49] !== '1800-01-01' && $row[49] !== '1845-01-01') {
                        if ($row[49] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 123 Fecha atención parto o cesárea es mayor a la fecha de corte del reporte');
                        }
                        if ($row[49] !== '1845-01-01' && $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 049 Si registra Fecha de atención parto o cesárea, el sexo debe ser F');
                        }
                        if ($row[49] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 173 Fecha atención parto o cesárea es menor a la fecha de nacimiento');
                        }
                        if ($row[49] !== '1845-01-01' && ($age < 10 || $age > 60)) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 294 Si registra Fecha de atención parto ó cesárea, debe ser mayor o igual de 10 años y menor de 60 años');
                        }
                        if ($row[49] !== '1845-01-01' && $row[50] === '1845-01-01') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención parto o cesárea”.', $fileName, $line, 50, 'Error 296 Si registra fecha de atención parto ó cesárea, debe registrar fecha de salida de atención parto o cesárea');
                        }
                    }

                }
                $flagComodin = $this->isComodin($row[50]);
                if (!$this->checkValidDateFormat($row[50])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 426 contenido en el campo FechaSalidaPartoCesarea  no válido');
                } else {
                        if ($row[50] !== '1845-01-01'  && $row[50] !== '1800-01-01' &&  $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 050 Si registra fecha de salida de atención de parto el sexo debe ser F');
                        }
                        if ($row[50] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 124 Fecha salida del parto o cesárea es mayor a la fecha de corte');
                        }
                        if ($row[50] !== '1845-01-01' && $row[50] !== '1800-01-01' && $row[50] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 174 Fecha salida del parto o cesárea es menor a la fecha de nacimiento');
                        }
                        if ($row[50] !== '1845-01-01' && $row[50] !== '1800-01-01' && ($age < 10 && $age >= 60)) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 299 Si registra Fecha de salida de atención parto o cesárea, debe ser mayor o igual de 10 años y menor de 60 años');
                        }
                        if ($row[49] > '1900-01-01' && $row[50] > '1900-01-01') {
                            if ($row[49] > $row[50]) {
                                $flagSuccess = 0;
                                $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 300 Fecha de salida de parto es menor a fecha de atención del parto');
                            }
                        }
                        if (($row[50] !== '1845-01-01' && $row[50] !== '1800-01-01') && ($row[49] == '1845-01-01' || $row[49] == '1800-01-01')) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de salida de atención parto o cesárea”.', $fileName, $line, 51, 'Error 301 Si registra Fecha de salida de atención parto o cesárea, debe registrar Fecha de atención parto o cesárea');
                        }
                }
                $flagComodin = $this->isComodin($row[51]);
                if (!$this->checkValidDateFormat($row[51])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la promoción y apoyo de la lactancia materna”.', $fileName, $line, 52, 'Error 427 contenido en el campo fecha de atención para la consejería en lactancia materna  no válido');
                } else {
                    if ($row[51] !== '1800-01-01' && $row[51] !== '1805-01-01' && $row[51] !== '1810-01-01' && $row[51] !== '1825-01-01' && $row[51] !== '1830-01-01' && $row[51] !== '1835-01-01' && $row[51] !== '1845-01-01') {
                        if ($row[51] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la promoción y apoyo de la lactancia materna”.', $fileName, $line, 52, 'Error 125 Fecha atención en salud para la promoción y apoyo de la lactancia materna es mayor a la fecha de corte del reporte');
                        }
                        if ($row[51] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la promoción y apoyo de la lactancia materna”.', $fileName, $line, 52, 'Error 175 Fecha atención en salud para la promoción y apoyo de la lactancia materna es menor a la fecha de nacimiento');
                        }
                    }
                    if (intval($row[14]) !== 1 && $ageMonth >= 7 && $row[51] !== '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la promoción y apoyo de la lactancia materna”.', $fileName, $line, 52, 'Error 583 La atención en salud para la promoción y apoyo de la lactancia materna no aplica para la edad de la persona porque no es gestante');
                    }
                    if ($row[51] !== '1845-01-01' && $row[10] === 'M' && $ageMonth >= 7) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la promoción y apoyo de la lactancia materna”.', $fileName, $line, 52, 'Error 051 Registre Fecha atención en salud para la promoción y apoyo de la lactancia materna no aplica a personas de sexo M con edad mayor a 7 meses');
                    }
                }
                $flagComodin = $this->isComodin($row[52]);
                if (!$this->checkValidDateFormat($row[52])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de consulta de valoración integral”.', $fileName, $line, 53, 'Error 447 contenido en el campo Consulta de valoración integral no válido');
                } else {
                    if ($row[52] !== '1800-01-01' && $row[52] !== '1805-01-01' && $row[52] !== '1810-01-01' && $row[52] !== '1825-01-01' && $row[52] !== '1830-01-01' && $row[52] !== '1835-01-01' && $row[52] !== '1845-01-01') {
                        if ($row[52] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de consulta de valoración integral”.', $fileName, $line, 53, 'Error 126 Fecha de consulta de valoración integral es mayor a la fecha de corte');
                        }
                        if ($row[52] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de consulta de valoración integral”.', $fileName, $line, 53, 'Error 176 Fecha de consulta de valoración integral es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[53]);
                if (!$this->checkValidDateFormat($row[53])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la asesoría en anticoncepción”.', $fileName, $line, 54, 'Error 448 contenido en el campo Fecha de atención para la asesoría en anticoncepción no válido');
                } else {
                    $calculatedAgeYear = $age;
                    if($row[53] > '1900-01-01'){
                        $dateCalculated = $this->calculatedAge($row[9],$row[53]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
//                    if ($row[53] !== '1800-01-01' && $row[53] !== '1805-01-01' && $row[53] !== '1810-01-01' && $row[53] !== '1825-01-01' && $row[53] !== '1830-01-01' && $row[53] !== '1835-01-01' && $row[53] !== '1845-01-01') {
                        if ($row[53] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la asesoría en anticoncepción”.', $fileName, $line, 54, 'Error 127 La fecha de la Atención en salud para la asesoría en anticoncepción es mayor a la fecha de corte');
                        }
                        if ($row[53] > '1845-01-01' && $row[53] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la asesoría en anticoncepción”.', $fileName, $line, 54, 'Error 177 La fecha de la Atención en salud para la asesoría en anticoncepción es menor a la fecha de nacimiento');
                        }
                        if ($row[53] == '1845-01-01' && $calculatedAgeYear >= 10) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la asesoría en anticoncepción”.', $fileName, $line, 54, 'Error 305 Verifique la Fecha de Atención en salud para la asesoría en anticoncepción con reporte "no aplica" en población mayor o igual de 10 años');
                        }
//                    } else {
                        if ($row[53] !== '1845-01-01' && $calculatedAgeYear < 10) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud para la asesoría en anticoncepción”.', $fileName, $line, 54, 'Error 304 Verifique la actividad Fecha de Atención en salud para la asesoría en anticoncepción reportada en población menor de 10 años');
                        }
//                    }
                }
                $flagComodin = $this->isComodin($row[54]);
                if ($row[55] !== '1845-01-01' && intval($row[54]) === 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de método anticonceptivo”.', $fileName, $line, 55, 'Error 309 Si registra fecha de suministro de método anticonceptivo, debe registra un dato diferente a 0 en el suministro');
                }
                if (intval($row[54]) !== 0 && $row[55] > '1900-01-01' && ($age < 10 || $age >= 60)) {
//                        $this->pushWarningMessage('Campo “Suministro de método anticonceptivo”.', $fileName, $line, 'Error 307');
                }
                if ($row[54] === '0' && ($age >= 10 && $age <= 59)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de método anticonceptivo”.', $fileName, $line, 55, 'Error 308 No es válido registrar no aplica en suministro de método anticonceptivo si la edad está entre 10 y 59 años ');
                }
                $flagComodin = $this->isComodin($row[55]);
                if (!$this->checkValidDateFormat($row[55])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de suministro de método anticonceptivo”.', $fileName, $line, 56, 'Error 428 contenido en el campo FechaSuministroMetodoAnticonceptivo no válido');
                } else {
                    if ($row[55] !== '1800-01-01' && $row[55] !== '1805-01-01' && $row[55] !== '1810-01-01' && $row[55] !== '1825-01-01' && $row[55] !== '1830-01-01' && $row[55] !== '1835-01-01' && $row[55] !== '1845-01-01') {
                        if ($row[55] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de suministro de método anticonceptivo”.', $fileName, $line, 56, 'Error 128 Fecha suministro método anticonceptivo es mayor a la fecha de corte del reporte');
                        }
                        if ($row[55] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de suministro de método anticonceptivo”.', $fileName, $line, 56, 'Error 178 Fecha suministro método anticonceptivo es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[55] == '1845-01-01' && intval($row[54]) !== 0) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de suministro de método anticonceptivo”.', $fileName, $line, 56, 'Error 306 Si resgistró no aplica (1845-01-01) en la fecha de suministro de método, debe registrar no aplica (0) en el método anticonceptivo suministrado. ');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[56]);
                if (!$this->checkValidDateFormat($row[56])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de primera consulta prenatal”.', $fileName, $line, 57, 'Error 449 contenido en el campo primera consulta prenatal no válido');
                } else {

                    if ($row[56] !== '1800-01-01' && $row[56] !== '1805-01-01' && $row[56] !== '1810-01-01' && $row[56] !== '1825-01-01' && $row[56] !== '1830-01-01' && $row[56] !== '1835-01-01' && $row[56] !== '1845-01-01' && $row[56] !== '1900-01-01') {
                        if ($row[56] > '1900-01-01' && $row[58] > '1900-01-01' && $row[56] > $row[58]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de primera consulta prenatal”.', $fileName, $line, 57, 'Error 318 Fecha de ultimo control prenatal de seguimiento es menor a Fecha de primera consulta prenatal');
                        }
                        if ($row[56] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de primera consulta prenatal”.', $fileName, $line, 57, 'Error 129 Fecha de primera consulta prenatal es mayor a la fecha de corte');
                        }
                        if ($row[56] <= $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de primera consulta prenatal”.', $fileName, $line, 57, 'Error 179 Fecha de primera consulta prenatal es menor a la fecha de nacimiento');
                        }
                    }

                }
                $flagComodin = $this->isComodin($row[57]);
                if ($row[105] > '1900-01-01' && (intval($row[57]) === 0 || $row[57] === 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de glicemia basal”.', $fileName, $line, 58, 'Error 584 Si registra Fecha de toma de glicemia basal, debe registrar el Resultado de glicemia basal');
                }
                if ((($row[105] == '1800-01-01' || $row[105] == '1805-01-01' || $row[105] == '1810-01-01' || $row[105] == '1825-01-01' || $row[105] == '1830-01-01' || $row[105] == '1835-01-01') && intval($row[57]) !== 998) || (intval($row[57]) === 998 && ($row[105] != '1800-01-01' && $row[105] != '1805-01-01' && $row[105] != '1810-01-01' && $row[105] != '1825-01-01' && $row[105] != '1830-01-01' && $row[105] != '1835-01-01'))) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de glicemia basal”.', $fileName, $line, 58, 'Error 585 Si registró 1800-01-01, 1805-01-01, 1810-01-01, 1825-01-01, 1830-01-01 ó 1835-01-01 en Fecha Glicemia Basal, registre 998 en Resultado GB y viceversa');
                }
                if ($row[105] == '1845-01-01' && (intval($row[57]) !== 0 || $age >= 29)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de glicemia basal”.', $fileName, $line, 58, 'Error 586 Registre no aplica en Fecha toma de glicemia y Resultado de glicemia, a los menores de 29 años sin riesgo cardiovascular identificado');
                }
                $flagComodin = $this->isComodin($row[58]);
                if (!$this->checkValidDateFormat($row[58])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de último control prenatal de seguimiento”.', $fileName, $line, 59, 'Error 450 contenido en el  UltimoControlPrenatal no válido');
                } else {
                    if ($row[58] !== '1800-01-01' && $row[58] !== '1845-01-01') {
                        if ($row[58] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de último control prenatal de seguimiento”.', $fileName, $line, 59, 'Error 130 Fecha de último control prenatal de seguimiento es mayor a la fecha de corte');
                        }
                        if ($row[58] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de último control prenatal de seguimiento”.', $fileName, $line, 59, 'Error 180 Fecha de último control prenatal de seguimiento es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[59]);
                if (intval($row[59]) !== 0 && intval($row[59]) !== 1 && intval($row[59]) !== 16 && intval($row[59]) !== 17 && intval($row[59]) !== 18 && intval($row[59]) !== 20 && intval($row[59]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de ácido fólico en el control prenatal durante el periodo reportado”.', $fileName, $line, 60, 'Error 587 Error en valores permitidos -Suministro de ácido fólico en el control prenatal durante el periodo reportado');
                }
                $flagComodin = $this->isComodin($row[60]);
                if (intval($row[60]) !== 0 && intval($row[60]) !== 1 && intval($row[60]) !== 16 && intval($row[60]) !== 17 && intval($row[60]) !== 18 && intval($row[60]) !== 20 && intval($row[60]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de sulfato ferroso en el control prenatal durante el periodo reportado”.', $fileName, $line, 61, 'Error 588 Error en valores permitidos -Suministro de sulfato ferroso en el control prenatal durante el periodo reportado');
                }
                $flagComodin = $this->isComodin($row[61]);
                if (intval($row[61]) !== 0 && intval($row[61]) !== 1 && intval($row[61]) !== 16 && intval($row[61]) !== 17 && intval($row[61]) !== 18 && intval($row[61]) !== 20 && intval($row[61]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de carbonato de calcio en el control prenatal durante el periodo reportado”.', $fileName, $line, 62, 'Error 589 Error en valores permitidos -Suministro de carbonato de calcio en el control prenatal durante el periodo r');
                }
                $flagComodin = $this->isComodin($row[62]);
                if (!$this->checkValidDateFormat($row[62])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de valoración agudeza visual”.', $fileName, $line, 63, 'Error 451 contenido en el campo FechaValoracionAgudezaVisual no válido');
                } else {
                    if ($row[62] !== '1800-01-01' && $row[62] !== '1805-01-01' && $row[62] !== '1810-01-01' && $row[62] !== '1825-01-01' && $row[62] !== '1830-01-01' && $row[62] !== '1835-01-01' && $row[62] !== '1845-01-01') {
                        if ($row[62] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de valoración agudeza visual”.', $fileName, $line, 63, 'Error 131 Fecha de valoración agudeza visual es mayor a la fecha de corte');
                        }
                        if ($row[62] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de valoración agudeza visual”.', $fileName, $line, 63, 'Error 181 Fecha de valoración agudeza visual es menor a la fecha de nacimiento');
                        }
                    } else {
                        if (intval($row[27]) === 0 && intval($row[28]) === 0 && $row[62] !== '1845-01-01') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de valoración agudeza visual”.', $fileName, $line, 63, 'Error 590 Si las variables Agudeza visual lejana ojo izquierdo y ojo derecho no aplican, debe registrar no aplica en Fecha de valoración de la agudeza visual ');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[63]);
                if (!$this->checkValidDateFormat($row[63])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de tamizaje VALE”.', $fileName, $line, 64, 'Error 452 contenido en el campo Fecha de tamizaje VALE no válido');
                } else {
                    if ($row[63] !== '1800-01-01' && $row[63] !== '1805-01-01' && $row[63] !== '1810-01-01' && $row[63] !== '1825-01-01' && $row[63] !== '1830-01-01' && $row[63] !== '1835-01-01' && $row[63] !== '1845-01-01') {
                        if ($row[63] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje VALE”.', $fileName, $line, 64, 'Error 132 Fecha de tamizaje VALE es mayor a la fecha de corte');
                        }
                        if ($row[63] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje VALE”.', $fileName, $line, 64, 'Error 182 Fecha de tamizaje VALE es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[64]);
                if (!$this->checkValidDateFormat($row[64])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha del tacto rectal”.', $fileName, $line, 65, 'Error 453 contenido en el campo Fecha del tacto rectal no válido');
                } else {
                    if ($row[64] !== '1800-01-01' && $row[64] !== '1805-01-01' && $row[64] !== '1810-01-01' && $row[64] !== '1825-01-01' && $row[64] !== '1830-01-01' && $row[64] !== '1835-01-01' && $row[64] !== '1845-01-01') {
                        if ($row[64] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha del tacto rectal”.', $fileName, $line, 65, 'Error 133 Fecha del tacto rectal es mayor a la fecha de corte');
                        }
                        if ($row[64] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha del tacto rectal”.', $fileName, $line, 65, 'Error 183 Fecha del tacto rectal es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[65]);
                if (!$this->checkValidDateFormat($row[65])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de tamización con oximetría pre y post ductal”.', $fileName, $line, 66, 'Error 454 contenido en el campo Fecha de tamización con oximetría pre y post ductal no válido');
                } else {
                    if ($row[65] !== '1800-01-01' && $row[65] !== '1805-01-01' && $row[65] !== '1810-01-01' && $row[65] !== '1825-01-01' && $row[65] !== '1830-01-01' && $row[65] !== '1835-01-01' && $row[65] !== '1845-01-01') {
                        if ($row[65] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamización con oximetría pre y post ductal”.', $fileName, $line, 66, 'Error 134 Fecha tamización con oximetría pre y post ductal es mayor a la fecha de corte');
                        }
                        if ($row[65] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamización con oximetría pre y post ductal”.', $fileName, $line, 66, 'Error 184 Fecha tamización con oximetría pre y post ductal es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[66]);
                if (!$this->checkValidDateFormat($row[66])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de realización colonoscopia tamizaje”.', $fileName, $line, 67, 'Error 455 contenido en el campo Fecha realización colonoscopia tamizaje no válido');
                } else {
                    if ($row[66] !== '1800-01-01' && $row[66] !== '1805-01-01' && $row[66] !== '1810-01-01' && $row[66] !== '1825-01-01' && $row[66] !== '1830-01-01' && $row[66] !== '1835-01-01' && $row[66] !== '1845-01-01') {
                        if ($row[66] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de realización colonoscopia tamizaje”.', $fileName, $line, 67, 'Error 135 Fecha de realización colonoscopia tamizaje es mayor a la fecha de corte');
                        }
                        if ($row[66] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de realización colonoscopia tamizaje”.', $fileName, $line, 67, 'Error 185 Fecha de realización colonoscopia tamizaje es menor o igual a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[67]);
                if (!$this->checkValidDateFormat($row[67])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de la prueba sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 68, 'Error 456 Fecha de la prueba de sangre oculta en materia fecal no válido');
                } else {
                    if ($row[67] !== '1800-01-01' && $row[67] !== '1805-01-01' && $row[67] !== '1810-01-01' && $row[67] !== '1825-01-01' && $row[67] !== '1830-01-01' && $row[67] !== '1835-01-01' && $row[67] !== '1845-01-01') {
                        if ($row[67] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de la prueba sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 68, 'Error 136 Fecha de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon) es mayor a la fecha de corte');
                        }
                        if ($row[67] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de la prueba sangre oculta en materia fecal (tamizaje Ca de colon)”.', $fileName, $line, 68, 'Error 186 Fecha de la prueba de sangre oculta en materia fecal (tamizaje Ca de colon) es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[68]);
                if (!$this->checkValidDateFormat($row[68])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Consulta de Psicología”.', $fileName, $line, 69, 'Error 457 contenido en el campo ConsultaPsicología no válido');
                } else {
                    if ($row[68] != '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Consulta de Psicología”.', $fileName, $line, 69, 'Error 591 Error en valores permitidos -Consulta de Psicología');
                    }
                }
                $flagComodin = $this->isComodin($row[69]);
                if (!$this->checkValidDateFormat($row[69])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de tamizaje auditivo neonatal”.', $fileName, $line, 70, 'Error 458 contenido en el campo Fecha de tamizaje auditivo neonatal no válido');
                } else {
                    if ($row[69] !== '1800-01-01' && $row[69] !== '1805-01-01' && $row[69] !== '1810-01-01' && $row[69] !== '1825-01-01' && $row[69] !== '1830-01-01' && $row[69] !== '1835-01-01' && $row[69] !== '1845-01-01') {
                        if ($row[69] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje auditivo neonatal”.', $fileName, $line, 70, 'Error 138 Fecha de tamizaje auditivo neonatal es mayor a la fecha de corte');
                        }
                        if ($row[69] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje auditivo neonatal”.', $fileName, $line, 70, 'Error 188 Fecha de tamizaje auditivo neonatal es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[70]);
                if (intval($row[70]) !== 0 && intval($row[70]) !== 1 && intval($row[70]) !== 16 && intval($row[70]) !== 17 && intval($row[70]) !== 18 && intval($row[70]) !== 20 && intval($row[70]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de fortificación casera en la primera infancia (6 a 23 meses)”.', $fileName, $line, 71, 'Error 592 Error en valores permitidos -Suministro de  fortificación casera en la primera Infancia.');
                } else {
                    if (($ageMonth < 6 || $ageMonth > 27) && (intval($row[70]) === 1 || intval($row[70]) === 16 || intval($row[70]) === 17 || intval($row[70]) === 18 || intval($row[70]) === 20 || intval($row[70]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de fortificación casera en la primera infancia (6 a 23 meses)”.', $fileName, $line, 71, 'Error 063 Verifique la edad de la persona con Suministro de  fortificación casera, según normatividad vigente');
                    }
                    if ($row[70] === '0' && ($ageMonth >= 6 && $ageMonth <= 27)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de fortificación casera en la primera infancia (6 a 23 meses)”.', $fileName, $line, 71, 'Error 328 No es válido registrar "No aplica" en Suministro de fortificación casera para la edad reportada, revise la normatividad vigente.');
                    }
                }
                $flagComodin = $this->isComodin($row[71]);
                if (intval($row[71]) !== 0 && intval($row[71]) !== 1 && intval($row[71]) !== 16 && intval($row[71]) !== 17 && intval($row[71]) !== 18 && intval($row[71]) !== 20 && intval($row[71]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de vitamina A en la primera infancia (24 a 60 meses)”.', $fileName, $line, 72, 'Error 593 Error en valores permitidos -Suministro de vitamina A en la primera infancia');
                } else {
                    if (($ageMonth < 24 || $ageMonth > 63) && (intval($row[71]) === 1 || intval($row[71]) === 16 || intval($row[71]) === 17 || intval($row[71]) === 18 || intval($row[71]) === 20 || intval($row[71]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de vitamina A en la primera infancia (24 a 60 meses)”.', $fileName, $line, 72, 'Error 064 Verifique la edad de la persona con Suministro de Vitamina A en la primera Infancia, según normatividad vigente');
                    }
                    if ($row[71] === '0' && ($ageMonth >= 24 && $ageMonth <= 63)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de vitamina A en la primera infancia (24 a 60 meses)”.', $fileName, $line, 72, 'Error 329 No es válido registrar "No aplica" en Suministro de Vitamina A en la primera Infancia, para la edad reportada, revise la normatividad vigente.');
                    }
                }
                $flagComodin = $this->isComodin($row[72]);
                if (!$this->checkValidDateFormat($row[72])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma LDL”.', $fileName, $line, 73, 'Error 459 contenido en el campo Fecha de toma LDL no válido');
                } else {
                    if ($row[72] !== '1800-01-01' && $row[72] !== '1805-01-01' && $row[72] !== '1810-01-01' && $row[72] !== '1825-01-01' && $row[72] !== '1830-01-01' && $row[72] !== '1835-01-01' && $row[72] !== '1845-01-01') {
                        if ($row[72] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma LDL”.', $fileName, $line, 73, 'Error 139 Fecha de toma LDL es mayor a la fecha de corte');
                        }
                        if ($row[72] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma LDL”.', $fileName, $line, 73, 'Error 189 Fecha de toma LDL es menor o igual a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[73]);
                if (!$this->checkValidDateFormat($row[73])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma PSA”.', $fileName, $line, 74, 'Error 460 contenido en el campo Fecha de toma PSA no válido');
                } else {
                    if ($row[73] !== '1800-01-01' && $row[73] !== '1805-01-01' && $row[73] !== '1810-01-01' && $row[73] !== '1825-01-01' && $row[73] !== '1830-01-01' && $row[73] !== '1835-01-01' && $row[73] !== '1845-01-01') {
                        if ($row[73] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma PSA”.', $fileName, $line, 74, 'Error 140 Fecha de toma PSA es mayor a la fecha de corte');
                        }
                        if ($row[73] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma PSA”.', $fileName, $line, 74, 'Error 190 Fecha de toma PSA es menor o igual a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[74]);
                if (intval($row[74]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Preservativos entregados a pacientes con ITS”.', $fileName, $line, 75, 'Error 594 Error en valores permitidos - Preservativos entregados a pacientes con ITS');
                }
                $flagComodin = $this->isComodin($row[75]);
                if (!$this->checkValidDateFormat($row[75])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de tamizaje visual neonatal”.', $fileName, $line, 76, 'Error 461 contenido en el campo Fecha de tamizaje visual neonatal no válido');
                } else {
                    if ($row[75] !== '1800-01-01' && $row[75] !== '1805-01-01' && $row[75] !== '1810-01-01' && $row[75] !== '1825-01-01' && $row[75] !== '1830-01-01' && $row[75] !== '1835-01-01' && $row[75] !== '1845-01-01') {
                        if ($row[75] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje visual neonatal”.', $fileName, $line, 76, 'Error 141 Fecha de tamizaje visual neonatal es mayor a la fecha de corte');
                        }
                        if ($row[75] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje visual neonatal”.', $fileName, $line, 76, 'Error 191 Fecha de tamizaje visual neonatal es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[76]);
                if (!$this->checkValidDateFormat($row[76])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de atención en salud bucal por profesional en odontología”.', $fileName, $line, 77, 'Error 462 contenido en el campo Fecha de atención en salud bucal por profesional en odontología no válido');
                } else {
                    if ($row[76] !== '1800-01-01' && $row[76] !== '1805-01-01' && $row[76] !== '1810-01-01' && $row[76] !== '1825-01-01' && $row[76] !== '1830-01-01' && $row[76] !== '1835-01-01') {
                        if ($row[76] !== '1845-01-01') {
                            if ($row[76] > $this->m202['content'][0][3]) {
                                $flagSuccess = 0;
                                $this->pushInconsistencyMessage('Campo “Fecha de atención en salud bucal por profesional en odontología”.', $fileName, $line, 77, 'Error 142 Fecha atención en salud bucal por profesional en odontología es mayor a la fecha de corte');
                            }
                            if ($row[76] < $row[9]) {
                                $flagSuccess = 0;
                                $this->pushInconsistencyMessage('Campo “Fecha de atención en salud bucal por profesional en odontología”.', $fileName, $line, 77, 'Error 192 Fecha atención en salud bucal por profesional en odontología es menor o igual a la fecha de nacimiento');
                            }
                        }
                        if ($ageMonth < 6 && $row[76] !== '1845-01-01') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud bucal por profesional en odontología”.', $fileName, $line, 77, 'Error 402 Si registra Fecha atención en salud bucal por profesional en odontología, la edad debe ser mayor o igual a 6 meses');
                        }
                        if ((intval($row[102]) === 0 && $row[76] !== '1845-01-01') || (intval($row[102]) !== 0 && $row[76] === '1845-01-01')) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de atención en salud bucal por profesional en odontología”.', $fileName, $line, 77, 'Error 597 Registre no aplica en Atención en salud bucal por profesional en odontología si registró no aplica en COP y viceversa');
                        }
                    }
                }

                $flagComodin = $this->isComodin($row[77]);
                if (intval($row[77]) !== 0 && intval($row[77]) !== 1 && intval($row[77]) !== 16 && intval($row[77]) !== 17 && intval($row[77]) !== 18 && intval($row[77]) !== 20 && intval($row[77]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Suministro de hierro en la primera Infancia (24 a 59 meses)”.', $fileName, $line, 78, 'Error 598 Error en valores permitidos -Suministro de hierro en la primera Infancia ');
                }
//                else {
                    $calculatedAgeMonth = $ageMonth;
                    if ($row[52] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[52]);
                        $calculatedAgeMonth = $dateCalculated['month'];
                    }
                    if (($calculatedAgeMonth < 24 || $calculatedAgeMonth > 63) && $row[77] !== '0') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de hierro en la primera Infancia (24 a 63 meses)”.', $fileName, $line, 78, 'Error 669 Verifique la edad de la persona con Suministro de hierro en la primera Infancia, según normatividad vigente');
                    }
                    if ($row[77] === '0' && ($calculatedAgeMonth >= 24 && $calculatedAgeMonth <= 63)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Suministro de hierro en la primera Infancia (24 a 63 meses)”.', $fileName, $line, 78, 'Error 670 No es válido registrar "No aplica" en Suministro de hierro en la primera Infancia, para la edad reportada, revise la normatividad vigente.');
                    }
//                }
                $flagComodin = $this->isComodin($row[78]);
                if (!$this->checkValidDateFormat($row[78])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 79, 'Error 429 contenido en el campo FechaAntigenoSuperficieHepatitisB  no válido');
                } else {
                    if ($row[78] !== '1800-01-01' && $row[78] !== '1805-01-01' && $row[78] !== '1810-01-01' && $row[78] !== '1825-01-01' && $row[78] !== '1830-01-01' && $row[78] !== '1835-01-01' && $row[78] !== '1845-01-01') {
                        if ($row[78] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 79, 'Error 143 Fecha antígeno de superficie hepatitis B es mayor a la fecha de corte');
                        }
                        if ($row[78] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 79, 'Error 193 Fecha antígeno de superficie hepatitis B es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[79]);
                if (intval($row[79]) !== 0 && intval($row[79]) !== 4 && intval($row[79]) !== 5 && intval($row[79]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 80, 'Error 601 Error en valores permitidos - Resultado antígeno de superficie hepatitis B');
                } else {
                    if ($row[78] > '1900-01-01' && (intval($row[79]) === 0 || intval($row[79]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 80, 'Error 341 Si registra fecha válida para Fecha antígeno de superficie hepatitis B, debe registrar resultado');
                    }
                    if (intval($row[79]) !== 21 && ($row[78] === '1800-01-01' || $row[78] === '1805-01-01' || $row[78] === '1810-01-01' || $row[78] === '1825-01-01' || $row[78] === '1830-01-01' || $row[78] === '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 80, 'Error 599 Si registra un comodín que indican no realización o sin dato en Fecha de antígeno de superficie hepatitis B, registre 21 en el resultado');
                    }
                    if (intval($row[79]) === 0 && $row[78] !== '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de antígeno de superficie hepatitis B (Toda la población)”.', $fileName, $line, 80, 'Error 600 Si Resultado antígeno de superficie hepatitis B no aplica, el registro de Fecha antígeno de superficie hepatitis B tampoco aplica');
                    }
                }
                $flagComodin = $this->isComodin($row[80]);
                if (!$this->checkValidDateFormat($row[80])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba tamizaje para sífilis”.', $fileName, $line, 81, 'Error 430 contenido en el campo prueba de tamizaje para sífilis  no válido');
                } else {
                    if ($row[80] !== '1800-01-01' && $row[80] !== '1805-01-01' && $row[80] !== '1810-01-01' && $row[80] !== '1825-01-01' && $row[80] !== '1830-01-01' && $row[80] !== '1835-01-01' && $row[80] !== '1845-01-01') {
                        if ($row[80] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba tamizaje para sífilis”.', $fileName, $line, 81, 'Error 144 Fecha de toma de la prueba de tamizaje para sífilis es mayor a la fecha de corte');
                        }
                        if ($row[80] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba tamizaje para sífilis”.', $fileName, $line, 81, 'Error 194 Fecha de toma de la prueba de tamizaje para sífilis es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[81]);
                if (intval($row[81]) !== 0 && intval($row[81]) !== 4 && intval($row[81]) !== 5 && intval($row[81]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de prueba tamizaje para sífilis”.', $fileName, $line, 82, 'Error 604 Error en valores permitidos - Resultado de prueba de tamizaje para sífilis');
                } else {
                    if ($row[80] > '1900-01-01' && (intval($row[81]) === 0 || intval($row[81]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba tamizaje para sífilis”.', $fileName, $line, 82, 'Error 344 Si registra Fecha de toma de la prueba de tamizaje para sífilis, debe registrar resultado de prueba');
                    }
                    if (intval($row[81]) !== 21 && ($row[80] === '1800-01-01' || $row[80] === '1805-01-01' || $row[80] === '1810-01-01' || $row[80] === '1825-01-01' || $row[80] === '1830-01-01' || $row[80] === '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba tamizaje para sífilis”.', $fileName, $line, 82, 'Error 602 Si registra un comodín que indica no realización o sin dato en Fecha de prueba de tamizaje para sífilis, registre 21 en el resultado');
                    }
                    if (intval($row[81]) === 0 && $row[80] !== '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba tamizaje para sífilis”.', $fileName, $line, 82, 'Error 603 Si Resultado de prueba de tamizaje para sífilis no aplica, el registro de Fecha de prueba de tamizaje para sífilis tampoco aplica');
                    }
                }
                $flagComodin = $this->isComodin($row[82]);
                if (!$this->checkValidDateFormat($row[82])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba para VIH”.', $fileName, $line, 83, 'Error 431 contenido en el campo Fecha prueba VIH  no válido');
                } else {
                    if ($row[82] !== '1800-01-01' && $row[82] !== '1805-01-01' && $row[82] !== '1810-01-01' && $row[82] !== '1825-01-01' && $row[82] !== '1830-01-01' && $row[82] !== '1835-01-01' && $row[82] !== '1845-01-01') {
                        if ($row[82] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba para VIH”.', $fileName, $line, 83, 'Error 145 Fecha de toma de prueba para VIH es mayor a la fecha de corte');
                        }
                        if ($row[82] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de prueba tamizaje para sífilis”.', $fileName, $line, 83, 'Error 195 Fecha de toma de prueba para VIH es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[83]);
                if (intval($row[83]) !== 0 && intval($row[83]) !== 4 && intval($row[83]) !== 5 && intval($row[83]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 607 Error en valores permitidos - Resultado de la prueba para VIH');
                } else {
                    if ($row[82] > '1900-01-01' && (intval($row[83]) === 0 || intval($row[83]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 346 Si registra fecha de toma de prueba para VIH, debe registrar resultado para prueba de VIH');
                    }
                    if (intval($row[83]) !== 21 && ($row[82] === '1800-01-01' || $row[82] === '1805-01-01' || $row[82] === '1810-01-01' || $row[82] === '1825-01-01' || $row[82] === '1830-01-01' || $row[82] === '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 605 Si registra alguno de los comodines que indican no realización en Fecha de prueba para VIH, registre riesgo no evaluado en el resultado');
                    }
                    if (intval($row[83]) !== 0 && $row[82] === '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 606 Si fecha de la prueba para VIH no aplica, el registro de resultado de la prueba para VIH tampoco aplica');
                    }
//                    if (intval($row[83]) !== 21 && $row[82] === '1800-01-01' ) {
//                        $flagSuccess = 0;
//                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 605 Si registra alguno de los comodines que indican no realización en Fecha de prueba para VIH, registre riesgo no evaluado en el resultado');
//                    }
//                    if (intval($row[83]) !== 0 && ($row[82] === '1805-01-01' || $row[82] === '1810-01-01' || $row[82] === '1825-01-01' || $row[82] === '1830-01-01' || $row[82] === '1835-01-01')) {
//                        $flagSuccess = 0;
//                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 605 Si registra alguno de los comodines que indican no realización en Fecha de prueba para VIH, registre riesgo no evaluado en el resultado');
//                    }
//                    if (intval($row[83]) === 0 && $row[82] !== '1845-01-01') {
//                        $flagSuccess = 0;
//                        $this->pushInconsistencyMessage('Campo “Resultado de prueba para VIH”.', $fileName, $line, 84, 'Error 606 Si fecha de la prueba para VIH no aplica, el registro de resultado de la prueba para VIH tampoco aplica');
//                    }
                }
                $flagComodin = $this->isComodin($row[84]);
                if (!$this->checkValidDateFormat($row[84])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de TSH neonatal”.', $fileName, $line, 85, 'Error 432 contenido en el campo FechaTSHneonatal no válido');
                } else {
//                    $calculatedAgeYear = $age;
//                    if($row[99] > '1900-01-01'){
//                        $dateCalculated = $this->calculatedAge($row[9],$row[99]);
//                        $calculatedAgeYear = $dateCalculated['year'];
//                    }
                    if ($row[84] !== '1800-01-01' && $row[84] !== '1805-01-01' && $row[84] !== '1810-01-01' && $row[84] !== '1825-01-01' && $row[84] !== '1830-01-01' && $row[84] !== '1835-01-01' && $row[84] !== '1845-01-01') {
                        if ($row[84] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de TSH neonatal”.', $fileName, $line, 85, 'Error 146 Fecha de TSH neonatal es mayor a la fecha de corte');
                        }
                        if ($row[84] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de TSH neonatal”.', $fileName, $line, 85, 'Error 196 Fecha de TSH neonatal es menor a la fecha de nacimiento');
                        }
                    } else {
                        if (intval($row[85]) === 0 && $row[84] !== '1845-01-01') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de TSH neonatal”.', $fileName, $line, 85, 'Error 350 Si registra fecha de toma de TSH Neonatal, debe registrar resultado de TSH Neonatal');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[85]);
                if (intval($row[85]) !== 0 && intval($row[85]) !== 4 && intval($row[85]) !== 5 && intval($row[85]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de TSH neonatal”.', $fileName, $line, 86, 'Error 608 Error en valores permitidos - Resultado de TSH neonatal');
                } else {
                    if ($row[84] > '1900-01-01' && $ageDay > 7 && (intval($row[85]) === 4 || intval($row[85]) === 5)) {
//                            $this->pushWarningMessage('Campo “Resultado de TSH neonatal”.', $fileName, $line, 'Error 068');
                    }
                    if (intval($row[85]) === 21 && ($row[84] !== '1800-01-01' && $row[84] !== '1805-01-01' && $row[84] !== '1810-01-01' && $row[84] !== '1825-01-01' && $row[84] !== '1830-01-01' && $row[84] !== '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de TSH neonatal”.', $fileName, $line, 86, 'Error 609 Si Resultado de TSH neonatal es riesgo no evaluado, debe registrar un comodín que indique no realización o sin dato en la fecha del tamizaje ');
                    }
                }
                $flagComodin = $this->isComodin($row[86]);
                if (intval($row[86]) !== 0 && ($row[10] !== 'F' || $age < 10)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tamizaje del cáncer de cuello uterino ”.', $fileName, $line, 87, 'Error 069 Si registra Tamizaje Cáncer de Cuello Uterino, la edad debe ser mayor o igual a 10 años y el sexo F');
                }
                if ($row[87] <= '1900-01-01' && (intval($row[86]) === 1 || intval($row[86]) === 2 || intval($row[86]) === 3 || intval($row[86]) === 4)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tamizaje del cáncer de cuello uterino ”.', $fileName, $line, 87, 'Error 610 Si registra Tamizaje cáncer de cuello uterino, debe registrar Fecha tamizaje cáncer de cuello uterino');
                }
                if ((intval($row[86]) === 1 || intval($row[86]) === 4) && (intval($row[88]) !== 1 && intval($row[88]) !== 2 && intval($row[88]) !== 3 && intval($row[88]) !== 4 && intval($row[88]) !== 5 && intval($row[88]) !== 6 && intval($row[88]) !== 7 && intval($row[88]) !== 8 && intval($row[88]) !== 9 && intval($row[88]) !== 10
                        && intval($row[88]) !== 11 && intval($row[88]) !== 12 && intval($row[88]) !== 13 && intval($row[88]) !== 14 && intval($row[88]) !== 15 && intval($row[88]) !== 16 && intval($row[88]) !== 17 && intval($row[88]) !== 18)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tamizaje del cáncer de cuello uterino ”.', $fileName, $line, 87, 'Error 611 Si reportó la opción de respuesta "1" o "4" en Tamizaje Cáncer de Cuello Uterino, reporte una opción entre 1 a 18 en el Resultado de tamizaje de CaCu');
                }
                if ((intval($row[86]) === 2 || intval($row[86]) === 3) && (intval($row[88]) !== 19 && intval($row[88]) !== 20)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tamizaje del cáncer de cuello uterino ”.', $fileName, $line, 87, 'Error 612 Si reportó la opción de respuesta "2" o "3" en Tamizaje Cáncer de Cuello Uterino, reporte la opción "19" o "20" en el Resultado tamizaje de CaCu');
                }
                if (intval($row[86]) === 0 && ($row[87] !== '1845-01-01' || intval($row[88]) !== 0)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tamizaje del cáncer de cuello uterino ”.', $fileName, $line, 87, 'Error 613 Si Tamizaje cáncer de cuello uterino no aplica, el registro de Fecha y resultado de tamizaje de cáncer de cuello tampoco aplica');
                }
                if (intval($row[86]) === 21 && (($row[87] !== '1800-01-01' && $row[87] !== '1805-01-01' && $row[87] !== '1810-01-01' && $row[87] !== '1825-01-01' && $row[87] !== '1830-01-01' && $row[87] !== '1835-01-01') || intval($row[88]) !== 21 )) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de TSH neonatal”.', $fileName, $line, 87, 'Error 614 Si Registró 21 en Tamizaje cáncer de cuello uterino, debe registrar comodín de no realización o sin dato en Fecha del tamizaje y  21 en el resultado');
                }
                $flagComodin = $this->isComodin($row[87]);
                if ($row[87] > $this->m202['content'][0][3]) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de tamizaje cáncer de cuello uterino”.', $fileName, $line, 88, 'Error 147 Fecha de tamizaje cáncer de cuello uterino es mayor a la fecha de corte');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[87] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[87]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[87] !== '1800-01-01' && $row[87] !== '1805-01-01' && $row[87] !== '1810-01-01' && $row[87] !== '1825-01-01' && $row[87] !== '1830-01-01' && $row[87] !== '1835-01-01' && $row[87] !== '1845-01-01') {
                        if ($row[87] > '1900-01-01' && (intval($row[88]) < 1 || intval($row[88]) > 20)) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje cáncer de cuello uterino”.', $fileName, $line, 88, 'Error 352 Si registra Fecha de tamizaje cáncer de cuello uterino, debe registrar Resultado tamizaje de cáncer de cuello uterino');
                        }
                        if ($row[87] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje cáncer de cuello uterino”.', $fileName, $line, 88, 'Error 197 Fecha de tamizaje cáncer de cuello uterinoa es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[87] !== '1845-01-01' && $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje cáncer de cuello uterino”.', $fileName, $line, 88, 'Error 071 Si registra Fecha de tamizaje cáncer de cuello uterino, el sexo debe ser F');
                        }
                        if ($row[87] !== '1845-01-01' && $calculatedAgeYear < 10) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de tamizaje cáncer de cuello uterino”.', $fileName, $line, 88, 'Error 070 Si registra Fecha de tamizaje cáncer de cuello uterino, la edad debe ser mayor o igual a 10 años');
                        }
                    }
                }

                $flagComodin = $this->isComodin($row[88]);
                if (intval($row[88]) !== 0 && intval($row[88]) !== 1 && intval($row[88]) !== 2 && intval($row[88]) !== 3 && intval($row[88]) !== 4 && intval($row[88]) !== 5 && intval($row[88]) !== 6 && intval($row[88]) !== 7 && intval($row[88]) !== 8 && intval($row[88]) !== 9 && intval($row[88]) !== 10
                    && intval($row[88]) !== 11 && intval($row[88]) !== 12 && intval($row[88]) !== 13 && intval($row[88]) !== 14 && intval($row[88]) !== 15 && intval($row[88]) !== 16 && intval($row[88]) !== 17 && intval($row[88]) !== 18 && intval($row[88]) !== 19 && intval($row[88]) !== 20 && intval($row[88]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado tamizaje cáncer de cuello uterino”.', $fileName, $line, 89, 'Error 616 Error en valores permitidos -  Resultado tamizaje de cáncer de cuello');
                } else {
                    if (intval($row[88]) !== 0 && $age < 10) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado tamizaje cáncer de cuello uterino”.', $fileName, $line, 89, 'Error 072 Si registra Resultado tamizaje de cáncer de cuello uterino, la edad debe ser mayor o igual a 10 años');
                    }
                    if (intval($row[88]) !== 0 && $row[10] !== 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado tamizaje cáncer de cuello uterino”.', $fileName, $line, 89, 'Error 073 Si registra Resultado tamizaje de cáncer de cuello, el sexo debe ser F');
                    }
                }

                $flagComodin = $this->isComodin($row[89]);
                if(intval($row[89]) !== 0 && intval($row[89]) !== 1 && intval($row[89]) !== 2 && intval($row[89]) !== 3 && intval($row[89]) !== 4 && intval($row[89]) !== 999){
                    $this->pushInconsistencyMessage('Campo “Calidad en la muestra de citología cervicouterina”.', $fileName, $line, 90, 'Error 354 valor "Calidad en la muestra de citología cervicouterina" no valido');
                }
                if ((intval($row[89]) === 1 || intval($row[89]) === 2 || intval($row[89]) === 3) && (intval($row[88]) < 1 || intval($row[88]) > 18)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Calidad en la muestra de citología cervicouterina”.', $fileName, $line, 90, 'Error 354 Si registra calidad de la muestra, debe registrar resultado de citología');
                }
                if (intval($row[89]) === 4 && intval($row[88]) !== 18) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Calidad en la muestra de citología cervicouterina”.', $fileName, $line, 90, 'Error 615 Si registra calidad de la muestra es insatisfactoria, el resultado de citología es insatisfactorio para la lectura');
                }
                if ($age < 10 && (intval($row[89]) === 1 || intval($row[89]) === 2 || intval($row[89]) === 3 || intval($row[89]) === 4 || intval($row[89]) === 999)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Calidad en la muestra de citología cervicouterina”.', $fileName, $line, 90, 'Error 074 Si registra Calidad en la Muestra de Citología Cervicouterina, la edad debe ser mayor o igual a 10 años');
                }
                if (intval($row[89]) !== 0 && $row[10] !== 'F') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Calidad en la muestra de citología cervicouterina”.', $fileName, $line, 90, 'Error 075 Si registra Calidad en la Muestra de Citología Cervicouterina, el sexo debe ser F');
                }
                $flagComodin = $this->isComodin($row[90]);
                if (intval($row[90]) !== 999 && intval($row[90]) !== 0) {
                    $rep = Sedeproveedore::where('Codigo', $row[90])->first();
                    if (!$rep) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Código de habilitación IPS donde se realiza tamizaje cáncer de cuello uterino”.', $fileName, $line, 91, 'Error 022 La IPS de tamizaje cáncer de cuello uterino no existe');
                    }
                }
                if (intval($row[90]) !== 0 && $age < 10) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de habilitación IPS donde se realiza tamizaje cáncer de cuello uterino”.', $fileName, $line, 91, 'Error 076 Si registra IPS de tamizaje de cáncer de cuello uterino, la edad debe ser mayor a 10 años');
                }
                if (intval($row[90]) !== 0 && $row[10] !== 'F') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de habilitación IPS donde se realiza tamizaje cáncer de cuello uterino”.', $fileName, $line, 91, 'Error 077 Si registra IPS de tamizaje de cáncer de cuello uterino, el sexo debe ser F');
                }
                if ((intval($row[89]) === 1 || intval($row[89]) === 2 || intval($row[89]) === 3 || intval($row[89]) === 4) && (intval($row[90]) === 0 || intval($row[90]) === 999)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Código de habilitación IPS donde se realiza tamizaje cáncer de cuello uterino”.', $fileName, $line, 91, 'Error 355 Si registra Código de habilitación IPS tamizaje cáncer de cuello uterino, debe registrar calidad de la muestra');
                }
                $flagComodin = $this->isComodin($row[91]);
                if (!$this->checkValidDateFormat($row[91])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de colposcopia”.', $fileName, $line, 92, 'Error 434 contenido en el campo FechaColposcopia no válido');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[91] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[91]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[91] !== '1800-01-01' && $row[91] !== '1805-01-01' && $row[91] !== '1810-01-01' && $row[91] !== '1825-01-01' && $row[91] !== '1830-01-01' && $row[91] !== '1835-01-01' && $row[91] !== '1845-01-01') {
                        if ($row[91] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de colposcopia”.', $fileName, $line, 92, 'Error 148 Fecha colposcopia es mayor a la fecha de corte');
                        }
                        if ($row[91] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de colposcopia”.', $fileName, $line, 92, 'Error 198 Fecha colposcopia es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[91] !== '1845-01-01' && $calculatedAgeYear < 10) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de colposcopia”.', $fileName, $line, 92, 'Error 078 Si registra Fecha Colposcopia, la edad debe ser mayor o igual a 10 años');
                        }
                        if ($row[91] !== '1845-01-01' && $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de colposcopia”.', $fileName, $line, 92, 'Error 079 Si registra Fecha Colposcopia, el sexo debe ser Femenino');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[92]);
                if ($row[72] > '1900-01-01' && (intval($row[92]) === 0 || intval($row[92]) >= 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de LDL”.', $fileName, $line, 93, 'Error 617 Si registra Fecha válida en Fecha de toma LDL, debe registrar Resultado de LDL');
                }
                if ((($row[72] === '1800-01-01' || $row[72] === '1805-01-01' || $row[72] === '1810-01-01' || $row[72] === '1825-01-01' || $row[72] === '1830-01-01' || $row[72] === '1835-01-01') && intval($row[92]) !== 998) || (intval($row[92]) === 998 && ($row[72] !== '1800-01-01' && $row[72] !== '1805-01-01' && $row[72] !== '1810-01-01' && $row[72] !== '1825-01-01' && $row[72] !== '1830-01-01' && $row[72] !== '1835-01-01'))) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de LDL”.', $fileName, $line, 93, 'Error 618 Si registró 1800-01-01, 1805-01-01, 1810-01-01, 1825-01-01, 1830-01-01 ó 1835-01-01 en Fecha de toma LDL, registre 998 en Resultado de LDL y viceversa');
                }
                if ($row[72] === '1845-01-01' && (intval($row[92]) !== 0 || $age >= 29)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de LDL”.', $fileName, $line, 93, 'Error 619 Registre no aplica en Fecha de toma de LDL y Resultado de LDL, a los menores de 29 años sin riesgo cardiovascular identificado');
                }
                $flagComodin = $this->isComodin($row[93]);
                if (!$this->checkValidDateFormat($row[93])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de biopsia cervicouterina”.', $fileName, $line, 94, 'Error 435 contenido en el campo fecha biopsia cervicouterina no válido');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[93] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[93]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[93] !== '1800-01-01' && $row[93] !== '1805-01-01' && $row[93] !== '1810-01-01' && $row[93] !== '1825-01-01' && $row[93] !== '1830-01-01' && $row[93] !== '1835-01-01' && $row[93] !== '1845-01-01') {
                        if ($row[93] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de biopsia cervicouterina”.', $fileName, $line, 94, 'Error 149 Fecha biopsia cervicouterina es mayor a la fecha de corte');
                        }
                        if ($row[93] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de biopsia cervicouterina”.', $fileName, $line, 94, 'Error 199 Fecha biopsia cervicouterina es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[93] !== '1845-01-01' && $calculatedAgeYear < 10) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de biopsia cervicouterina”.', $fileName, $line, 94, 'Error 082 Si registra Fecha biopsia cervicouterina, la edad debe ser mayor a 10 años');
                        }
                        if ($row[93] !== '1845-01-01' && $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de biopsia cervicouterina”.', $fileName, $line, 94, 'Error 083 Si registra Fecha biopsia cervicouterina, el sexo debe ser Femenino');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[94]);
                if (intval($row[94]) !== 0 && intval($row[94]) !== 1 && intval($row[94]) !== 3 && intval($row[94]) !== 4 && intval($row[94]) !== 5 && intval($row[94]) !== 6 && intval($row[94]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 620 Error en valores permitidos - Resultado de biopsia cervicouterina');
                } else {
                    if ($row[93] <= '1900-01-01' && (intval($row[94]) === 1 || intval($row[94]) === 3 || intval($row[94]) === 4 || intval($row[94]) === 5 || intval($row[94]) === 6)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 359 Si registra resultado de biopsia cervicouterina, debe registrar Fecha biopsia cervicouterina');
                    }
                    if (intval($row[94]) === 0 && $row[93] !== '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 621 Si Resultado de biopsia cervicouterina no aplica, el registro de Fecha biopsia cervicouterina tampoco aplica');
                    }
                    if (intval($row[94]) === 21 && ($row[93] !== '1800-01-01' && $row[93] !== '1805-01-01' && $row[93] !== '1810-01-01' && $row[93] !== '1825-01-01' && $row[93] !== '1830-01-01' && $row[93] !== '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 622 Se registra riesgo no evaluado en Resultado biopsia cervicouterina es sin dato, debe registrar comodín asociado en Fecha de biopsia cervicouterina');
                    }
                    if ($age <= 10 && (intval($row[94]) === 1 || intval($row[94]) === 3 || intval($row[94]) === 4 || intval($row[94]) === 5 || intval($row[94]) === 6 || intval($row[94]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 084 Si registra Resultado de biopsia cervicouterina, la edad debe ser mayor a 10 años');
                    }
                    if (intval($row[94]) !== 0 && $row[10] !== 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia cervicouterina”.', $fileName, $line, 95, 'Error 085 Si registra Resultado de Biopsia Cervical, el sexo debe ser Femenino');
                    }
                }
                $flagComodin = $this->isComodin($row[95]);
                if ($row[111] > '1900-01-01' && (intval($row[95]) === 0 || intval($row[95]) >= 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de HDL”.', $fileName, $line, 96, 'Error 623 Si registra fecha válida en Fecha de toma HDL, debe registrar Resultado de HDL');
                }
                if ((($row[111] === '1800-01-01' || $row[111] === '1805-01-01' || $row[111] === '1810-01-01' || $row[111] === '1825-01-01' || $row[111] === '1830-01-01' || $row[111] === '1835-01-01') && intval($row[95]) !== 998) || (intval($row[95]) === 998 && ($row[111] !== '1800-01-01' && $row[111] !== '1805-01-01' && $row[111] !== '1810-01-01' && $row[111] !== '1825-01-01' && $row[111] !== '1830-01-01' && $row[111] !== '1835-01-01'))) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de HDL”.', $fileName, $line, 96, 'Error 624 Si registró 1800-01-01, 1805-01-01, 1810-01-01, 1825-01-01, 1830-01-01 ó 1835-01-01 en Fecha de toma HDL, registre 998 en Resultado de HDL y viceversa');
                }
                if ($row[111] === '1845-01-01' && (intval($row[95]) !== 0 || $age >= 29)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de HDL”.', $fileName, $line, 96, 'Error 625 Registre no aplica en Fecha de toma HDL y Resultado de HDL, a los menores de 29 años sin riesgo cardiovascular identificado');
                }
                $flagComodin = $this->isComodin($row[96]);
                if (!$this->checkValidDateFormat($row[96])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 436 contenido en el campo FechaMamografia no válido');
                } else {
                    $calculatedAgeYear = $age;
                    if ($row[96] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[96]);
                        $calculatedAgeYear = $dateCalculated['year'];
                    }
                    if ($row[96] !== '1800-01-01' && $row[96] !== '1805-01-01' && $row[96] !== '1810-01-01' && $row[96] !== '1825-01-01' && $row[96] !== '1830-01-01' && $row[96] !== '1835-01-01' && $row[96] !== '1845-01-01') {

                        if ($row[96] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 150 Fecha de mamografía es mayor a la fecha de corte');
                        }
                        if ($row[96] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 200 Fecha de mamografía es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[10] === 'F' && $row[96] === '1845-01-01' && $calculatedAgeYear >= 50) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 362 Si es mujer mayor o igual de 50 años, no es válido registrar "no aplica" para mamografía');
                        }
                        if ($row[96] !== '1845-01-01' && $calculatedAgeYear < 35) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 088 Registre "No aplica" en la Fecha de mamografía, si la edad es menor a 35 años');
                        }
                        if ($row[96] !== '1845-01-01' && $row[10] !== 'F') {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de mamografía”.', $fileName, $line, 97, 'Error 089 Si registra una fecha válida o un comodín en la Fecha de mamografía, el sexo debe ser Femenino');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[97]);
                if (intval($row[97]) !== 0 && intval($row[97]) !== 1 && intval($row[97]) !== 2 && intval($row[97]) !== 3 && intval($row[97]) !== 4 && intval($row[97]) !== 5 && intval($row[97]) !== 6 && intval($row[97]) !== 7 && intval($row[97]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 628 Error en valores permitidos - Resultado mamografía');
                }

                if ($age >= 50 && $row[10] === 'F' && $row[97] !== '21' && ($row[96] === '1800-01-01' || $row[96] === '1805-01-01' || $row[96] === '1810-01-01' || $row[96] === '1825-01-01' || $row[96] === '1830-01-01' || $row[96] === '1835-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 626 Si es mujer mayor o igual de 50 años y registró un comodín de no realización o sin dato en Fecha de Mamografía, registre 21 en Resultado mamografía');
                }
                if ($row[96] > '1900-01-01' && (intval($row[97]) !== 1 && intval($row[97]) !== 2 && intval($row[97]) !== 3 && intval($row[97]) !== 4 && intval($row[97]) !== 5 && intval($row[97]) !== 6 && intval($row[97]) !== 7)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 361 Si registra fecha de mamografía, debe registrar resultado de mamografía');
                }
                if ($row[96] == '1845-01-01' && intval($row[97]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 627 Si registró 1845-01-01 en Fecha de Mamografía, registre 0 en Resultado mamografía');
                }
                if (intval($row[97]) !== 0 && $age < 35) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 090 Si registra Resultado mamografía, la edad debe ser mayor o igual a 35 años');
                }
                if (intval($row[97]) !== 0 && $row[10] !== 'F') {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 091 Si registra Resultado mamografía, el sexo debe ser F');
                }
                if ($age < 35 && intval($row[97]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de mamografía”.', $fileName, $line, 98, 'Error 364 Si es menor de 35 años no aplica para resultado de mamografía');
                }

                $flagComodin = $this->isComodin($row[98]);
                if ($row[118] > '1900-01-01' && (intval($row[98]) === 0 || intval($row[98]) >= 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de triglicéridos”.', $fileName, $line, 99, 'Error 629 Si registró Fecha de toma triglicéridos válida, registre el resultado en Resultado triglicéridos.');
                }
                if ($row[118] == '1845-01-01' && (intval($row[98]) !== 0 || $age >= 29)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de triglicéridos”.', $fileName, $line, 99, 'Error 631 Registre no aplica en Fecha de toma triglicéridos y Resultado de triglicéridos, a los menores de 29 años sin riesgo cardiovascular identificado');
                }
                if ((($row[118] === '1800-01-01' || $row[118] === '1805-01-01' || $row[118] === '1810-01-01' || $row[118] === '1825-01-01' || $row[118] === '1830-01-01' || $row[118] === '1835-01-01') && intval($row[98]) !== 998) || (intval($row[98]) === 998 && ($row[118] !== '1800-01-01' && $row[118] !== '1805-01-01' && $row[118] !== '1810-01-01' && $row[118] !== '1825-01-01' && $row[118] !== '1830-01-01' && $row[118] !== '1835-01-01'))) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de triglicéridos”.', $fileName, $line, 99, 'Error 630 Si registró 1800-01-01, 1805-01-01, 1810-01-01, 1825-01-01, 1830-01-01 ó 1835-01-01 en Fecha toma triglicéridos, registre 998 en Resultado y viceversa');
                }
                $flagComodin = $this->isComodin($row[99]);
                if (!$this->checkValidDateFormat($row[99])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 100, 'Error 437 contenido en el campo FechaTomaBiopsiaMama no válido');
                } else {
//                    $calculatedAgeYear = $age;
//                    if($row[99] > '1900-01-01'){
//                        $dateCalculated = $this->calculatedAge($row[9],$row[99]);
//                        $calculatedAgeYear = $dateCalculated['year'];
//                    }
                    if ($row[99] > '1900-01-01' && $row[10] !== 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 100, 'Error 094 Si registra Fecha de toma biopsia de mama, el sexo debe ser F');
                    }
                    if ($row[99] > $this->m202['content'][0][3]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 100, 'Error 151 Fecha de toma biopsia de mama es mayor a la fecha de corte');
                    }
                    if ($row[99] > '1900-01-01' && $row[99] < $row[9]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 100, 'Error 201 Fecha de toma biopsia mama es menor a la fecha de nacimiento');
                    }
                }
                $flagComodin = $this->isComodin($row[100]);
                if (!$this->checkValidDateFormat($row[100])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de resultado biopsia de mama”.', $fileName, $line, 101, 'Error 438 contenido en el campo FechaResultadoBiopsiaMama no válido');
                } else {
//                    $calculatedAgeYear = $age;
//                    if($row[100] > '1900-01-01'){
//                        $dateCalculated = $this->calculatedAge($row[9],$row[100]);
//                        $calculatedAgeYear = $dateCalculated['year'];
//                    }
                    if($row[100] <= '1900-01-01' && $row[100] !== '1800-01-01' && $row[100] !== '1845-01-01'){
                        $this->pushInconsistencyMessage('Campo “Fecha de resultado biopsia de mama”.', $fileName, $line, 101, 'Error 412 Comodín inválido');
                    }
                    if ($row[99] > '1900-01-01' && $row[100] > '1900-01-01' && $row[100] < $row[99]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de resultado biopsia de mama”.', $fileName, $line, 101, 'Error 368 La Fecha de resultado de biopsia de mama es menor a la fecha de toma de la biopsia');
                    }
                    if ($row[100] > '1900-01-01' && $row[10] != 'F') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de resultado biopsia de mama”.', $fileName, $line, 101, 'Error 095 Si registra Fecha resultado de biopsia de mama, el sexo debe ser F');
                    }
                    if ($row[100] > '1900-01-01' && $row[100] < $row[9]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 202 Fecha resultado de biopsia de mama es menor a la fecha de nacimiento');
                    }
                    if ($row[100] > $this->m202['content'][0][3]) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 152 Fecha resultado de biopsia de mama es mayor a la fecha de corte');
                    }
                    if ($row[100] !== '1800-01-01' && intval($row[101]) === 21) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 632 Si registra sin dato en Resultado de biopsia de mama, debe registrar sin dato en Fecha de resultado de biopsia');
                    }
                    if ($row[100] === '1800-01-01' && intval($row[101]) !== 21) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 632 Si registra sin dato en Resultado de biopsia de mama, debe registrar sin dato en Fecha de resultado de biopsia');
                    }
                    if ($row[100] < '1900-01-01' && (intval($row[101]) === 1 || intval($row[101]) === 2 || intval($row[101]) === 3 || intval($row[101]) === 4 || intval($row[101]) === 5)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 369 Si registra un Resultado de biopsia de mama debe registrar Fecha resultado de biopsia de mama');
                    }
                    if (($row[100] == '1800-01-01' || $row[100] == '1845-01-01') && intval($row[101]) >= 1 && intval($row[101]) <= 5) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha de toma biopsia de mama”.', $fileName, $line, 101, 'Error 369 Si registra un Resultado de biopsia de mama debe registrar Fecha resultado de biopsia de mama');
                    }
                }
                $flagComodin = $this->isComodin($row[101]);
                if ($this->isThereAny('char',$row[101]) || intval($row[101]) !== 0 && intval($row[101]) !== 1 && intval($row[101]) !== 2 && intval($row[101]) !== 3 && intval($row[101]) !== 4 && intval($row[101]) !== 5 && intval($row[101]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de biopsia de mama”.', $fileName, $line, 102, 'Error 633 Error en valores permitidos - Resultado de Biopsia de mama');
                } else {
                    if ($row[10] !== 'F' and (intval($row[101]) === 1 || intval($row[101]) === 2 || intval($row[101]) === 3 || intval($row[101]) === 4 || intval($row[101]) === 5 || intval($row[101]) === 21)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia de mama”.', $fileName, $line, 102, 'Error 096 Si registra Resultado de biopsia de mama, el sexo debe ser F');
                    }
                    if (intval($row[101]) === 0 && ($row[100] === '1800-01-01' || $row[100] > '1900-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Resultado de biopsia de mama”.', $fileName, $line, 102, 'Error 367 Si registra sin dato o fecha válida de resultado de biopsia de mama, debe registrar sin dato o el resultado de biopsia de mama');
                    }
                }
                $flagComodin = $this->isComodin($row[102]);
                if (strlen($row[102]) > 12 || $this->isThereAny('char', $row[102]) || $this->isThereAny('letter', $row[102])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “COP por persona”.', $fileName, $line, 103, 'Error 678 Solo se permite el registro de los valores 0, 21 o un valor de 12 dígitos de longitud');
                } else {
                    $calculatedAgeMonth = $ageMonth;
                    if ($row[76] > '1900-01-01') {
                        $dateCalculated = $this->calculatedAge($row[9], $row[76]);
                        $calculatedAgeMonth = $dateCalculated['month'];
                    }
                    if (strlen($row[102]) === 12) {
                        $arrDientes = preg_split('//', $row[102], -1, PREG_SPLIT_NO_EMPTY);
                        $totalDientes = intval($arrDientes[0] . $arrDientes[1]) + intval($arrDientes[2] . $arrDientes[3]) + intval($arrDientes[4] . $arrDientes[5]) + intval($arrDientes[6] . $arrDientes[7]);
                        if (($totalDientes > 22 && $calculatedAgeMonth < 60) || ($totalDientes > 32 && $calculatedAgeMonth <= 60)) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “COP por persona”.', $fileName, $line, 103, 'Error 102 La suma de dientes sanos + los diagnosticados  no es igual al total de diente presentes o los perdidos + los presentes exceden la cantidad para la edad.');
                        }
                        if ($totalDientes !== intval($arrDientes[10] . $arrDientes[11])) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “COP por persona”.', $fileName, $line, 103, 'Error 102 La suma de dientes sanos + los diagnosticados  no es igual al total de diente presentes o los perdidos + los presentes exceden la cantidad para la edad.');
                        }
                    }
                    if ($calculatedAgeMonth >= 6 && intval($row[102]) !== 21 && ($row[76] === '1800-01-01' || $row[76] === '1805-01-01' || $row[76] === '1810-01-01' || $row[76] === '1825-01-01' || $row[76] === '1830-01-01' || $row[76] === '1835-01-01')) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “COP por persona”.', $fileName, $line, 103, 'Error 634 Si registró comodín de no realización o sin dato en Fecha atención en salud bucal a partir de los 6 meses de edad, registre 0 en COP por persona');
                    }
                    if ($row[76] > '1900-01-01' && strlen($row[102]) < 12) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “COP por persona”.', $fileName, $line, 103, 'Error 635 Si registró Fecha atención en salud bucal por profesional en odontología, debe registrar el resultado de COP. Si es edéntulo, registre 000000000000.');
                    }
                }
                $flagComodin = $this->isComodin($row[103]);
                if (!$this->checkValidDateFormat($row[103])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma hemoglobina”.', $fileName, $line, 104, 'Error 439 contenido en el campo FechaHemoglobina no válido');
                } else {
                    if ($row[103] !== '1800-01-01' && $row[103] !== '1805-01-01' && $row[103] !== '1810-01-01' && $row[103] !== '1825-01-01' && $row[103] !== '1830-01-01' && $row[103] !== '1835-01-01' && $row[103] !== '1845-01-01') {
                        if ($row[103] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma hemoglobina”.', $fileName, $line, 104, 'Error 153 Fecha de toma hemoglobina es mayor a la fecha de corte');
                        }
                        if ($row[103] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma hemoglobina”.', $fileName, $line, 104, 'Error 203 Fecha de toma hemoglobina es menor a la fecha de nacimiento');
                        }
                    } else {
                        if ($row[10] === 'F' && $row[103] === '1845-01-01' && ($age >= 10 && $age <= 17)) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma hemoglobina”.', $fileName, $line, 104, 'Error 638 Si es mujer entre 10 y 17 años debe registrar una Fecha de toma hemoglobina diferente de 1845-01-01');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[104]);
                if($this->isThereAny('charExclude.', $row[104])){
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de hemoglobina”.', $fileName, $line, 105, 'Error 000 el campo registra un valor no permitido');
                }
                if ($row[103] > '1900-01-01' && (intval($row[104]) === 0 || floatval($row[104]) > 25 || floatval($row[104]) < 1)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de hemoglobina”.', $fileName, $line, 105, 'Error 639 Si registró Fecha de toma hemoglobina válida, registre el resultado de la hemoglobina,mayor a 0 y menor a 99');
                }
                if ($row[10] === 'F' && intval($row[104]) !== 998 && $age >= 10 && $age <= 17 && ($row[103] === '1800-01-01' || $row[103] === '1805-01-01' || $row[103] === '1810-01-01' || $row[103] === '1825-01-01' || $row[103] === '1830-01-01' || $row[103] === '1835-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de hemoglobina”.', $fileName, $line, 105, 'Error 640 Si es mujer entre 10 y 17 años y registró un comodín de no realización o sin dato en Fecha toma de hemoglobina, registre 998 en Resultado');
                }
                if ($row[103] === '1845-01-01' && intval($row[104]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de hemoglobina”.', $fileName, $line, 105, 'Error 641 Registró no aplica en Fecha toma de hemoglobina, registre 0 en Resultado de hemogobina');
                }
                $flagComodin = $this->isComodin($row[105]);
                if (!$this->checkValidDateFormat($row[105])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma glicemia basal”.', $fileName, $line, 106, 'Error 440 contenido en el campo FechaTomaGlicemiaBasal no válido');
                } else {
                    if ($row[105] !== '1800-01-01' && $row[105] !== '1805-01-01' && $row[105] !== '1810-01-01' && $row[105] !== '1825-01-01' && $row[105] !== '1830-01-01' && $row[105] !== '1835-01-01' && $row[105] !== '1845-01-01') {
                        if ($row[105] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma glicemia basal”.', $fileName, $line, 106, 'Error 642 Fecha de toma de glicemia basal es mayor a la fecha de corte');
                        }
                        if ($row[105] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma glicemia basal”.', $fileName, $line, 106, 'Error 643 Fecha de toma de glicemia basal es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[106]);
                if (!$this->checkValidDateFormat($row[106])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma creatinina”.', $fileName, $line, 107, 'Error 441 contenido en el campo FechaCreatinina no válido');
                } else {
                    if ($row[106] !== '1800-01-01' && $row[106] !== '1805-01-01' && $row[106] !== '1810-01-01' && $row[106] !== '1825-01-01' && $row[106] !== '1830-01-01' && $row[106] !== '1835-01-01' && $row[106] !== '1845-01-01') {
                        if ($row[106] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma creatinina”.', $fileName, $line, 107, 'Error 155 Fecha creatinina es mayor a la fecha de corte');
                        }
                        if ($row[106] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma creatinina”.', $fileName, $line, 107, 'Error 205 Fecha creatinina es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[107]);
                if($this->isThereAny('charExclude.',$row[107])){
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de creatinina”.', $fileName, $line, 105, 'Error 000 el campo registra un valor no permitido');
                }
                if ($row[106] > '1900-01-01' && ($row[107] <= 0 || $row[107] >= 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de creatinina”.', $fileName, $line, 108, 'Error 371 Si registra Fecha de toma creatinina, debe registrar el Resultado de creatinina');
                }
                if ((($row[106] === '1800-01-01' || $row[106] === '1805-01-01' || $row[106] === '1810-01-01' || $row[106] === '1825-01-01' || $row[106] === '1830-01-01' || $row[106] === '1835-01-01') && intval($row[107]) !== 998) || (intval($row[107]) === 998 && ($row[106] !== '1800-01-01' && $row[106] !== '1805-01-01' && $row[106] !== '1810-01-01' && $row[106] !== '1825-01-01' && $row[106] !== '1830-01-01' && $row[106] !== '1835-01-01'))) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de creatinina”.', $fileName, $line, 108, 'Error 645 Si registró 1800-01-01, 1805-01-01, 1810-01-01, 1825-01-01, 1830-01-01 ó 1835-01-01 en Fecha toma creatinina, registre 998 en el Resultado y viceversa');
                }
                if ($row[106] === '1845-01-01' && (intval($row[107]) !== 0 || $age >= 29)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de creatinina”.', $fileName, $line, 108, 'Error 646 Registre no aplica en Fecha de toma creatinina y Resultado de creatinina, a los menores de 29 años sin riesgo cardiovascular identificado');
                }
                $flagComodin = $this->isComodin($row[108]);
                if (!$this->checkValidDateFormat($row[108])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha Hemoglobina Glicosilada”.', $fileName, $line, 109, 'Error 442 Contendio en el campo FechaHemoglobinaGlicosilada no válido');
                } else {
                    if ($row[108] !== '1845-01-01') {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Fecha Hemoglobina Glicosilada”.', $fileName, $line, 109, 'Error 647 Error en valores permitidos- Fecha Hemoglobina Glicosilada');
                    }
                }
                $flagComodin = $this->isComodin($row[109]);
                if($this->isThereAny('charExclude.', $row[109])){
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 000 el campo registra un valor no permitido');
                }
                $calculatedAgeYear = $age;
                if ($row[73] > '1900-01-01') {
                    $dateCalculated = $this->calculatedAge($row[9], $row[73]);
                    $calculatedAgeYear = $dateCalculated['year'];
                }
                if ($row[73] > '1900-01-01' && ($calculatedAgeYear < 40 || $row[10] !== 'M' || $row[109] === '0' || intval($row[109]) >= 998)) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 649 Si registra Fecha de toma PSA, debe ser hombre mayor de 40 y registrar Resultado PSA,mayor a 0 y menor que 998');
                }
                if($calculatedAgeYear >= 40 && $row[10] === 'M' && (($row[109] <= 0 && $row[109] > 998) || $row[73] == '1845-01-01')){
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 649 Si registra Fecha de toma PSA, debe ser hombre mayor de 40 y registrar Resultado PSA,mayor a 0 y menor que 998');
                }
                if ($row[109] === '998' && ($row[73] !== '1800-01-01' && $row[73] !== '1805-01-01' && $row[73] !== '1810-01-01' && $row[73] !== '1825-01-01' && $row[73] !== '1830-01-01' && $row[73] !== '1835-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 650 Si Resultado de PSA es riesgo no evaluado, debe registrar alguno de los comodines que indican no realización o sin dato en la fecha de PSA');
                }
                if ($row[109] != 998 && ($row[73] === '1800-01-01' || $row[73] === '1805-01-01' || $row[73] === '1810-01-01' || $row[73] === '1825-01-01' || $row[73] === '1830-01-01' || $row[73] === '1835-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 650 Si Resultado de PSA es riesgo no evaluado, debe registrar alguno de los comodines que indican no realización o sin dato en la fecha de PSA');
                }
                if($row[109] == 0 && $row[73] !== '1845-01-01'){
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error  Si Resultado de PSA no aplica, fecha de toma PSA debe ser 1845-01-01');
                }
                if($row[109] != 0 && $row[73] === '1845-01-01'){
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error  Si Resultado de PSA no aplica, fecha de toma PSA debe ser 1845-01-01');
                }
                if ($row[10] === 'F' && ($row[109] != 0 || $row[73] !== '1845-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 651 Si el sexo es F, registre no aplica en la fecha y el resultado de tacto rectal');
                }
                if ($calculatedAgeYear < 40 && $row[10] === 'M' && (floatval($row[109]) != 0 || $row[73] !== '1845-01-01')) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de PSA”.', $fileName, $line, 110, 'Error 652 Si es hombre menor de 40 años, debe registrar  no aplica en el resultado y la fecha de PSA');
                }
                $flagComodin = $this->isComodin($row[110]);
                if (!$this->checkValidDateFormat($row[110])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma de tamizaje hepatitis C”.', $fileName, $line, 111, 'Error 443 contenido en el campo fecha de toma de tamizaje hepatitis C no válido');
                } else {
                    if ($row[110] !== '1800-01-01' && $row[110] !== '1805-01-01' && $row[110] !== '1810-01-01' && $row[110] !== '1825-01-01' && $row[110] !== '1830-01-01' && $row[110] !== '1835-01-01' && $row[110] !== '1845-01-01') {
                        if ($row[110] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de tamizaje hepatitis C”.', $fileName, $line, 111, 'Error 157 Fecha de toma de tamizaje hepatitis C es mayor a la fecha de corte');
                        }
                        if ($row[110] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de tamizaje hepatitis C”.', $fileName, $line, 111, 'Error 207 Fecha de toma de tamizaje hepatitis C es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[111]);
                if (!$this->checkValidDateFormat($row[111])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma HDL”.', $fileName, $line, 112, 'Error 444 contenido en el campo FechaTomaHDL no válido');
                } else {
                    if ($row[111] !== '1800-01-01' && $row[111] !== '1805-01-01' && $row[111] !== '1810-01-01' && $row[111] !== '1825-01-01' && $row[111] !== '1830-01-01' && $row[111] !== '1835-01-01' && $row[111] !== '1845-01-01') {
                        if ($row[111] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma HDL”.', $fileName, $line, 112, 'Error 158 Fecha de toma de baciloscopia de diagnóstico es mayor a la fecha de corte');
                        }
                        if ($row[111] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma HDL”.', $fileName, $line, 112, 'Error 208 Fecha de toma de HDL es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[112]);
                if (!$this->checkValidDateFormat($row[112])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma de baciloscopia diagnóstico”.', $fileName, $line, 113, 'Error 445 contenido en el campo FechaTomaBaciloscopiaDiagnostico no válido');
                } else {
                    if ($row[112] !== '1800-01-01' && $row[112] !== '1805-01-01' && $row[112] !== '1810-01-01' && $row[112] !== '1825-01-01' && $row[112] !== '1830-01-01' && $row[112] !== '1835-01-01' && $row[112] !== '1845-01-01') {
                        if ($row[112] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de baciloscopia diagnóstico”.', $fileName, $line, 113, 'Error 159 Fecha de toma de baciloscopia de diagnóstico es mayor a la fecha de corte');
                        }
                        if ($row[112] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma de baciloscopia diagnóstico”.', $fileName, $line, 113, 'Error 209 Fecha de toma de baciloscopia de diagnóstico es menor a la fecha de nacimiento');
                        }
                    }
                }
                $flagComodin = $this->isComodin($row[113]);
                if (intval($row[113]) !== 1 && intval($row[113]) !== 2 && intval($row[113]) !== 3 && intval($row[113]) !== 4 && intval($row[113]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Resultado de baciloscopia diagnóstico”.', $fileName, $line, 114, 'Error 653 Error en valores permitidos -  Resultado de baciloscopia diagnóstico');
                } else {
                    if ($row[112] > '1900-01-01' && (intval($row[113]) !== 1 && intval($row[113]) !== 2 && intval($row[113]) !== 3)) {
                        $flagSuccess = 0;
                        $this->pushInconsistencyMessage('Campo “Si registra fecha toma de baciloscopia, debe registrar Resultado de baciloscopia diagnóstico. Si aún no tiene el resultado, indique en proceso”.', $fileName, $line, 114, 'Error 375 Si registra fecha toma de baciloscopia, debe registrar Resultado de baciloscopia diagnóstico. Si aún no tiene el resultado, indique en proceso');
                    }
                }
                $flagComodin = $this->isComodin($row[114]);
                if (intval($row[114]) !== 0 && intval($row[114]) !== 4 && intval($row[114]) !== 5 && intval($row[114]) !== 6 && intval($row[114]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Clasificación del riesgo cardiovascular”.', $fileName, $line, 115, 'Error 655 Error en valores permitidos -Clasificación del riesgo cardiovascular');
                }
                $flagComodin = $this->isComodin($row[115]);
                if (intval($row[115]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tratamiento para Sífilis”.', $fileName, $line, 116, 'Error 656 Error en valores permitidos - Tratamiento para sífilis gestacional');
                }
                $flagComodin = $this->isComodin($row[116]);
                if (intval($row[116]) !== 0) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Tratamiento para Sífilis Congénita”.', $fileName, $line, 117, 'Error 657 Error en valores permitidos -  Tratamiento para sífilis congénita');
                }
                $flagComodin = $this->isComodin($row[117]);
                if (intval($row[117]) !== 0 && intval($row[117]) !== 4 && intval($row[117]) !== 5 && intval($row[117]) !== 6 && intval($row[117]) !== 21) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Clasificación del riesgo metabólico”.', $fileName, $line, 118, 'Error 665 Error en valores permitidos -Clasificación de riesgo metabólico');
                }
                $flagComodin = $this->isComodin($row[118]);
                if (!$this->checkValidDateFormat($row[118])) {
                    $flagSuccess = 0;
                    $this->pushInconsistencyMessage('Campo “Fecha de toma triglicéridos”.', $fileName, $line, 119, 'Error 446 contenido en el campo Fecha de toma triglicéridos no válido');
                } else {
                    if ($row[118] !== '1800-01-01' && $row[118] !== '1805-01-01' && $row[118] !== '1810-01-01' && $row[118] !== '1825-01-01' && $row[118] !== '1830-01-01' && $row[118] !== '1835-01-01' && $row[118] !== '1845-01-01') {
                        if ($row[118] > $this->m202['content'][0][3]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma triglicéridos”.', $fileName, $line, 119, 'Error 666 Fecha de toma triglicéridos es mayor a la fecha de corte');
                        }
                        if ($row[118] < $row[9]) {
                            $flagSuccess = 0;
                            $this->pushInconsistencyMessage('Campo “Fecha de toma triglicéridos”.', $fileName, $line, 119, 'Error 667 Fecha de toma triglicéridos es menor a la fecha de nacimiento');
                        }
                    }
                }
                $columnaCedulas = array_keys(array_column($this->m202['content'], 4), $row[4]);
                if(count($columnaCedulas)  > 1){
                    $this->pushInconsistencyMessage('Campo “Número de identificación del usuario”.', $fileName, $line, 5, 'Error el numero de identificacion se encuentra repetido en el archivo');
                }
            }
            if ($flagComodin) {
                $this->pushInconsistencyMessage('Error 679 esta validación no permite el acceso de líneas de registro que solo reporten comodines (0 o 21) , (1800-01-01 o 1845-01-01), (999 0 998)', $fileName, $line, 0, '');
            }

            if ($flagSuccess) {
                $this->m202['success'][] = $row;
            }
            $line++;
        }
    }

    private function pushInconsistencyMessage($msg, $fileName = '', $line = '', $column = '', $code = '')
    {
        if (count($this->inconsistencyMessages) < 15000) {
            if ($line != '' && $fileName != '') {
                if ($code != '') {
                    array_push($this->inconsistencyMessages, ['Linea' => $line, 'Columna' => $column, 'Error' => substr($code, 6), 'Mensaje' => $msg]);
                } else {
                    array_push($this->inconsistencyMessages, ['Linea' => $line, 'Columna' => '', 'Error' => '', 'Mensaje' => $msg]);
                }
            } else {

                array_push($this->inconsistencyMessages, ['Linea' => '', 'Columna' => '', 'Error' => '', 'Mensaje' => $msg]);
            }
        }
    }

    private function areThereAnyInconsistencyMessages()
    {
        if (count($this->inconsistencyMessages) == 0) {
            return false;
        }
        return true;
    }

    private function checkValidDateFormat($date)
    {
        $valores = explode('-', $date);
        if (strlen($valores[0]) != 4 || !is_numeric($valores[0]) || strlen($valores[1]) != 2 || !is_numeric($valores[1]) || strlen($valores[2]) != 2 || !is_numeric($valores[2])) {
            return false;
        }
        if (count($valores) != 3 || !checkdate($valores[1], $valores[2], $valores[0])) {
            return false;
        }
        list($year, $month, $day) = explode('-', $date);
        if (!isset($month) || !isset($year) || strlen($year) != 4) {
            return false;
        }

        return true;
    }

    private function isComodin($string)
    {
        if (intval($string) !== 0 || intval($string) !== 21 || $string !== '1800-01-01' || $string !== '1845-01-01' || intval($string) !== 999 || intval($string) !== 998) {
            return false;
        }
    }

    private function isThereAny($request, $item)
    {
        $string = $item;
        switch ($request) {
            case 'number':
                $validar = "/[0-9]+/i";
                break;
            case 'letter':
                $validar = "/[aA-zZ]+/i";
                break;
            case 'char':
                $validar = "/[\W_]+/i";
                break;
            case 'charExcludeSpace':
                $ignore = [' '];
                $string = str_replace($ignore, "", $item);
                $validar = "/[^\w\-]/i";
                break;
            case 'charExclude.':
                $ignore = ['.'];
                $string = str_replace($ignore, "", $item);
                $validar = "/[\W_]+/i";
                break;
        }

        if (preg_match($validar, $string)) {
            return true;
        }
        return false;
    }

    private function checkValidDocumentNumberByDocumentType($documentType, $documentNumber)
    {
        switch ($documentType) {
            case 'CC':
                $longitud = [1, 10];
                if ($this->isThereAny('letter', $documentNumber) || $this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número cédula no válido');
                    return false;
                }
                break;
            case 'TI':
                $longitud = [1, 11];
                if ($this->isThereAny('letter', $documentNumber) || $this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número tarjeta de identidad no válido');
                    return false;
                }
                break;
            case 'RC':
                $longitud = [8, 11];
                if ($this->isThereAny('letter', $documentNumber) || $this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número registro civíl no válido');
                    return false;
                }
                break;
            case 'CE':
                $longitud = [3, 7];
                if ($this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número cédula de extrajería no válido');
                    return false;
                }
                break;
            case 'MS':
                $longitud = [9, 12];
                if ($this->isThereAny('letter', $documentNumber) || $this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número documento menor sin identificar no válido');
                    return false;
                }
                break;
            case 'PA':
                $longitud = [3, 16];
                if ($this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número pasaporte no válido');
                    return false;
                }
                break;
            case 'CN':
//                $longitud = [6, 11];
//                if ($this->isThereAny('char', $documentNumber)) {
//                    //$this->pushInconsistencyMessage('número documento CN no válido');
//                    return false;
//                }
                return true;

                break;
            case 'NI':
                $longitud = [9, 9];
                if ($this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número documento "NI" no válido');
                    return false;
                }
                break;
            case 'PE':
                $longitud = [3, 15];
                if ($this->isThereAny('letter', $documentNumber) || $this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número documento "PE" no válido');
                    return false;
                }
                break;
            case 'CD':
                $longitud = [4, 11];
                if ($this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número documento "CD" no válido');
                    return false;
                }
                break;
            case 'SC':
                $longitud = [4, 9];
                if ($this->isThereAny('char', $documentNumber)) {
                    //$this->pushInconsistencyMessage('número documento "SC" no válido');
                    return false;
                }
                break;
            case 'AS':
                return true;
                break;

            default:
                //$this->pushInconsistencyMessage('tipo de documento no válido');
                return false;
                break;

        }
        if (strlen($documentNumber) >= $longitud[0] && strlen($documentNumber) <= $longitud[1]) {
            return true;
        }
        return false;
    }

    private function actualAge($date)
    {
        $birthDate = new \DateTime($date);
        $actualDate = new \DateTime($this->fechaHasta);
        $interval = $birthDate->diff($actualDate);
        $data = [
            'year' => $interval->y,
            'month' => intval($interval->m) + (intval($interval->y) > 0 ? intval($interval->y) * 12 : 0),
            'day' => $interval->days
        ];
        return $data;
    }

    private function calculatedAge($date, $dateService)
    {
        $birthDate = new \DateTime($date);
        $actualDate = new \DateTime($dateService);
        $interval = $birthDate->diff($actualDate);
        $data = [
            'year' => $interval->y,
            'month' => intval($interval->m) + (intval($interval->y) > 0 ? intval($interval->y) * 12 : 0),
            'day' => $interval->days
        ];
        return $data;
    }

    public function save202(Request $request)
    {
        $package202Exist = Packages_202::where('sedeproveedor_id', $request['info']['provider'])
            ->where('start_date', '>=', $request['info']['start_date'])
            ->where('final_date', '<=', $request['info']['finish_date'])
            ->where('partial', 1)
            ->first();
        if ($package202Exist) {
            DetailsPackages_202::where('package_202_id', $package202Exist->id)->delete();
            Packages_202::where('id', $package202Exist->id)->delete();
        }
        $package202 = Packages_202::create([
            'name' => $request['files'][202]['fileName'],
            'eapba' => $request['files'][202]['content'][0][1],
            'start_date' => $request['files'][202]['content'][0][2],
            'final_date' => $request['files'][202]['content'][0][3],
            'number_records' => $request['files'][202]['content'][0][4],
            'state_id' => 1,
            'sedeproveedor_id' => $request['files']['red']['id']
        ]);
        foreach ($request['files'][202]['content'] as $data) {
            if (count($data) > 5) {
                $array = $data;
                $array['package_202_id'] = $package202->id;
                DetailsPackages_202::create($array);
            }
        }

        return response()->json(['message' => 'Registro exitoso', 'id' => $package202->id]);
    }

    public function savePartial202(Request $request)
    {
        $package202Exist = Packages_202::where('sedeProveedor_id', $request['info']['provider'])
            ->where('start_date', '>=', $request['info']['start_date'])
            ->where('final_date', '<=', $request['info']['finish_date'])
            ->where('partial', 1)
            ->first();
        if ($package202Exist) {
            DetailsPackages_202::where('package_202_id', $package202Exist->id)->delete();
            Packages_202::where('id', $package202Exist->id)->delete();
        }
        $package202 = Packages_202::create([
            'name' => $request['files'][202]['fileName'],
            'eapba' => $request['files'][202]['content'][0][1],
            'start_date' => $request['files'][202]['content'][0][2],
            'final_date' => $request['files'][202]['content'][0][3],
            'number_records' => $request['files'][202]['content'][0][4],
            'state_id' => 1,
            'sedeproveedor_id' => $request['files']['red']['id'],
            'partial' => 1
        ]);
        foreach ($request['files'][202]['success'] as $data) {
            if (count($data) > 5) {
                $array = $data;
                $array['package_202_id'] = $package202->id;
                DetailsPackages_202::create($array);
            }
        }
        return response()->json(['message' => 'Registro exitoso', 'id' => $package202->id]);
    }


    public function loadInspected()
    {
        return response()->json(
            Packages_202::select([
                'packages_202s.id',
                'sp.Nombre as provider',
                'packages_202s.name',
                'packages_202s.created_at',
                'packages_202s.start_date',
                'packages_202s.final_date',
                'packages_202s.partial',
                's.Nombre as state',
                'packages_202s.state_id'
            ])->join('estados as s', 's.id', 'packages_202s.state_id')
                ->join('sedeproveedores as sp', 'sp.id', 'packages_202s.sedeproveedor_id')
                ->get());
    }

    public function file202($id)
    {
        $packages202 = Packages_202::find($id);
        $detailsPackages = DetailsPackages_202::where('package_202_id', $id)->get()->toArray();
        $data = "1|".$packages202->eapba. "|" . $packages202->start_date . "|" . $packages202->final_date . "|" . count($detailsPackages) . "\r" . PHP_EOL;
        $count = 1;
        foreach ($detailsPackages as $key => $details) {
            $array = $details;
            unset($array['id']);
            unset($array['package_202_id']);
            unset($array['created_at']);
            unset($array['updated_at']);
            unset($array['0']);
            unset($array['1']);
            if ($key != count($detailsPackages) - 1) {
                $data .= '2|' . $count . '|' .implode('|', $array) . "\r" . PHP_EOL;
            } else {
                $data .= '2|' . $count . '|' .implode('|', $array);
            }
            $count++;
        }
        return Response::make($data);
    }

    public function certificate($id, Request $request)
    {
        $pdf = new Certificate202();
        $pdf->generate($id, $request->type);
    }

    public function validateExist(Request $request)
    {
        $package202 = Packages_202::where('sedeproveedor_id', $request['provider'])
            ->where('start_date', '>=', $request['start_date'])
            ->where('final_date', '<=', $request['finish_date'])
            ->where('state_id',1)
            ->first();
        return response()->json($package202);
    }

    public function consolidate202(Request $request)
    {
        $fechaDesde = null;
        $fechaHasta = null;
        if(intval($request->tipoFecha) === 2){
            switch ($request->trimestre) {
                case '1':
                    $fechaDesde = $request->anio . '-01-01';
                    $fechaHasta = $request->anio . '-03-31';
                    break;
                case '2':
                    $fechaDesde = $request->anio . '-04-01';
                    $fechaHasta = $request->anio . '-06-30';
                    break;
                case '3':
                    $fechaDesde = $request->anio . '-07-01';
                    $fechaHasta = $request->anio . '-09-30';
                    break;
                case '4':
                    $fechaDesde = $request->anio . '-10-01';
                    $fechaHasta = $request->anio . '-12-31';
                    break;
            }
        }

        $sum = Packages_202::select(DB::raw("SUM(CAST(number_records as int)) as total"))->where('start_date', $fechaDesde)->where('final_date', $fechaHasta)
            ->where('state_id',1)
            ->first();
        $query = DetailsPackages_202::select(['details_packages_202s.*'])->join('packages_202s as p', 'p.id', 'details_packages_202s.package_202_id');
        if(intval($request->tipoFecha) === 1){
            $query->where('p.created_at','>=',$request->fechaDesde)
                ->where('p.created_at','<=',$request->fechaHasta);
        }else{
            $query->where('p.start_date', $fechaDesde)
                ->where('p.final_date', $fechaHasta);
        }
        $query->where('p.state_id',1)
            ->orderBy('details_packages_202s.id','ASC');
        $detailsPackages = $query->get()->toArray();
        $data = "1|RES004|" . $fechaDesde . "|" . $fechaHasta . "|" . count($detailsPackages). "\r" . PHP_EOL;
        $count = 1;
        foreach ($detailsPackages as $key => $details) {
            $array = $details;
            unset($array['id']);
            unset($array['package_202_id']);
            unset($array['created_at']);
            unset($array['updated_at']);
            unset($array['0']);
            unset($array['1']);

            if ($key != count($detailsPackages) - 1) {
                $str = '2|' . $count . '|' . implode('|', $array) . "\r" . PHP_EOL;
                $data .= $str;
            } else {
                $str = '2|' . $count . '|' . implode('|', $array);
                $data .= $str;
            }
            $count++;
        }
        return Response::make($data);
    }

    public function reportACAP($id)
    {
        $alerts = [];
        $package202 = Packages_202::find($id);
        $provider = Provider::find($package202->provider_id);
        $repsAliados = Rep::where('provider_associated_id', $provider->id)->where('state_id', 1)->get()->toArray();
        $packagesRip = PackagesRip::where('code',$provider->code)
            ->where('start_date','>=',$package202->start_date)
            ->where('finish_date','<=',$package202->final_date)
            ->get()->toArray();
        if($packagesRip){
            $arrPackagesRipId = array_column($packagesRip,'id');
            $details202 = DetailsPackages_202::where('package_202_id',$id)->orderBy('id','ASC')->get();
            foreach ($details202 as $index => $detail){
                if($detail['52'] > '1900-01-01'){
                    $arrDate52 = explode('-',$detail['52']);
                    $ACVariable52 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate52[2].'/'.$arrDate52[1].'/'.$arrDate52[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable52){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 669 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 52'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 670 el soporte RIPS AC en el campo código de prestador no esta en la red habilitada'];
                    }
                }

                if($detail['53'] > '1900-01-01'){
                    $arrDate53 = explode('-',$detail['53']);
                    $ACVariable53 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate53[2].'/'.$arrDate53[1].'/'.$arrDate53[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable53){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 671el soporte RIPS AC en el campo fecha no es igual al valor de la variable 53'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 672 el soporte RIPS AC en el campo código de prestador no está en la red habilitada'];
                    }
                }

                if($detail['56'] > '1900-01-01'){
                    $arrDate56 = explode('-',$detail['56']);
                    $ACVariable56 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate56[2].'/'.$arrDate56[1].'/'.$arrDate56[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable56){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 673 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 56'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 674 el soporte RIPS AC en el campo código de prestador no está en la red habilitada'];
                    }
                }

                if($detail['58'] > '1900-01-01'){
                    $arrDate58 = explode('-',$detail['58']);
                    $ACVariable58 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate58[2].'/'.$arrDate58[1].'/'.$arrDate58[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable58){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 675 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 58'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 676 el soporte RIPS AC en el campo código de prestador no está en la red habilitada'];
                    }
                }

                if($detail['62'] > '1900-01-01'){
                    $arrDate62 = explode('-',$detail['62']);
                    $ACVariable62 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate62[2].'/'.$arrDate62[1].'/'.$arrDate62[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable62){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 677 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 62'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 678 el soporte RIPS AC en el campo código de prestador no está en la red habilitada'];
                    }
                }

                if($detail['64'] > '1900-01-01'){
                    $arrDate64 = explode('-',$detail['64']);
                    $ACVariable64 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate64[2].'/'.$arrDate64[1].'/'.$arrDate64[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable64){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 679 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 64'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 680 el soporte RIPS AC en el campo código de prestador no está en la red habilitada'];
                    }
                }

                if($detail['76'] > '1900-01-01'){
                    $arrDate76 = explode('-',$detail['76']);
                    $ACVariable76 = Ac::where('document_number',$detail['4'])->where('consultation_date',$arrDate76[2].'/'.$arrDate76[1].'/'.$arrDate76[0])->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$ACVariable76){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 681 el soporte RIPS AC en el campo fecha no es igual al valor de la variable 76.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 682 el soporte RIPS AC en el campo código de prestador no está en la red habilitada.'];
                    }
                }

//                ------------------------------------------------------- AP -------------------------------------------------------


                if($detail['49'] > '1900-01-01') {
                    $arrDate49 = explode('-',$detail['49']);
                    $codes49 = ['721003','732201','735301','735930','735931','740001','740002','740003'];
                    $APVariable49 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate49[2].'/'.$arrDate49[1].'/'.$arrDate49[0])->whereIn('process_code',$codes49)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable49){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 683 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 49 o el código de procedimiento no corresponde a un parto.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 684 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['67'] > '1900-01-01') {
                    $arrDate67 = explode('-',$detail['67']);
                    $codes67 = ['907008'];
                    $APVariable67 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate67[2].'/'.$arrDate67[1].'/'.$arrDate67[0])->whereIn('process_code',$codes67)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable67){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 684 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 67 o el código de procedimiento no corresponde a cups 907008.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 685 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['72'] > '1900-01-01') {
                    $arrDate72 = explode('-',$detail['72']);
                    $codes72 = ['907008'];
                    $APVariable72 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate72[2].'/'.$arrDate72[1].'/'.$arrDate72[0])->whereIn('process_code',$codes72)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable72){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 686 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 72 o el código de procedimiento no corresponde a cups 907008.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 687 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['73'] > '1900-01-01') {
                    $arrDate73 = explode('-',$detail['73']);
                    $codes73 = ['906611'];
                    $APVariable73 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate73[2].'/'.$arrDate73[1].'/'.$arrDate73[0])->whereIn('process_code',$codes73)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable73){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 688 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 73 o el código de procedimiento no corresponde a cups 906611.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 689 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['78'] > '1900-01-01') {
                    $arrDate78 = explode('-',$detail['78']);
                    $codes78 = ['906317'];
                    $APVariable78 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate78[2].'/'.$arrDate78[1].'/'.$arrDate78[0])->whereIn('process_code',$codes78)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable78){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 690 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 78 o el código de procedimiento no corresponde a cups 906317.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 691 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['80'] > '1900-01-01') {
                    $arrDate80 = explode('-',$detail['80']);
                    $codes80 = ['906915'];
                    $APVariable80 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate80[2].'/'.$arrDate80[1].'/'.$arrDate80[0])->whereIn('process_code',$codes80)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable80){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 692 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 80 o el código de procedimiento no corresponde a cups 906915.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 693 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['82'] > '1900-01-01') {
                    $arrDate82 = explode('-',$detail['82']);
                    $codes82 = ['906249'];
                    $APVariable82 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate82[2].'/'.$arrDate82[1].'/'.$arrDate82[0])->whereIn('process_code',$codes82)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable82){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 694 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 82 o el código de procedimiento no corresponde a cups 906249.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 695 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['84'] > '1900-01-01') {
                    $arrDate84 = explode('-',$detail['84']);
                    $codes84 = ['904904'];
                    $APVariable84 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate84[2].'/'.$arrDate84[1].'/'.$arrDate84[0])->whereIn('process_code',$codes84)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable84){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 696 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 84 o el código de procedimiento no corresponde a cups 904904.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 697 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['87'] > '1900-01-01' and intval($detail['86']) === 1) {
                    $arrDate87 = explode('-',$detail['87']);
                    $codes87 = ['898001','892901'];
                    $APVariable87 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate87[2].'/'.$arrDate87[1].'/'.$arrDate87[0])->whereIn('process_code',$codes87)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable87){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 698 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 86 o el código de procedimiento no corresponde a cups Citología.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 699 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['91'] > '1900-01-01') {
                    $arrDate91 = explode('-',$detail['91']);
                    $codes91 = ['702203'];
                    $APVariable91 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate91[2].'/'.$arrDate91[1].'/'.$arrDate91[0])->whereIn('process_code',$codes91)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable91){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 700 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 91 o el código de procedimiento no corresponde a cups Colposcopia'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 701 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['93'] > '1900-01-01') {
                    $arrDate93 = explode('-',$detail['93']);
                    $codes93 = ['671201'];
                    $APVariable93 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate93[2].'/'.$arrDate93[1].'/'.$arrDate93[0])->whereIn('process_code',$codes93)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable93){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 702 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 93 o el código de procedimiento no corresponde a cups biopsia cérvico uterina'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 703 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['96'] > '1900-01-01') {
                    $arrDate96 = explode('-',$detail['96']);
                    $codes96 = ['876802'];
                    $APVariable96 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate96[2].'/'.$arrDate96[1].'/'.$arrDate96[0])->whereIn('process_code',$codes96)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable96){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 704 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 96 o el código de procedimiento no corresponde a cups mamografía'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 705 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }


                if($detail['99'] > '1900-01-01') {
                    $arrDate99 = explode('-',$detail['99']);
                    $codes99 = ['851101'];
                    $APVariable99 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate99[2].'/'.$arrDate99[1].'/'.$arrDate99[0])->whereIn('process_code',$codes99)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable99){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 706 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 99 o el código de procedimiento no corresponde a cups biopsia de mama.'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 707 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['103'] > '1900-01-01') {
                    $arrDate103 = explode('-',$detail['103']);
                    $codes103 = ['902213'];
                    $APVariable103 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate103[2].'/'.$arrDate103[1].'/'.$arrDate103[0])->whereIn('process_code',$codes103)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable103){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 708 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 103 o el código de procedimiento no corresponde a hemoglobina'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 709 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['105'] > '1900-01-01') {
                    $arrDate105 = explode('-',$detail['105']);
                    $codes105 = ['903841'];
                    $APVariable105 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate105[2].'/'.$arrDate105[1].'/'.$arrDate105[0])->whereIn('process_code',$codes105)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable105){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 710 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 105 o el código de procedimiento no corresponde a Glicemia basal'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 711 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['106'] > '1900-01-01') {
                    $arrDate106= explode('-',$detail['106']);
                    $codes106 = ['903876'];
                    $APVariable106 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate106[2].'/'.$arrDate106[1].'/'.$arrDate106[0])->whereIn('process_code',$codes106)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable106){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 712 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 106 o el código de procedimiento no corresponde a creatinina'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 713 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['110'] > '1900-01-01') {
                    $arrDate110= explode('-',$detail['110']);
                    $codes110 = ['906225'];
                    $APVariable110 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate110[2].'/'.$arrDate110[1].'/'.$arrDate110[0])->whereIn('process_code',$codes110)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable110){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 714 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 110 o el código de procedimiento no corresponde a hepatitis C'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 715 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['111'] > '1900-01-01') {
                    $arrDate111= explode('-',$detail['111']);
                    $codes111 = ['903815'];
                    $APVariable111 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate111[2].'/'.$arrDate111[1].'/'.$arrDate111[0])->whereIn('process_code',$codes111)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable111){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 716 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 111 o el código de procedimiento no corresponde a HDL'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 717 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['112'] > '1900-01-01') {
                    $arrDate112= explode('-',$detail['112']);
                    $codes112 = ['901101'];
                    $APVariable112 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate112[2].'/'.$arrDate112[1].'/'.$arrDate112[0])->whereIn('process_code',$codes112)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable112){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 718 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 112 o el código de procedimiento no corresponde a baciloscopia'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 719 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }

                if($detail['118'] > '1900-01-01') {
                    $arrDate118= explode('-',$detail['118']);
                    $codes118 = ['903868'];
                    $APVariable118 = Ap::where('document_number',$detail['4'])->where('process_date',$arrDate118[2].'/'.$arrDate118[1].'/'.$arrDate118[0])->whereIn('process_code',$codes118)->whereIn('packages_rip_id',$arrPackagesRipId)->first();
                    if(!$APVariable118){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 720 el soporte RIPS AP en el campo fecha no es igual al valor de la variable 118 o el código de procedimiento no corresponde a Triglicéridos'];
                    }
                    if(array_search($detail['2'], array_column($repsAliados, 'code')) === false){
                        $alerts[] = ['linea' => $index+1,'Mensaje' => 'Alerta 721 el soporte RIPS AP en el campo código de prestador no está en la red habilitada.'];
                    }
                }




            }
        }

        return (new FastExcel(collect($alerts)))->download('file.xlsx');
    }

    public function getProgramming(){
        $programacion = Programming_202::all();
        return response()->json($programacion);
    }

    public function Programming($year){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $programacion = Programming_202::where('anio',$year)->first();
        $lineasBase = PatientProgramming::where('programming_202_id',$programacion->id)->get();
        foreach ($lineasBase as $lineaBase){
            $dates = $this->calculatedAge($lineaBase->birth_date,$year.'-01-01');
            if($lineaBase->gender === 'M' && intval($dates['year']) >= 50 && intval($dates['year']) < 80){
                $lineaBase->tacto_rectal = 1;
                $detalles22 = DetailsPackages_202::select(['22'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('22',['4','5'])->get();
                if(count($detalles22) > 0){
                    $lineaBase->ejecuto_tacto_rectal = 1;
                }else{
                    $lineaBase->ejecuto_tacto_rectal = 0;
                }
            }else{
                $lineaBase->tacto_rectal = 0;
                $lineaBase->ejecuto_tacto_rectal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 50 && intval($dates['year']) < 75){
                $lineaBase->prueba_sangre_oculta_materia_fecal = 1;
                $detalles24 = DetailsPackages_202::select(['24'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('24',['4','5','6'])->get();
                if(count($detalles24) > 0){
                    $lineaBase->ejecuto_prueba_sangre_oculta_materia_fecal = 1;
                }else{
                    $lineaBase->ejecuto_prueba_sangre_oculta_materia_fecal = 0;
                }
            }else{
                $lineaBase->prueba_sangre_oculta_materia_fecal = 0;
                $lineaBase->ejecuto_prueba_sangre_oculta_materia_fecal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            $lineaBase->peso = 1;
            $detalles29 = DetailsPackages_202::select(['29'])
                ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                ->where('p.start_date','>=', $year.'-01-01')
                ->where('p.final_date','<=',$year.'-12-31')
                ->where('details_packages_202s.4',$lineaBase->document)
                ->where('29','>','1900-01-01')->get();
            if(count($detalles29) > 0){
                $lineaBase->ejecuto_peso = 1;
            }else{
                $lineaBase->ejecuto_peso = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            $lineaBase->talla = 1;
            $detalles31 = DetailsPackages_202::select(['31'])
                ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                ->where('p.start_date','>=', $year.'-01-01')
                ->where('p.final_date','<=',$year.'-12-31')
                ->where('details_packages_202s.4',$lineaBase->document)
                ->where('31','>','1900-01-01')->get();
            if(count($detalles31) > 0){
                $lineaBase->ejecuto_talla = 1;
            }else{
                $lineaBase->ejecuto_talla = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) <= 3){
                $lineaBase->tamizaje_auditivo_neonatal = 1;
                $detalles37 = DetailsPackages_202::select(['37'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('37',['4','5'])->get();
                if(count($detalles37) > 0){
                    $lineaBase->ejecuto_tamizaje_auditivo_neonatal = 1;
                }else{
                    $lineaBase->ejecuto_tamizaje_auditivo_neonatal = 0;
                }
            }else{
                $lineaBase->tamizaje_auditivo_neonatal = 0;
                $lineaBase->ejecuto_tamizaje_auditivo_neonatal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) <= 3){
                $lineaBase->tamizaje_visual_neonatal = 1;
                $detalles38 = DetailsPackages_202::select(['38'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('38',['4','5'])->get();
                if(count($detalles38) > 0){
                    $lineaBase->ejecuto_tamizaje_visual_neonatal = 1;
                }else{
                    $lineaBase->ejecuto_tamizaje_visual_neonatal = 0;
                }
            }else{
                $lineaBase->tamizaje_visual_neonatal = 0;
                $lineaBase->ejecuto_tamizaje_visual_neonatal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) > 50){
                $lineaBase->tamizaje_hepatitis_c = 1;
                $detalles42 = DetailsPackages_202::select(['42'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('42',['4','5'])->get();
                if(count($detalles42) > 0){
                    $lineaBase->ejecuto_tamizaje_hepatitis_c = 1;
                }else{
                    $lineaBase->ejecuto_tamizaje_hepatitis_c = 0;
                }
            }else{
                $lineaBase->tamizaje_hepatitis_c = 0;
                $lineaBase->ejecuto_tamizaje_hepatitis_c = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) < 8){
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_gruesa = 1;
                $detalles43 = DetailsPackages_202::select(['43'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('43',['4','5','3'])->get();
                if(count($detalles43) > 0){
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_gruesa = 1;
                }else{
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_gruesa = 0;
                }
            }else{
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_gruesa = 0;
                $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_gruesa = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) < 8){
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_finoadaptativa = 1;
                $detalles44 = DetailsPackages_202::select(['44'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('44',['4','5','3'])->get();
                if(count($detalles44) > 0){
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_finoadaptativa = 1;
                }else{
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_finoadaptativa = 0;
                }
            }else{
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_finoadaptativa = 0;
                $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_finoadaptativa = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) < 8){
                $lineaBase->escala_abreviada_desarrollo_area_personal_social = 1;
                $detalles45 = DetailsPackages_202::select(['45'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('45',['4','5','3'])->get();
                if(count($detalles45) > 0){
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_personal_social = 1;
                }else{
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_personal_social = 0;
                }
            }else{
                $lineaBase->escala_abreviada_desarrollo_area_personal_social = 0;
                $lineaBase->ejecuto_escala_abreviada_desarrollo_area_personal_social = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) < 8){
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_audicion_lenguaje = 1;
                $detalles46 = DetailsPackages_202::select(['46'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('46',['4','5','3'])->get();
                if(count($detalles46) > 0){
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_audicion_lenguaje = 1;
                }else{
                    $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_audicion_lenguaje = 0;
                }
            }else{
                $lineaBase->escala_abreviada_desarrollo_area_motricidad_audicion_lenguaje = 0;
                $lineaBase->ejecuto_escala_abreviada_desarrollo_area_motricidad_audicion_lenguaje = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['day']) < 30){
                $lineaBase->tamizacion_oximetria_pre_post_ductal = 1;
                $detalles48 = DetailsPackages_202::select(['48'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('48',['4','5',])->get();
                if(count($detalles48) > 0){
                    $lineaBase->ejecuto_tamizacion_oximetria_pre_post_ductal = 1;
                }else{
                    $lineaBase->ejecuto_tamizacion_oximetria_pre_post_ductal = 0;
                }
            }else{
                $lineaBase->tamizacion_oximetria_pre_post_ductal = 0;
                $lineaBase->ejecuto_tamizacion_oximetria_pre_post_ductal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            $lineaBase->consulta_valoracion_integral = 1;
            $detalles52 = DetailsPackages_202::select(['52'])
                ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                ->where('p.start_date','>=', $year.'-01-01')
                ->where('p.final_date','<=',$year.'-12-31')
                ->where('details_packages_202s.4',$lineaBase->document)
                ->where('52','>','1900-01-01')->get();
            if(count($detalles52) > 0){
                $lineaBase->ejecuto_consulta_valoracion_integral = 1;
            }else{
                $lineaBase->ejecuto_consulta_valoracion_integral = 0;
            }

//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 10 && intval($dates['year']) < 60){
                $lineaBase->salud_asesoria_anticoncepcion = 1;
                $detalles53 = DetailsPackages_202::select(['53'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('53','>','1900-01-01')->get();
                if(count($detalles53) > 0){
                    $lineaBase->ejecuto_salud_asesoria_anticoncepcion = 1;
                }else{
                    $lineaBase->ejecuto_salud_asesoria_anticoncepcion = 0;
                }
            }else{
                $lineaBase->salud_asesoria_anticoncepcion = 0;
                $lineaBase->ejecuto_salud_asesoria_anticoncepcion = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 10){
                $lineaBase->suministro_metodo_anticonceptivo = 1;
                $detalles54 = DetailsPackages_202::select(['54'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('54',['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'])->get();
                if(count($detalles54) > 0){
                    $lineaBase->ejecuto_suministro_metodo_anticonceptivo = 1;
                }else{
                    $lineaBase->ejecuto_suministro_metodo_anticonceptivo = 0;
                }
            }else{
                $lineaBase->suministro_metodo_anticonceptivo = 0;
                $lineaBase->ejecuto_suministro_metodo_anticonceptivo = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->glicemia_basal = 1;
                $detalles57 = DetailsPackages_202::select(['57'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereNotIn('57',['0','998'])->get();
                if(count($detalles57) > 0){
                    $lineaBase->ejecuto_glicemia_basal = 1;
                }else{
                    $lineaBase->ejecuto_glicemia_basal = 0;
                }
            }else{
                $lineaBase->glicemia_basal = 0;
                $lineaBase->ejecuto_glicemia_basal = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 3){
                $lineaBase->agudeza_visual = 1;
                $detalles62 = DetailsPackages_202::select(['62'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('62','>','1900-01-01')->get();
                if(count($detalles62) > 0){
                    $lineaBase->ejecuto_agudeza_visual = 1;
                }else{
                    $lineaBase->ejecuto_agudeza_visual = 0;
                }
            }else{
                $lineaBase->agudeza_visual = 0;
                $lineaBase->ejecuto_agudeza_visual = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) >= 6 && intval($dates['month']) <= 23){
                $lineaBase->fortificacion_casera_primera_infancia = 1;
                $detalles70 = DetailsPackages_202::select(['70'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('70',['1'])->get();
                if(count($detalles70) > 0){
                    $lineaBase->ejecuto_fortificacion_casera_primera_infancia = 1;
                }else{
                    $lineaBase->ejecuto_fortificacion_casera_primera_infancia = 0;
                }
            }else{
                $lineaBase->fortificacion_casera_primera_infancia = 0;
                $lineaBase->ejecuto_fortificacion_casera_primera_infancia = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) >= 24 && intval($dates['month']) <= 59){
                $lineaBase->vitamina_a_primera_infancia = 1;
                $detalles71 = DetailsPackages_202::select(['71'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('71',['1'])->get();
                if(count($detalles71) > 0){
                    $lineaBase->ejecuto_vitamina_a_primera_infancia = 1;
                }else{
                    $lineaBase->ejecuto_vitamina_a_primera_infancia = 0;
                }
            }else{
                $lineaBase->vitamina_a_primera_infancia = 0;
                $lineaBase->ejecuto_vitamina_a_primera_infancia = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->ldl = 1;
                $detalles72 = DetailsPackages_202::select(['72'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('72','>','1900-01-01')->get();
                if(count($detalles72) > 0){
                    $lineaBase->ejecuto_ldl = 1;
                }else{
                    $lineaBase->ejecuto_ldl = 0;
                }
            }else{
                $lineaBase->ldl = 0;
                $lineaBase->ejecuto_ldl = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if($lineaBase->gender === 'M' && intval($dates['year']) >= 50 && intval($dates['year']) <= 75 ){
                $lineaBase->psa = 1;
                $detalles73 = DetailsPackages_202::select(['73'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('73','>','1900-01-01')->get();
                if(count($detalles73) > 0){
                    $lineaBase->ejecuto_psa = 1;
                }else{
                    $lineaBase->ejecuto_psa = 0;
                }
            }else{
                $lineaBase->psa = 0;
                $lineaBase->ejecuto_psa = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) >= 6 ){
                $lineaBase->salud_bucal_profesional_odontologia = 1;
                $detalles76 = DetailsPackages_202::select(['76'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('76','>','1900-01-01')->get();
                if(count($detalles76) > 0){
                    $lineaBase->ejecuto_salud_bucal_profesional_odontologia = 1;
                }else{
                    $lineaBase->ejecuto_salud_bucal_profesional_odontologia = 0;
                }
            }else{
                $lineaBase->salud_bucal_profesional_odontologia = 0;
                $lineaBase->ejecuto_salud_bucal_profesional_odontologia = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) >= 24 && intval($dates['month']) <= 59){
                $lineaBase->suministro_hierro_primera_infancia = 1;
                $detalles77 = DetailsPackages_202::select(['77'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('77',['1'])->get();
                if(count($detalles77) > 0){
                    $lineaBase->ejecuto_suministro_hierro_primera_infancia = 1;
                }else{
                    $lineaBase->ejecuto_suministro_hierro_primera_infancia = 0;
                }
            }else{
                $lineaBase->suministro_hierro_primera_infancia = 0;
                $lineaBase->ejecuto_suministro_hierro_primera_infancia = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if($lineaBase->gender === 'F' && intval($dates['year']) >= 25 && intval($dates['year']) <= 65){
                $lineaBase->tamizaje_cancer_cuello_uterino = 1;
                $detalles87 = DetailsPackages_202::select(['87'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('87','>','1900-01-01')->get();
                if(count($detalles87) > 0){
                    $lineaBase->ejecuto_tamizaje_cancer_cuello_uterino = 1;
                }else{
                    $lineaBase->ejecuto_tamizaje_cancer_cuello_uterino = 0;
                }
            }else{
                $lineaBase->tamizaje_cancer_cuello_uterino = 0;
                $lineaBase->ejecuto_tamizaje_cancer_cuello_uterino = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if($lineaBase->gender === 'F' && intval($dates['year']) >= 25 && intval($dates['year']) <= 65){
                $lineaBase->citologia_cervicouterina = 1;
                $detalles89 = DetailsPackages_202::select(['89'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('89',['1','2','3','4'])->get();
                if(count($detalles89) > 0){
                    $lineaBase->ejecuto_citologia_cervicouterina = 1;
                }else{
                    $lineaBase->ejecuto_citologia_cervicouterina = 0;
                }
            }else{
                $lineaBase->citologia_cervicouterina = 0;
                $lineaBase->ejecuto_citologia_cervicouterina = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->hdl = 1;
                $detalles95 = DetailsPackages_202::select(['95'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereNotIn('95',['0','998'])->get();
                if(count($detalles95) > 0){
                    $lineaBase->ejecuto_hdl = 1;
                }else{
                    $lineaBase->ejecuto_hdl = 0;
                }
            }else{
                $lineaBase->hdl = 0;
                $lineaBase->ejecuto_hdl = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if($lineaBase->gender === 'F' && intval($dates['year']) >= 50 && intval($dates['year']) <= 69){
                $lineaBase->mamografia = 1;
                $detalles96 = DetailsPackages_202::select(['96'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('96','>','1900-01-01')->get();
                if(count($detalles96) > 0){
                    $lineaBase->ejecuto_mamografia = 1;
                }else{
                    $lineaBase->ejecuto_mamografia = 0;
                }
            }else{
                $lineaBase->mamografia = 0;
                $lineaBase->ejecuto_mamografia = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->trigliceridos = 1;
                $detalles98 = DetailsPackages_202::select(['98'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereNotIn('98',['0','998'])->get();
                if(count($detalles98) > 0){
                    $lineaBase->ejecuto_trigliceridos = 1;
                }else{
                    $lineaBase->ejecuto_trigliceridos = 0;
                }
            }else{
                $lineaBase->trigliceridos = 0;
                $lineaBase->ejecuto_trigliceridos = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['month']) >= 6){
                $lineaBase->cop = 1;
                $detalles102 = DetailsPackages_202::select(['102'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereNotIn('102',['0','21'])->get();
                if(count($detalles102) > 0){
                    $lineaBase->ejecuto_cop = 1;
                }else{
                    $lineaBase->ejecuto_cop = 0;
                }
            }else{
                $lineaBase->cop = 0;
                $lineaBase->ejecuto_cop = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if($lineaBase->gender === 'F' && intval($dates['year']) >= 10 && intval($dates['year']) <= 17){
                $lineaBase->hemoglobina = 1;
                $detalles103 = DetailsPackages_202::select(['103'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('103','>','1900-01-01')->get();
                if(count($detalles103) > 0){
                    $lineaBase->ejecuto_hemoglobina = 1;
                }else{
                    $lineaBase->ejecuto_hemoglobina = 0;
                }
            }else{
                $lineaBase->hemoglobina = 0;
                $lineaBase->ejecuto_hemoglobina = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->creatinina = 1;
                $detalles106 = DetailsPackages_202::select(['106'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->where('106','>','1900-01-01')->get();
                if(count($detalles106) > 0){
                    $lineaBase->ejecuto_creatinina = 1;
                }else{
                    $lineaBase->ejecuto_creatinina = 0;
                }
            }else{
                $lineaBase->creatinina = 0;
                $lineaBase->ejecuto_creatinina = 0;
            }
//            --------------------------------------------------------------------------------------------------------------
            if(intval($dates['year']) >= 29){
                $lineaBase->riesgo_cardiovascular = 1;
                $detalles114 = DetailsPackages_202::select(['114'])
                    ->join('packages_202s as p','p.id','details_packages_202s.package_202_id')
                    ->where('p.start_date','>=', $year.'-01-01')
                    ->where('p.final_date','<=',$year.'-12-31')
                    ->where('details_packages_202s.4',$lineaBase->document)
                    ->whereIn('114',['4','5','6'])->get();
                if(count($detalles114) > 0){
                    $lineaBase->ejecuto_riesgo_cardiovascular = 1;
                }else{
                    $lineaBase->ejecuto_riesgo_cardiovascular = 0;
                }
            }else{
                $lineaBase->riesgo_cardiovascular = 0;
                $lineaBase->ejecuto_riesgo_cardiovascular = 0;

            }
            $lineaBase->save();
        }
        $query = PatientProgramming::select(['document as documento','patient_programmings.*'])->where('programming_202_id',$programacion->id)->get();
        $result = $query->makeHidden(['id', 'programming_202_id', 'patient_id', 'gender','created_at','updated_at','birth_date','document'])->toArray();
        return (new FastExcel(collect($result)))->download('file.xlsx');

    }

    public function baseLine(){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $message = "Creacion Exitosa";
        $type = 'success';
        $anio = intval(date('Y'));
        $programacion = Programming_202::where('anio',$anio)->first();
        if(!$programacion){
            $nuevaProgramacion = Programming_202::create(['anio'=>$anio]);
            $patients = Patient::where('state_id',1)->get();
            foreach ($patients as $patient){
                PatientProgramming::create([
                    'patient_id' => $patient->id,
                    'programming_202_id' => $nuevaProgramacion->id,
                    'birth_date' => $patient->birth_date,
                    'gender' => $patient->gender,
                    'document' => $patient->document
                ]);
            }
        }else{
            $message = "Linea base ya se encuentra creada";
            $type = 'error';
        }

        $result = ['message' => $message,'type'  => $type];
        return response()->json($result);

    }

    public function reject202($id){
        DetailsPackages_202::where('package_202_id',$id)->delete();
        Packages_202::find($id)
            ->update(['state_id' => 6]);
        return response()->json(['message' => 'Paquete Rechazado']);
    }

    public function exportErrors(Request $request)
    {
        $appointments = collect($request->all());
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }
}
