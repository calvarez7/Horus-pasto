<?php
namespace App\Formats;

use App\Modelos\Agenda;
use App\Modelos\agendauser;
use App\Modelos\Cita;
use App\Modelos\citapaciente;
use App\Modelos\Consultorio;
use App\Modelos\Especialidade;
use App\Modelos\Especialidadtipoagenda;
use App\Modelos\Paciente;
use App\Modelos\Sede;
use App\Modelos\TipoAgenda;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CertificadoCitas extends FPDF
{
 public static $citas;
    public function generar($datos)
    {
        $pdf= new CertificadoCitas('p', 'mm', 'A4');

        self::$citas = Cita::select(DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as paciente"),
            'pacientes.Tipo_Doc','pacientes.Num_Doc','tipo_agendas.name as nombreCita','especialidades.Nombre as especialidadCita','citas.Hora_Inicio',
            DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"),'sedes.Nombre as sede','sedes.direccion as direcionSede','tipo_agendas.modalidad'
        )
        ->join('cita_paciente','cita_paciente.cita_id','citas.id')
            ->join('pacientes','pacientes.id','cita_paciente.Paciente_id')
            ->join('agendas','agendas.id','citas.Agenda_id')
            ->join('especialidade_tipoagenda','especialidade_tipoagenda.id','agendas.Especialidad_id')
            ->join('tipo_agendas','tipo_agendas.id','especialidade_tipoagenda.Tipoagenda_id')
            ->join('especialidades','especialidades.id','especialidade_tipoagenda.Especialidad_id')
            ->join('consultorios','consultorios.id','agendas.Consultorio_id')
            ->join('sedes','sedes.id','consultorios.Sede_id')
            ->join('agendausers','agendausers.Agenda_id','agendas.id')
            ->join('users','users.id','agendausers.Medico_id')
            ->where('cita_paciente.Estado_id',4)
            ->where('pacientes.id',$datos['paciente_id'])
            ->where('citas.id',$datos['id'])
            ->first();

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Output();
    }
    public function Header(){
        $this->SetDrawColor(140,190,56);
        $this->Rect(8, 5, 195, 130);
        $this->SetDrawColor(0,0,0);

        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 16, 9, -220);
        $this->SetFont('Arial', '', 7);
        $this->SetXY(8, 37);
        $this->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');
        $pdf = $this;
        $pdf->SetXY(35,25);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(186, 4, utf8_decode('COMPROBANTE DE ASIGNACIÓN DE CITAS'), 0, 0, 'C');

        $pdf->SetXY(12,43);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL LA CITA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetXY(12, 49);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(187, 4, utf8_decode('Señor(A)'.' '.self::$citas->paciente.' '.'con numero de documento'.' '.self::$citas->Tipo_Doc.' '.self::$citas->Num_Doc.' '.'se le informa que su cita de'.' '.
            self::$citas->nombreCita.' '.'de'.' '.self::$citas->especialidadCita.' '.'es el dia'.' '.Carbon::parse(self::$citas->Hora_Inicio)->isoFormat('dddd D MMMM [de] YYYY, [hora] h:mm a').' con el medico'.' '.self::$citas->Medico.' en la sede'.' '.self::$citas->sede.' '.'con direccion'.' '.self::$citas->direcionSede.
    ' '.'en la modalidad'.' '.self::$citas->modalidad.'. '.' Si su cita es con especialista por favor llevar a la consulta, historias clínicas de atenciones previas. si no puede asistir, cancele con un día de anticipación en https://www.horus-health.com/gestion/citas, click en pendientes y cancelar.'), 0, 'J');

        $link = 'https://www.horus-health.com/gestion/citas';
        $pdf->ln();
        $pdf->ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(185, 4, utf8_decode('Puede ingresar a la pagina dando click AQUI'), 0, 0, 'C',false,$link);

        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,85,'Funcionario que Imprime:','U');
        if(auth()->user()){
            $this->TextWithDirection(206, 50, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');
        }else{
            $this->TextWithDirection(206, 50, utf8_decode('Impreso por el paciente'), 'U');
        }
        $this->TextWithDirection(209,85,utf8_decode('Fecha Impresión:'),'U');
        $this->TextWithDirection(209, 60, Carbon::now()->isoFormat('dddd D MMMM [de] YYYY, [hora] h:mm a'), 'U');

    }
    public function TextWithDirection($x, $y, $txt, $direction = 'R')
    {

        if ($direction == 'U') {
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        }
        if ($this->ColorFlag) {
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        }
        $this->_out($s);
    }
}
