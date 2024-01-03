<?php
namespace App\Formats;

use App\Modelos\cup;
use App\User;
use App\Modelos\Paciente;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\citapaciente;
use App\Formats\ConcentimientosInformados;
use Illuminate\Support\Facades\Storage;

class ConcentimientosInformados extends FPDF{
    public static $data;
    public static $paciente;
    public static $fechaHoy;
    public static $medicoAtendio;

    //MultiCell with bullet
    function MultiCellBlt($w, $h, $blt, $txt, $border=0, $align='J', $fill=false)
    {
        //Get bullet width including margins
        $blt_width = $this->GetStringWidth($blt)+$this->cMargin*2;

        //Save x
        $bak_x = $this->x;

        //Output bullet
        $this->Cell($blt_width,$h,$blt,0,'',$fill);

        //Output text
        $this->MultiCell($w-$blt_width,$h,$txt,$border,$align,$fill);

        //Restore x
        $this->x = $bak_x;
    }

    public function generar($datos){
        self::$data = $datos;
        self::$paciente = Paciente::where('id',self::$data['paciente_id'])->first();
        self::$fechaHoy = date('d-m-Y h:i:s a');
        self::$medicoAtendio = citapaciente::select('name','apellido', 'Firma','cita_paciente.firma_paciente','cita_paciente.ruta_foto',
        'aceptacion_consentimiento','firmante','numero_documento_representante')
            ->join('users as us', 'us.id', 'cita_paciente.user_medico_atiende')
            ->where('cita_paciente.id', self::$data["cita_paciente_id"])
            ->first();
        $pdf = new ConcentimientosInformados('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        if (self::$data['id']  == '8471' || self::$data['id'] == '12789'){
            $this->bodyLavado_oidos($pdf);
        }elseif (self::$data['id']  == '3722' || self::$data['id']  == '3720' || self::$data['id']  == '3721') {
          $this->bodycistouretropexia($pdf);
        }elseif (self::$data['id']  == '4334' || self::$data['id']  == '8509' || self::$data['id']  == '11966' || self::$data['id']  == '4330' || self::$data['id']  == '4331' || self::$data['id']  == '4332' || self::$data['id']  == '8528' ) {
            $this->bodyinfiltraciones($pdf);
        }elseif (self::$data['id']  == '4335' || self::$data['id'] == '4329') {
            $this->bodysudermico($pdf);
        }elseif (self::$data['id']  == '8490' || self::$data['id']  == '12020' || self::$data['id'] == '4072') {
           $this->bodyDIU($pdf);
        }elseif(self::$data['id'] == '293'||self::$data['id'] == '5733' ||self::$data['id'] == '5826'
        ||self::$data['id'] == '5827'||self::$data['id'] == '5828' ||self::$data['id'] == '5829'
        ||self::$data['id'] == '5830'||self::$data['id'] == '5831' ||self::$data['id'] == '7722'
        ||self::$data['id'] == '8496'|| self::$data['id'] == '4578' || self::$data['id'] == '8495'
        ||self::$data['id'] == '8497'||self::$data['id'] == '8498' ||self::$data['id'] == '8499'
        ||self::$data['id'] == '10621'){
            $this->bodyRetiroCuerpoExtraño($pdf);
        }elseif( self::$data['id'] == '4582' || self::$data['id'] == '4583' || self::$data['id'] == '4587'
        || self::$data['id'] == '4588' || self::$data['id'] == '4590' || self::$data['id'] == '4592' || self::$data['id'] == '4594'
        || self::$data['id'] == '8213' || self::$data['id'] == '9056' || self::$data['id'] == '9073'
        || self::$data['id'] == '9074' || self::$data['id'] == '9089'
        ){
            $this->bodycirugiaParpadosYConjuntivas($pdf);
        }elseif(self::$data['id'] == '7723' || self::$data['id'] == '7724' || self::$data['id'] == '7732' || self::$data['id'] == '7735'
        || self::$data['id'] == '7736' || self::$data['id'] == '7744' || self::$data['id'] == '7746' || self::$data['id'] == '7778'){
            $this->bodycirugiaAmputacionesMayores($pdf);
        }elseif(
        self::$data['id'] == '4258' || self::$data['id'] == '4259' || self::$data['id'] == '4291' || self::$data['id'] == '4298'
        || self::$data['id'] == '4299'){
            $this->bodyReseccionCuaqdranteMamayColgajo($pdf);
        }elseif(self::$data['id'] == '4009' || self::$data['id'] == '12138' || self::$data['id'] == '12237'){
            $this->bodyHisteroscopiaDiagnostica($pdf);
        }elseif(self::$data['id'] == '7211' || self::$data['id'] == '7212' || self::$data['id'] == '7213' || self::$data['id'] == '7216' ||
        self::$data['id'] == '7217' || self::$data['id'] == '7219' || self::$data['id'] == '7220' || self::$data['id'] == '7225' ||
        self::$data['id'] == '7226' || self::$data['id'] == '7227' || self::$data['id'] == '7228' || self::$data['id'] == '7229' ||
        self::$data['id'] == '7230' || self::$data['id'] == '7231' || self::$data['id'] == '7232' || self::$data['id'] == '7233' ||
        self::$data['id'] == '7234' || self::$data['id'] == '7235' || self::$data['id'] == '7236' || self::$data['id'] == '7237' ||
        self::$data['id'] == '7238' || self::$data['id'] == '7239' || self::$data['id'] == '7240' || self::$data['id'] == '7241' ||
        self::$data['id'] == '7242' || self::$data['id'] == '7243' || self::$data['id'] == '7244' || self::$data['id'] == '7245' ||
        self::$data['id'] == '7246' || self::$data['id'] == '7247' || self::$data['id'] == '7248' || self::$data['id'] == '7249' ||
        self::$data['id'] == '7250' || self::$data['id'] == '7251' || self::$data['id'] == '7252' || self::$data['id'] == '7253' ||
        self::$data['id'] == '7254' || self::$data['id'] == '7255' || self::$data['id'] == '7256' || self::$data['id'] == '7257' ||
        self::$data['id'] == '7258' || self::$data['id'] == '7259' || self::$data['id'] == '7260' || self::$data['id'] == '7261' ||
        self::$data['id'] == '7262' || self::$data['id'] == '7263' || self::$data['id'] == '12526' || self::$data['id'] == '12527' ||
        self::$data['id'] == '12537' || self::$data['id'] == '12554' || self::$data['id'] == '12555' || self::$data['id'] == '12556' ||
        self::$data['id'] == '12557'
        ){
            $this->bodyReduccionAbiertaFactura($pdf);
        }elseif(self::$data['id'] == '9058' || self::$data['id'] == '9059'){
            $this->bodyReseccionChalazion($pdf);
        }elseif(self::$data['id'] == '4473' || self::$data['id'] == '4474'){
            $this->bodyEseccionSimpleCicatrizAreaEspecial($pdf);
        }elseif(self::$data['id'] == '7635' || self::$data['id'] == '7636' || self::$data['id'] == '7637' || self::$data['id'] == '7639'){
            $this->bodyProtesisArticularCadera($pdf);
        }elseif(self::$data['id'] == '12963'){
            $this->bodyReseccionGinecomastia($pdf);
        }elseif(self::$data['id'] == '4267' || self::$data['id'] == '12252' || self::$data['id'] == '12988'){
            $this->bodyMamoplastiaReduccionBilateral($pdf);
        }elseif(self::$data['id'] == '7909' || self::$data['id'] == '12216' || self::$data['id'] == '12217' || self::$data['id'] == '12225'
        || self::$data['id'] == '12226' || self::$data['id'] == '12227' || self::$data['id'] == '12283'
        || self::$data['id'] == '12284' || self::$data['id'] == '12285' || self::$data['id'] == '12286'){
            $this->bodyOsteotomiaRodilla($pdf);
        }elseif(self::$data['id'] == '4584' || self::$data['id'] == '4585' || self::$data['id'] == '4586'){
            $this->bodyReseccionPteringion($pdf);
        }elseif(self::$data['id'] == '8193' || self::$data['id'] == '8194'){
            $this->bodyCambioSondaGastrostomia($pdf);
        }elseif(self::$data['id'] == '5295' || self::$data['id'] == '5296' || self::$data['id'] == '5297' || self::$data['id'] == '7332'
        || self::$data['id'] == '7858' || self::$data['id'] == '12197' || self::$data['id'] == '12198' || self::$data['id'] == '12199'
        || self::$data['id'] == '12211' || self::$data['id'] == '12212'){
            $this->bodyTratamientoQxHalluxValgus($pdf);
        }elseif(self::$data['id'] == '2743' || self::$data['id'] == '8931' || self::$data['id'] == '8940' || self::$data['id'] == '8941'
        || self::$data['id'] == '8942' || self::$data['id'] == '8958' || self::$data['id'] == '9701' || self::$data['id'] == '9742'
        || self::$data['id'] == '10366' || self::$data['id'] == '10368' || self::$data['id'] == '10369'){
            $this->bodyAnestesia($pdf);
        }elseif(
        self::$data['id'] == '4104' || self::$data['id'] == '4105' || self::$data['id'] == '4106' || self::$data['id'] == '4107'
        || self::$data['id'] == '4108'){
            $this->bodyColporrafiaAnteriorPosterior($pdf);
        }elseif(self::$data['id'] == '7714' || self::$data['id'] == '7715' || self::$data['id'] == '7716' || self::$data['id'] == '7717'
        || self::$data['id'] == '7718' || self::$data['id'] == '7930' || self::$data['id'] == '12489'){
            $this->bodyCorreccionLesionesManguitoRotador($pdf);
        }elseif(self::$data['id'] == '2937'){
            $this->bodyLesionTejidoRectalCondilomas($pdf);
        }elseif(self::$data['id'] == '11246' || self::$data['id'] == '11247'){
            $this->bodyTraqueostomia($pdf);
        }elseif(self::$data['id'] == '4022' || self::$data['id'] == '4023' || self::$data['id'] == '4024' || self::$data['id'] == '4025'
        || self::$data['id'] == '4026' || self::$data['id'] == '4027' || self::$data['id'] == '4028' || self::$data['id'] == '4029'
        || self::$data['id'] == '4031' || self::$data['id'] == '4032' || self::$data['id'] == '4034' || self::$data['id'] == '12079'
        || self::$data['id'] == '11987' || self::$data['id'] == '11992' || self::$data['id'] == '11993' || self::$data['id'] == '12005'
        || self::$data['id'] == '12007' || self::$data['id'] == '12013' || self::$data['id'] == '12109'
        || self::$data['id'] == '12110' || self::$data['id'] == '12111' || self::$data['id'] == '12127'){
            $this->bodyHistectomia($pdf);
        }elseif(self::$data['id'] == '4015' || self::$data['id'] == '4038' || self::$data['id'] == '4076' || self::$data['id'] == '4178' || self::$data['id'] == '4179'){
            $this->bodyLegradoGinecologico($pdf);
        }elseif(self::$data['id'] == '4249' || self::$data['id'] == '4250' || self::$data['id'] == '4251'){
            $this->bodyBiopsiaMamaAnclaje($pdf);
        }elseif(self::$data['id'] == '3932' || self::$data['id'] == '3933' || self::$data['id'] == '3934' || self::$data['id'] == '3935' || self::$data['id'] == '3936'
        || self::$data['id'] == '3937' || self::$data['id'] == '3938' || self::$data['id'] == '3946' || self::$data['id'] == '3952' || self::$data['id'] == '3977' || self::$data['id'] == '11946'
        || self::$data['id'] == '11950' || self::$data['id'] == '12011' || self::$data['id'] == '12016' || self::$data['id'] == '12018' || self::$data['id'] == '12170'){
            $this->bodyTubectomia($pdf);
        }elseif(self::$data['id'] == '3369' || self::$data['id'] == '3717' || self::$data['id'] == '3887' || self::$data['id'] == '3890'
        || self::$data['id'] == '3896' || self::$data['id'] == '3898' || self::$data['id'] == '3903' || self::$data['id'] == '3905'
        || self::$data['id'] == '3908' || self::$data['id'] == '3910' || self::$data['id'] == '3913' || self::$data['id'] == '3916'
        || self::$data['id'] == '3918' || self::$data['id'] == '3920' || self::$data['id'] == '3924' || self::$data['id'] == '3940'
        || self::$data['id'] == '3943' || self::$data['id'] == '3948' || self::$data['id'] == '3950' || self::$data['id'] == '3956'
        || self::$data['id'] == '3960' || self::$data['id'] == '3963' || self::$data['id'] == '3966' || self::$data['id'] == '3976'
        || self::$data['id'] == '3990' || self::$data['id'] == '3993' || self::$data['id'] == '3995' || self::$data['id'] == '3998'
        || self::$data['id'] == '4013' || self::$data['id'] == '4018' || self::$data['id'] == '4044' || self::$data['id'] == '4045'
        || self::$data['id'] == '4047' || self::$data['id'] == '4048' || self::$data['id'] == '4049' || self::$data['id'] == '4057'
        || self::$data['id'] == '4060' || self::$data['id'] == '4063' || self::$data['id'] == '4065' || self::$data['id'] == '4069'
        || self::$data['id'] == '4071' || self::$data['id'] == '4074' || self::$data['id'] == '4080' || self::$data['id'] == '4102'
        || self::$data['id'] == '4113' || self::$data['id'] == '4127' || self::$data['id'] == '4133' || self::$data['id'] == '4136'
        || self::$data['id'] == '4175' || self::$data['id'] == '4177'){
            $this->bodyCirugiaLamparoscopicaGinecologica($pdf);
        }elseif(
            self::$data['id'] == '3099' || self::$data['id'] == '3573' || self::$data['id'] == '3578'
            || self::$data['id'] == '3579' || self::$data['id'] == '3886' || self::$data['id'] == '3887' || self::$data['id'] == '11726'
            || self::$data['id'] == '11727' || self::$data['id'] == '11873' || self::$data['id'] == '11874' || self::$data['id'] == '11913'
            || self::$data['id'] == '11917' || self::$data['id'] == '11923'){
            $this->bodyCistectomiaViaAbdominal($pdf);
        }elseif(
            self::$data['id'] == '5561' || self::$data['id'] == '5562' || self::$data['id'] == '5563' || self::$data['id'] == '6779'
            || self::$data['id'] == '6802' || self::$data['id'] == '6803' || self::$data['id'] == '6804' || self::$data['id'] == '6809'
            || self::$data['id'] == '6810' || self::$data['id'] == '6811' || self::$data['id'] == '6812' || self::$data['id'] == '6813'
            || self::$data['id'] == '6814' || self::$data['id'] == '6815' || self::$data['id'] == '7292' || self::$data['id'] == '7293'
            || self::$data['id'] == '7294' || self::$data['id'] == '7295' || self::$data['id'] == '7296' || self::$data['id'] == '7297'
            || self::$data['id'] == '7298' || self::$data['id'] == '7299' || self::$data['id'] == '7300' || self::$data['id'] == '7301'
            || self::$data['id'] == '7302' || self::$data['id'] == '7380' || self::$data['id'] == '7381' || self::$data['id'] == '7382'
            || self::$data['id'] == '7383' || self::$data['id'] == '7874' || self::$data['id'] == '7892' || self::$data['id'] == '7893'
            || self::$data['id'] == '7894' || self::$data['id'] == '7993' || self::$data['id'] == '7994' || self::$data['id'] == '7995'
            || self::$data['id'] == '7996' || self::$data['id'] == '12176' || self::$data['id'] == '12177' || self::$data['id'] == '12654' ){
                $this->bodyLavadoDesbridamientoFracturas($pdf);
        }elseif(self::$data['id'] == '7877' || self::$data['id'] == '7878' || self::$data['id'] == '5539'){
                $this->bodyMeniscectomiaArtoscopica($pdf);
        }elseif (self::$data['id'] == '7726' || self::$data['id'] == '7727' || self::$data['id'] == '7728' || self::$data['id'] == '7730'
        || self::$data['id'] == '7738' || self::$data['id'] == '7739' || self::$data['id'] == '7740' || self::$data['id'] == '7741'
        || self::$data['id'] == '7743' || self::$data['id'] == '12449' || self::$data['id'] == '12450') {
            $this->bodyCirugiaAmputacioneMenor($pdf);
        }elseif(self::$data['id'] == '3761' || self::$data['id'] == '3762' || self::$data['id'] == '11957'
        || self::$data['id'] == '11876' || self::$data['id'] == '11880' || self::$data['id'] == '11915' || self::$data['id'] == '11924'
        || self::$data['id'] == '11925' || self::$data['id'] == '11926' || self::$data['id'] == '11927'){
            $this->bodyradicalLamparoscopia($pdf);
        }elseif(self::$data['id'] == '3701' || self::$data['id'] == '3702'  || self::$data['id'] == '3703'  || self::$data['id'] == '11855'
        || self::$data['id'] == '11856'  || self::$data['id'] == '12080' ){
            $this->bodyUreterolitotomia($pdf);
        }elseif(self::$data['id'] == '3835'){
            $this->bodyVasectomia($pdf);
        }elseif(self::$data['id'] == '3754' || self::$data['id'] == '11863'){
            $this->bodyReseccionTransurtralProstata($pdf);
        }elseif(self::$data['id'] == '5090' || self::$data['id'] == '5093' || self::$data['id'] == '5094' || self::$data['id'] == '5098' || self::$data['id'] == '5099'
        || self::$data['id'] == '5100'){
            $this->bodyAmigdalectomiaAdenoidectomia($pdf);
        }elseif(self::$data['id'] == '11932' || self::$data['id'] == '3419' || self::$data['id'] == '3428' || self::$data['id'] == '3437'
        || self::$data['id'] == '11841' || self::$data['id'] == '11900' || self::$data['id'] == '11934' || self::$data['id'] == '11939'
        || self::$data['id'] == '12001' || self::$data['id'] == '12002' || self::$data['id'] == '12065' || self::$data['id'] == '11148'){
            $this->bodyNefrectomiaRadicalAbierta($pdf);
        }elseif (self::$data['id'] == '8488') {
            $this->bodyExtraccionCatterViaEndoscopica($pdf);
        }elseif (self::$data['id'] == '291' || self::$data['id'] == '291' || self::$data['id'] == '3550' || self::$data['id'] == '3551'
        || self::$data['id'] == '3775' || self::$data['id'] == '12166'){
            $this->bodyCistoscoipa($pdf);
        }elseif(self::$data['id'] == '4906' || self::$data['id'] == '4907' || self::$data['id'] == '10872' || self::$data['id'] == '10873' || self::$data['id'] == '10877'
        || self::$data['id'] == '10878'){
            $this->bodyTurbinoplastia($pdf);
        }elseif(self::$data['id'] == '5187' || self::$data['id'] == '5188' || self::$data['id'] == '5185' || self::$data['id'] == '5186'
        || self::$data['id'] == '11410' || self::$data['id'] == '11411'){
            $this->bodyCirugiaSafenoVaricectomia($pdf);
        }elseif(self::$data['id'] == '4910' || self::$data['id'] == '4911' || self::$data['id'] == '4912' || self::$data['id'] == '4913'
        || self::$data['id'] == '4914' || self::$data['id'] == '10880' || self::$data['id'] == '10866' || self::$data['id'] == '10879' || self::$data['id'] == '10880'
        || self::$data['id'] == '11224' || self::$data['id'] == '11238' || self::$data['id'] == '11264'){
            $this->bodySeptoplastia($pdf);
        }elseif(self::$data['id'] == '4947' || self::$data['id'] == '10896'){
            $this->bodyCirugiaEndoscopicaSenosParanasales($pdf);
        }elseif(self::$data['id'] == '2997' || self::$data['id'] == '2998' || self::$data['id'] == '2999' || self::$data['id'] == '3000' || self::$data['id'] == '3001'
        || self::$data['id'] == '3002' || self::$data['id'] == '3003' || self::$data['id'] == '3004' || self::$data['id'] == '3031'
        || self::$data['id'] == '11720' || self::$data['id'] == '11721' || self::$data['id'] == '11722' || self::$data['id'] == '11723'
        || self::$data['id'] == '11830'){
            $this->bodyRealizacionHemorroidectomia($pdf);
        }elseif(self::$data['id'] == '2872' || self::$data['id'] == '2873' || self::$data['id'] == '2874' || self::$data['id'] == '2875'
        ||self::$data['id'] == '11576' ||self::$data['id'] == '11600'){
            $this->bodyCierreEstoma($pdf);
        }elseif(self::$data['id'] == '5540' || self::$data['id'] == '5539' || self::$data['id'] == '5608' || self::$data['id'] == '5609'
        || self::$data['id'] == '7630' || self::$data['id'] == '7631' || self::$data['id'] == '7877' || self::$data['id'] == '7878'
        || self::$data['id'] == '7914' || self::$data['id'] == '7915' || self::$data['id'] == '7917' || self::$data['id'] == '7919'){
            $this->bodyMenisectommiaArtroscopica($pdf);
        }elseif(self::$data['id'] == '2948' || self::$data['id'] == '2949' || self::$data['id'] == '2950' || self::$data['id'] == '11656'
        || self::$data['id'] == '11665'){
            $this->bodyReseccionTumorRectal($pdf);
        }elseif(self::$data['id'] == '3211' || self::$data['id'] == '3212' || self::$data['id'] == '3213' || self::$data['id'] == '3214' || self::$data['id'] == '3215' || self::$data['id'] == '3216'
        || self::$data['id'] == '3217' || self::$data['id'] == '3218' || self::$data['id'] == '3219' || self::$data['id'] == '3220' || self::$data['id'] == '3221' || self::$data['id'] == '3222'
        || self::$data['id'] == '3223' || self::$data['id'] == '3224' || self::$data['id'] == '3225' || self::$data['id'] == '3226' || self::$data['id'] == '3227' || self::$data['id'] == '3228'
        || self::$data['id'] == '3229' || self::$data['id'] == '3230' || self::$data['id'] == '3231' || self::$data['id'] == '3232' || self::$data['id'] == '3233' || self::$data['id'] == '3234'
        || self::$data['id'] == '3239' || self::$data['id'] == '3240' || self::$data['id'] == '3241' || self::$data['id'] == '3242' || self::$data['id'] == '3243' || self::$data['id'] == '3244'
        || self::$data['id'] == '3245' || self::$data['id'] == '3246' || self::$data['id'] == '3247' || self::$data['id'] == '3248' || self::$data['id'] == '3249' || self::$data['id'] == '3250'
        || self::$data['id'] == '3251' || self::$data['id'] == '3252' || self::$data['id'] == '3253' || self::$data['id'] == '3254' || self::$data['id'] == '3255' || self::$data['id'] == '3256'
        || self::$data['id'] == '3257' || self::$data['id'] == '3258' || self::$data['id'] == '3259' || self::$data['id'] == '3260' || self::$data['id'] == '3261' || self::$data['id'] == '3262'
        || self::$data['id'] == '3263' || self::$data['id'] == '3264'){
            $this->bodyHernirrafia($pdf);
        }elseif(self::$data['id'] == '5089' || self::$data['id'] == '10985'){
            $this->bodyAmigdalectomia($pdf);
        }elseif(self::$data['id'] == '2905' || self::$data['id'] == '2906' || self::$data['id'] == '2914' || self::$data['id'] == '3041'
        || self::$data['id'] == '3368' || self::$data['id'] == '3882' || self::$data['id'] == '3897' || self::$data['id'] == '3902'
        || self::$data['id'] == '3904' || self::$data['id'] == '3907' || self::$data['id'] == '3912' || self::$data['id'] == '3917'
        || self::$data['id'] == '3919' || self::$data['id'] == '3925' || self::$data['id'] == '3927' || self::$data['id'] == '3929'
        || self::$data['id'] == '6045' || self::$data['id'] == '9707' || self::$data['id'] == '11515' || self::$data['id'] == '11585'
        || self::$data['id'] == '11726' || self::$data['id'] == '11773' || self::$data['id'] == '11816' || self::$data['id'] == '11821'
        || self::$data['id'] == '11836' || self::$data['id'] == '11837' || self::$data['id'] == '12144' || self::$data['id'] == '12160'
        || self::$data['id'] == '3939' || self::$data['id'] == '3942' || self::$data['id'] == '3949' || self::$data['id'] == '3951'
        || self::$data['id'] == '3953' || self::$data['id'] == '3957' || self::$data['id'] == '3959' || self::$data['id'] == '4011'
        || self::$data['id'] == '4016' || self::$data['id'] == '4039' || self::$data['id'] == '4042' || self::$data['id'] == '4043'
        || self::$data['id'] == '4050' || self::$data['id'] == '4052' || self::$data['id'] == '4125' || self::$data['id'] == '12099'
        || self::$data['id'] == '12100' || self::$data['id'] == '12135' || self::$data['id'] == '12139' || self::$data['id'] == '12155'){
            $this->bodyLampartomiaExploratoria($pdf);
        }elseif(self::$data['id'] =='2846' || self::$data['id'] =='2847' || self::$data['id'] =='2848' || self::$data['id'] =='2849'
        || self::$data['id'] =='2850' || self::$data['id'] =='2851' || self::$data['id'] =='2834' || self::$data['id'] =='11617'
        || self::$data['id'] =='11634' || self::$data['id'] =='11637' || self::$data['id'] =='11652' || self::$data['id'] =='11660'
        || self::$data['id'] =='11661' || self::$data['id'] =='11662'){
            $this->bodyColectomiaTotalReservorio($pdf);
        }elseif(self::$data['id'] =='3108' || self::$data['id'] =='3110' || self::$data['id'] =='3111' || self::$data['id'] =='3118' || self::$data['id'] =='3121' || self::$data['id'] =='3135'
        || self::$data['id'] =='3144' || self::$data['id'] =='3145' || self::$data['id'] =='3146' || self::$data['id'] =='3148' || self::$data['id'] =='3151' || self::$data['id'] =='3153'
        || self::$data['id'] =='3156' || self::$data['id'] =='3143' || self::$data['id'] =='11712' || self::$data['id'] =='11713' || self::$data['id'] =='11714'
        || self::$data['id'] =='11715' || self::$data['id'] =='11727' || self::$data['id'] =='11748' || self::$data['id'] =='11749' || self::$data['id'] =='11750'
        || self::$data['id'] =='11751' || self::$data['id'] =='11759' || self::$data['id'] =='11775' || self::$data['id'] =='11776' || self::$data['id'] =='11777'
        || self::$data['id'] =='11796' || self::$data['id'] =='11804' || self::$data['id'] =='11805' || self::$data['id'] =='11833' || self::$data['id'] =='11869'
        || self::$data['id'] =='11870' || self::$data['id'] =='11947' || self::$data['id'] =='12244' || self::$data['id'] =='12246'){
            $this->bodyCirugiaViaBiliar($pdf);
        }elseif(self::$data['id'] =='3080' || self::$data['id'] =='3082' || self::$data['id'] =='3100'){
            $this->bodyColecistectomiaLaparoscopica($pdf);
        }elseif(self::$data['id'] =='9123' || self::$data['id'] =='9732'){
            $this->bodySondajeLagrimal($pdf);
        }elseif(self::$data['id'] =='9718' || self::$data['id'] =='10324' || self::$data['id'] =='10329'){
            $this->bodyDescompresionTunelCarpiano($pdf);
        }elseif(self::$data['id'] =='5592' || self::$data['id'] =='5593' || self::$data['id'] =='5594' || self::$data['id'] =='5610'
        || self::$data['id'] =='7631' || self::$data['id'] =='7907'
        || self::$data['id'] =='7908' || self::$data['id'] =='9746'
        || self::$data['id'] =='9747' || self::$data['id'] =='9748' || self::$data['id'] =='9749'){
            $this->bodyLigamentoCruzadoAnteriorExploracion($pdf);
        }elseif(self::$data['id'] =='3892' || self::$data['id'] =='3893' || self::$data['id'] =='3894'){
            $this->bodyExtirpacionTumoresOvariosParaovaricos($pdf);
        }


        $pdf->Output();
    }

    public function footer()
        {

            if($this->page == 2){

                $inicio = $this->GetY();

                $this->Line(23,$inicio+3,190,$inicio+3);
                $this->Line(23,$inicio+9,190,$inicio+9);
                $this->Line(23,$inicio+36,190,$inicio+36);
                $this->Line(23,$inicio+3,23,$inicio+36);
                $this->Line(122,$inicio+9,122,$inicio+36);
                $this->Line(190,$inicio+3,190,$inicio+36);
                $this->Line(60,$inicio+9,60,$inicio+36);

                $this->Ln();
                $y1=$this->GetY();
                if(self::$medicoAtendio->aceptacion_consentimiento == 'Si'){

                    $this->SetTextColor(10,10,10);
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(23,$y1+3);
                    $this->Cell(0,0,'Fecha y hora del consentimiento:' .self::$fechaHoy,0,0,'L',0);

                    $this->Ln();
                    $y2=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetFillColor(129, 214, 54);
                    $this->SetXY(23,$y2+2);
                    $this->Cell(37,5,utf8_decode( 'Foto de Identificación'),1,1,'C','true');

                    $y3=$this->GetY();
                    $this->SetXY(34,$y3+10);
                    $this->SetTextColor(205,205,205);
                    $this->Image(storage_path(substr(self::$medicoAtendio->ruta_foto,9)), 25,$y3+5, 32, 13);

                    $y4=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(60,$y4+2);
                    $this->SetFillColor(129, 214, 54);
                    $this->Cell(62,6,'Firma del paciente o representante legal',1,1,'C','true');

                    $y5=$this->GetY();
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(60,$y5);
                    if(self::$medicoAtendio->numero_documento_representante == null){
                        $this->Cell(0,4,utf8_decode('Identificación:'. self::$paciente->Num_Doc),0,0,'L',0);
                    }else{
                        $this->Cell(0,4,utf8_decode('Identificación:'. self::$medicoAtendio->numero_documento_representante),0,0,'L',0);
                    }
                    $y10 = $this->GetY();

                    $this->SetFont('Arial','B',8);
                    $this->SetXY(122,$y4+2);
                    $this->SetFillColor(129, 214, 54);
                    $this->Cell(68,6,utf8_decode('Firma del médico'),1,1,'C','true');

                    $y7=$this->GetY();
                    $this->SetXY(122,$y7+2);
                    $this->Cell(50,0,utf8_decode('Nombre: '.self::$medicoAtendio->name. " ".self::$medicoAtendio->apellido),0,'C');

                    $y8=$this->GetY();
                    $this->SetXY(23,$y2+12);

                    if(self::$medicoAtendio->firma_paciente){
                        $this->Image(storage_path(substr(self::$medicoAtendio->firma_paciente,9)), 70,$y4-12, 46, 11);
                    }

                    $y9=$this->GetY();
                    $this->SetTextColor(205,205,205);
                    $this->SetXY(145,$y9+1);
                    if(self::$medicoAtendio->Firma){
                        if (file_exists(storage_path(substr(self::$medicoAtendio->Firma, 9)))) {
                            $this->Image(storage_path(substr(self::$medicoAtendio->Firma, 9)), 130, $y4-12, 56, 11);
                        }
                    }

                    $sm = max($y1,$y2,$y3,$y4,$y5,$y7,$y8,$y9,$y10);
                    $yt=$sm;

                    $this->SetTextColor(10,10,10);
                    $this->SetXY(80,$y10+7);
                    $this->SetFont('Arial','',8);
                    $this->Cell(50,0,utf8_decode('* La huella del paciente se toma únicamente para las personas que no saben firmar o que presentan perdida de la visión.'),0,0,'C',0);
                }else{
                    $this->SetTextColor(10,10,10);
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(23,$y1+3);
                    $this->Cell(0,0,'Fecha y hora del consentimiento:',0,0,'L',0);

                    $this->Ln();
                    $y2=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetFillColor(129, 214, 54);
                    $this->SetXY(23,$y2+2);
                    $this->Cell(37,5,utf8_decode('Foto de Identificación'),1,1,'C','true');

                    $y3=$this->GetY();
                    $this->SetXY(37,$y3+10);
                    $this->SetTextColor(205,205,205);
                    $this->Cell(0,0,utf8_decode('FOTO'),0,0,'L',0);

                    $y4=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(60,$y4+2);
                    $this->SetFillColor(129, 214, 54);
                    $this->Cell(62,6,'Firma del paciente o representante legal',1,1,'C','true');

                    $y5=$this->GetY();
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(60,$y5);
                    $this->Cell(0,4,utf8_decode('Identificación:'),0,0,'L',0);

                    $y10 = $this->GetY();

                    $this->SetFont('Arial','B',8);
                    $this->SetXY(122,$y4+2);
                    $this->SetFillColor(129, 214, 54);
                    $this->Cell(68,6,utf8_decode('Firma del médico'),1,1,'C','true');

                    $y7=$this->GetY();
                    $this->SetXY(122,$y7+2);
                    $this->Cell(50,0,utf8_decode('Nombre:'),0,'C');

                    $y8=$this->GetY();
                    $this->SetXY(80,$y8-12);
                    $this->SetTextColor(205,205,205);
                    $this->Cell(0,0,utf8_decode('FIRME AQUÍ'),0,0,'L',0);

                    $y9=$this->GetY();
                    $this->SetTextColor(205,205,205);
                    $this->SetXY(145,$y9);
                    $this->Cell(0,0,utf8_decode('FIRME MEDICO'),0,0,'L',0);

                    $sm = max($y1,$y2,$y3,$y4,$y5,$y7,$y8,$y9,$y10);
                    $yt=$sm;

                    $this->SetTextColor(10,10,10);
                    $this->SetXY(80,$y10+7);
                    $this->SetFont('Arial','',8);
                    $this->Cell(50,0,utf8_decode('* La huella del paciente se toma únicamente para las personas que no saben firmar o que presentan perdida de la visión.'),0,0,'C',0);

                }

                $this->SetXY(70,$yt+20);
                $this->SetFont('Arial','B',9);
                $this->Cell(186,4,utf8_decode('DISENTIMIENTO O DESISTIMIENTO INFORMADO'),0,'C');

                $this->Ln();
                $this->SetX(13);
                $this->SetFont('Arial','',8);
                $this->MultiCell(186,4, utf8_decode('En el presente documento manifiesto que he sido informado sobre mi condición, las eventuales complicaciones y/o riesgos que se deriven, los beneficios de los procedimientos planeados, de manera que se puedan tomar decisiones informadas; no obstante, reunido con el profesional _____________________________________________________, decido de forma libre y consciente no aceptar/revocar la realización del procedimiento propuesto, haciéndome responsable de las consecuencias que puedan derivarse de esta decisión. '),0,'J');
                $inicio=$this->GetY();

                $this->Line(23,$inicio+3,190,$inicio+3);
                $this->Line(23,$inicio+9,190,$inicio+9);
                $this->Line(23,$inicio+36,190,$inicio+36);
                $this->Line(23,$inicio+3,23,$inicio+36);
                $this->Line(122,$inicio+9,122,$inicio+36);
                $this->Line(190,$inicio+3,190,$inicio+36);
                $this->Line(60,$inicio+9,60,$inicio+36);

                if(self::$medicoAtendio->aceptacion_consentimiento == 'No'){
                    $y1=$this->GetY();
                $this->SetTextColor(10,10,10);
                $this->SetFont('Arial','B',9);
                $this->SetXY(23,$y1+7);
                $this->Cell(0,0,'Fecha y hora del disentimiento o desistimiento informado:',0,0,'L',0);


                $y2=$this->GetY();
                $this->SetFillColor(129, 214, 54);
                $this->SetXY(23,$y2+2);
                $this->Cell(37,5,utf8_decode('Foto de Identificación'),1,1,'C','true');


                $y3=$this->GetY();
                $this->SetXY(35,$y3+12);
                $this->SetTextColor(205,205,205);
                $this->Image(storage_path(substr(self::$medicoAtendio->ruta_foto,9)), 25,$y3+5, 32, 13);


                $y4=$this->GetY();
                $this->SetXY(80,$y4-7);
                if(self::$medicoAtendio->firma_paciente){
                    $this->Image(storage_path(substr(self::$medicoAtendio->firma_paciente,9)), 68,$y4-15, 46, 11);
                }
                $this->SetXY(145,$y4-7);
                if(self::$medicoAtendio->Firma){
                    if (file_exists(storage_path(substr(self::$medicoAtendio->Firma, 9)))) {
                        $this->Image(storage_path(substr(self::$medicoAtendio->Firma, 9)), 130, $y4-15, 56, 11);
                    }
                }
                $y5=$this->GetY();
                $this->SetTextColor(10,10,10);
                $this->SetXY(60,$y5+4);
                $this->Cell(62,6,utf8_decode('Firma del paciente o representante legal'),1,1,'C','true');
                $this->SetXY(122,$y5+4);
                $this->SetFont('Arial','B',8);
                $this->SetFillColor(129, 214, 54);
                $this->Cell(68,6,utf8_decode('Firma del médico'),1,1,'C','true');

                $y6=$this->GetY();
                $this->SetFont('Arial','B',8);
                $this->SetXY(122,$y6+2);
                $this->Cell(50,0,utf8_decode('Nombre: '.self::$medicoAtendio->name. " ".self::$medicoAtendio->apellido),0,'C');

                $y7=$this->GetY();
                $this->SetFont('Arial','B',9);
                $this->SetXY(60,$y6+2);
                if(self::$medicoAtendio->numero_documento_representante == null){
                    $this->Cell(0,4,utf8_decode('Identificación:'. self::$paciente->Num_Doc),0,0,'L',0);
                }else{
                    $this->Cell(0,4,utf8_decode('Identificación:'. self::$medicoAtendio->numero_documento_representante),0,0,'L',0);
                }


                $y8=$this->GetY();
                $this->SetXY(30,$y8+7);
                $this->SetFont('Arial','',8);
                $this->Cell(50,4,utf8_decode('* La huella del paciente se toma únicamente para las personas que no saben firmar o que presentan perdida de la visión.'),0,0,'L',0);
                $this->Ln();
                $this->Ln();
                }else{
                    $y1=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(23,$y1+7);
                    $this->Cell(0,0,'Fecha y hora del disentimiento o desistimiento informado:',0,0,'L',0);


                    $y2=$this->GetY();
                    $this->SetFillColor(129, 214, 54);
                    $this->SetXY(23,$y2+2);
                    $this->Cell(37,5,utf8_decode(  'Huella del Paciente*'),1,1,'C','true');


                    $y3=$this->GetY();
                    $this->SetXY(35,$y3+12);
                    $this->SetTextColor(205,205,205);
                    $this->Cell(0,0,'HUELLA',0,0,'L',0);

                    $y4=$this->GetY();
                    $this->SetXY(80,$y4-7);
                    $this->Cell(0,0,utf8_decode('FIRME AQUÍ'),0,0,'L',0);
                    $this->SetXY(145,$y4-7);
                    $this->Cell(0,0,utf8_decode('FIRME AQUÍ'),0,0,'L',0);

                    $y5=$this->GetY();
                    $this->SetTextColor(10,10,10);
                    $this->SetXY(60,$y5+4);
                    $this->Cell(62,6,utf8_decode('Firma del paciente o representante legal'),1,1,'C','true');
                    $this->SetXY(122,$y5+4);
                    $this->SetFont('Arial','B',8);
                    $this->SetFillColor(129, 214, 54);
                    $this->Cell(68,6,utf8_decode('Firma del médico'),1,1,'C','true');

                    $y6=$this->GetY();
                    $this->SetFont('Arial','B',8);
                    $this->SetXY(122,$y6+2);
                    $this->Cell(50,0,utf8_decode('Nombre: '),0,'C');

                    $y7=$this->GetY();
                    $this->SetFont('Arial','B',9);
                    $this->SetXY(60,$y6+2);
                    $this->Cell(0,0,utf8_decode('Identificación:'),0,0,'',0);


                    $y8=$this->GetY();
                    $this->SetXY(30,$y8+7);
                    $this->SetFont('Arial','',8);
                    $this->Cell(50,4,utf8_decode('* La huella del paciente se toma únicamente para las personas que no saben firmar o que presentan perdida de la visión.'),0,0,'L',0);
                    $this->Ln();
                    $this->Ln();
                }
            }

            $this->SetXY(190,287);
            $this->SetFont('Arial', '', 7);
            $this->Cell(10,4,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
            $this->Ln();
            $this->Cell(186, 4, utf8_decode('Una vez descargado o impreso este documento se considera copia no controlada'),0,0,'C');
        }

    public function bodyLavado_oidos($pdf)
    {
        $pdf->SetFont('Arial','B',11);//Tipo de letra,tamaño y negrilla del titulo

        $pdf->Line(20,5,190,5);// linea superior del marco del logo
        $pdf->Line(20,35,190,35); // linea inferior del marco del logo
        $pdf->Line(20,5,20,35); // linea izquierda vertical  del marco del logo
        $pdf->Line(190,5,190,35); // linea derecha verticaldel  marco del  logo

        $logo = public_path() . "/images/logo.png"; // logo
        $pdf->Image($logo, 22, 7, -220); //posicion en el eje X,Y y Tamaño
        $pdf->SetXY(73,15);
        $pdf->Cell(68,5,'CONSENTIMIENTO INFORMADO',0,0,'C',0);

        switch (self::$data['id']) {
            case '12789':
                $pdf->setX(106);
                $pdf->Cell(1,15,'PROCEDIMIENTO LAVADO DE MANOS',0,0,'C',0);
                break;
            default:
                $pdf->setX(106);
                $pdf->Cell(1,15,'PROCEDIMIENTO LAVADO DE OIDOS',0,0,'C',0);
                break;
        }


        $pdf->Line(67,5,67,35);// linea vertical interior derecha del logo
        $pdf->Line(147,5,147,35);// linea vertical interior derecha del logo
        $pdf->Line(147,25,190,25); // Linea interior, horizontal  superior del logo
        $pdf->Line(147,16,190,16); // Linea interior, horizontal  inferior del logo


        $pdf->SetXY(112,13); // posicion del titulo codigo del documento
        $pdf->Cell(0,0,utf8_decode('Código:'),0,0,'C',0);// Titulo codigo del documento

        $pdf->SetX(149);// posicion del codigo del documeto
        $pdf->SetFont('Arial','',11); // tamaño, tipo de letra del codigo del documeto
        $pdf->Cell(0,0,'FO-CE-037',0,0,'C',0); // codigo del documento

        $pdf->SetFont('Arial','B',11); // tamaño, tipo de letra del version del documeto
        $pdf->SetXY(113,22); // posicion del version del documeto
        $pdf->Cell(0,0,utf8_decode('Versión:'),0,0,'C',0); // version del documento

        $pdf->SetFont('Arial','',11); // tamaño, tipo de letra de versiones del documeto
        $pdf->SetXY(134,22); // posicion de numero de  versiones del documeto
        $pdf->Cell(0,0,'03',0,0,'C',0);// numero de versiones del documento

        $pdf->SetFont('Arial','B',11);// tamaño, tipo de letra de titulo de fecha de aprobacion del documeto
        $pdf->SetXY(136,28); //posicion de titulo fecha de aprobacion
        $pdf->Cell(0,0,utf8_decode('Fecha de aprobación:'),0,0,'C',0); //titulo fecha de aprobacion
        $pdf->SetFont('Arial','',11);// tamaño, tipo de letra del fecha de aprobacion del documeto
        $pdf->SetX(115); // posicion de la fecha de aprobacion
        $pdf->Cell(0,10,'05/07/2022',0,0,'C',0);// fecha de aprobacion del documento


        $pdf->SetFont('Arial','B',9);// tamaño, tipo de letra de titulo de fecha y hora de diligenciamiento del documeto
        $pdf->SetXY(12,45);  // posicion de la fecha y hora de diligenciamiento
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0); // fecha y obtenidad desde la data

        $pdf->SetTextColor(10,10,10);// color del texto titulo asegurador
        $pdf->SetFont('Arial','B',9); // tamaño, tipo de la letra para titulo asegurador
        $pdf->SetX(90);// posicion del texto asegurador
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0); // Asewgurador aobtenidso desde la data
        $inicio = $pdf->GetY();

        $pdf->Line(10,50,200,50);
        $pdf->Line(10,65,200,65);
        $pdf->Line(10,50,10,65);
        $pdf->Line(200,50,200,65);
        $pdf->Line(25,50,25,65);
        $pdf->Ln();
        $pdf->SetXY(10,55);
        $pdf->Cell(40,4,'Paciente',0,0,'L',0);




        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,4,utf8_decode(self::$paciente->Primer_Ape),0,'C');

        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,4,utf8_decode(self::$paciente->Segundo_Ape),0,'C');


        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,4,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');


        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,4,utf8_decode(self::$paciente->Num_Doc),0,'C');


        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,4,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $conteo = max($y1,$y2,$y3,$y4,$y5);
        $pdf->Line(25,$conteo,200,$conteo);
        $pdf->Line(50,$inicio +5,50,$conteo +6);
        $pdf->Line(80,$inicio+5,80,$conteo +6);
        $pdf->Line(148,$inicio+5,148,$conteo +6);
        $pdf->Line(180,$inicio+5,180,$conteo +6);
        // $y=$conteo;

        $y=$pdf->GetY();
        $y10=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->Cell(25,4,'Primer apellido',0,0,'C',0);
        $y11=$pdf->GetY();
        $pdf->SetX(50);
        $pdf->Cell(30,4,'Segundo apellido',0,0,'C',0);
        $y12=$pdf->GetY();
        $pdf->SetX(80);
        $pdf->Cell(68,4,'Nombre completo',0,0,'C',0);
        $y13=$pdf->GetY();
        $pdf->SetX(148);
        $pdf->Cell(32,4,'Documento',0,0,'C',0);
        $y14=$pdf->GetY();
        $pdf->SetX(180);
        $pdf->Cell(20,4,'Edad',0,0,'C',0);
        $max= max($y10,$y11,$y12,$y13,$y14);
        $yt=$max;
        $y=$pdf->GetY();

        $pdf->SetXY(13,$y+8);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('DESCRIPCIÓN Y BENEFICIOS DEL PROCEDIMIENTO:'),0,0,'J',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4, utf8_decode('El lavado de oídos es el procedimiento que permite extraer el cerumen impactado en el conducto auditivo externo. Se requiere previamente la aplicación de sustancias que ablanden el tapón de cerumen (aplicadas durante 3 a 7 días previo al procedimiento). Es un procedimiento sencillo y fácil de realizar, de bajo costo, bajas complicaciones y de tipo ambulatorio; o sea puede regresar a casa el mismo día. Su finalidad es la descongestión del canal auditivo interno, mejoría de la audición y evitar cuadros infecciosos'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetX(13);
        $pdf->Cell(186,4,utf8_decode('RIESGOS DEL PROCEDIMIENTO:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4, utf8_decode('Como todo procedimiento y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, vértigos, trastornos del equilibrio, acufenos (ruidos en el oído) hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), sangrado o hemorragias, reacciones alérgicas, irritación frénica, heridas involuntarias en vasos sanguíneos.  El procedimiento de extracción es una técnica “a ciegas”, al no poder visualizarse a causa del tapón el tímpano y el oído medio. Las complicaciones son poco frecuentes, entre ellas, se puede presentar perforación timpánica. Existen otros riesgos como: ___________________________________________________________________________________________________________________  ____________________________________________________________________________________________________________________'),0,'J');

        $pdf->Ln();
        $pdf->setX(13);
        $pdf->MultiCell(186,4, utf8_decode(' En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');


        $y=$pdf->GetY();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetXY(13,$y+8);
        $pdf->Cell(186,4,'ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetX(13);
        $pdf->Cell(186,4,'RIESGO DE NO ACEPTAR EL PROCEDIMIENTO',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('En caso de no realizarse el procedimiento o intervención se pueden presentar hipoacusia, otitis externas o internas, o probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(186,4,utf8_decode(' En caso de requerir más información al respecto de su tratamiento se puede dirigir al personal médico tratante y de enfermería presente.'),0,'J');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('RECOMENDACIONES'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud. El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');


        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal'),0,'J');


        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('a)       Declaro que he entendido las condiciones y objetivos del procedimiento que se me va a practicar, los cuidados que debo tener antes y después, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo y acepto el alcance y los riesgos que conlleva el procedimiento que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('b)      El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con finesexclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X). '),0,'J');


        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('c)	    Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->MultiCell(186,4,utf8_decode('Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodycistouretropexia($pdf)
    {
        $pdf->SetFont('Arial','B',11);//Tipo de letra,tamaño y negrilla del titulo

        $pdf->Line(20,5,190,5);// linea superior del marco del logo
        $pdf->Line(20,35,190,35); // linea inferior del marco del logo
        $pdf->Line(20,5,20,35); // linea izquierda vertical  del marco del logo
        $pdf->Line(190,5,190,35); // linea derecha verticaldel  marco del  logo

        $logo = public_path() . "/images/logo.png"; // logo
        $pdf->Image($logo, 22, 7, -220); //posicion en el eje X,Y y Tamaño
        $pdf->SetXY(73,15);
        $pdf->Cell(68,5,'CONSENTIMIENTO INFORMADO',0,0,'C',0);
        $pdf->ln();
        $pdf->setX(87);
        $pdf->MultiCell(60,4,'CISTOURETROPEXIA',0,'J',0);




        $pdf->Line(67,5,67,35);// linea vertical interior derecha del logo
        $pdf->Line(147,5,147,35);// linea vertical interior derecha del logo
        $pdf->Line(147,25,190,25); // Linea interior, horizontal  superior del logo
        $pdf->Line(147,16,190,16); // Linea interior, horizontal  inferior del logo


        $pdf->SetXY(112,13); // posicion del titulo codigo del documento
        $pdf->Cell(0,0,utf8_decode('Código:'),0,0,'C',0);// Titulo codigo del documento

        $pdf->SetX(149);// posicion del codigo del documeto
        $pdf->SetFont('Arial','',11); // tamaño, tipo de letra del codigo del documeto
        $pdf->Cell(0,0,'FO-AI-027',0,0,'C',0); // codigo del documento

        $pdf->SetFont('Arial','B',11); // tamaño, tipo de letra del version del documeto
        $pdf->SetXY(113,22); // posicion del version del documeto
        $pdf->Cell(0,0,utf8_decode('Versión:'),0,0,'C',0); // version del documento

        $pdf->SetFont('Arial','',11); // tamaño, tipo de letra de versiones del documeto
        $pdf->SetXY(134,22); // posicion de numero de  versiones del documeto
        $pdf->Cell(0,0,'03',0,0,'C',0);// numero de versiones del documento

        $pdf->SetFont('Arial','B',11);// tamaño, tipo de letra de titulo de fecha de aprobacion del documeto
        $pdf->SetXY(136,28); //posicion de titulo fecha de aprobacion
        $pdf->Cell(0,0,utf8_decode('Fecha de aprobación:'),0,0,'C',0); //titulo fecha de aprobacion
        $pdf->SetFont('Arial','',11);// tamaño, tipo de letra del fecha de aprobacion del documeto
        $pdf->SetX(115); // posicion de la fecha de aprobacion
        $pdf->Cell(0,10,'05/07/2022',0,0,'C',0);// fecha de aprobacion del documento


        $pdf->SetFont('Arial','B',9);// tamaño, tipo de letra de titulo de fecha y hora de diligenciamiento del documeto
        $pdf->SetXY(12,45);  // posicion de la fecha y hora de diligenciamiento
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0); // fecha y obtenidad desde la data

        $pdf->SetTextColor(10,10,10);// color del texto titulo asegurador
        $pdf->SetFont('Arial','B',9); // tamaño, tipo de la letra para titulo asegurador
        $pdf->SetX(90);// posicion del texto asegurador
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0); // Asewgurador aobtenidso desde la data
        $inicio = $pdf->GetY();

        $pdf->Line(10,50,200,50);
        $pdf->Line(10,65,200,65);
        $pdf->Line(10,50,10,65);
        $pdf->Line(200,50,200,65);
        $pdf->Line(25,50,25,65);
        $pdf->Ln();
        $pdf->SetXY(10,55);
        $pdf->Cell(40,4,'Paciente',0,0,'L',0);

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,4,utf8_decode(self::$paciente->Primer_Ape),0,'C');

        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,4,utf8_decode(self::$paciente->Segundo_Ape),0,'C');


        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,4,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');


        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,4,utf8_decode(self::$paciente->Num_Doc),0,'C');


        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,4,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $conteo = max($y1,$y2,$y3,$y4,$y5);
        $pdf->Line(25,$conteo,200,$conteo);
        $pdf->Line(50,$inicio +5,50,$conteo +6);
        $pdf->Line(80,$inicio+5,80,$conteo +6);
        $pdf->Line(148,$inicio+5,148,$conteo +6);
        $pdf->Line(180,$inicio+5,180,$conteo +6);
        // $y=$conteo;

        $y=$pdf->GetY();
        $y10=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->Cell(25,4,'Primer apellido',0,0,'C',0);
        $y11=$pdf->GetY();
        $pdf->SetX(50);
        $pdf->Cell(30,4,'Segundo apellido',0,0,'C',0);
        $y12=$pdf->GetY();
        $pdf->SetX(80);
        $pdf->Cell(68,4,'Nombre completo',0,0,'C',0);
        $y13=$pdf->GetY();
        $pdf->SetX(148);
        $pdf->Cell(32,4,'Documento',0,0,'C',0);
        $y14=$pdf->GetY();
        $pdf->SetX(180);
        $pdf->Cell(20,4,'Edad',0,0,'C',0);
        $max= max($y10,$y11,$y12,$y13,$y14);
        $yt=$max;


        $pdf->SetY(66);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Ln();
        $pdf->SetX(12);

        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'), 0, 0, 'J', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es un procedimiento quirúrgico en donde se hace corrección de la anatomía uretrovesical, que causan incontinencia urinaria de esfuerzo. La mayoría de los procedimientos quirúrgicos para tratar la incontinencia de esfuerzo se dividen en dos categorías principales: procedimientos con cabestrillo y procedimientos de suspensión del cuello de la vejiga. En el procedimiento de cabestrillo, el cirujano usa cintas de malla sintética debajo del tubo que lleva la orina desde la vejiga (uretra) o el área de músculo engrosado donde la vejiga se conecta con la uretra (cuello de la vejiga).'),'J');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Evitar la incontinencia urinaria de esfuerzo y mejorar la calidad de vida.'),'J');
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección del caso, así como de la vía por la cual se realiza el procedimiento, se pueden presentar efectos indeseables, tanto derivados del procedimiento quirúrgico mismo, como de complicaciones de otros órganos o sistemas del organismo, como los debidos a condiciones propias de cada paciente, por ejemplo. (Diabetes, hipertensión arterial, enfermedad vascular arterial, obesidad o complicaciones venosas).'),'J');
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hematomas, hemorragia o sangrado.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infecciones urinarias o vaginales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dispareunia.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor pélvico crónico.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Traumatismos vasculares importantes.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Neuro praxias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión de estructuras cercanas que amerite procedimientos adicionales para su corrección.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Retención urinaria, que amerite paso de sonda o reintervenciones.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Continuar con incontinencia.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Perforación de la vejiga.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Formación de cálculos vesicales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Erosión o extrusión de la uretra o de la vagina con la malla.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reacción a los componentes de la malla.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infecciones o sepsis generalizada, incluso la muerte.'));
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),'J');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetX(13);
        $pdf->Cell(186,4,'ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $inicio=$pdf->GetY();
        if($inicio > 220){
            $pdf->AddPage();
            $inicio=10;
        }
        $pdf->Ln();
        $pdf->SetFont('Arial','B',9);
        $pdf->SetX(13);
        $pdf->Cell(186,4,'RIESGO DE NO ACEPTAR EL PROCEDIMIENTO',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(186,4,utf8_decode(' En caso de requerir más información al respecto de su tratamiento se puede dirigir al personal médico tratante y de enfermería presente.'),0,'J');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('RECOMENDACIONES'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud. El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(186,4,utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal'),0,'J');


        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('a)    Declaro que he entendido las condiciones y objetivos del procedimiento que se me va a practicar, los cuidados que debo tener antes y después, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo y acepto el alcance y los riesgos que conlleva el procedimiento que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->MultiCell(186,4, utf8_decode('b)   El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con finesexclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X). '),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(186,4,utf8_decode('c)	  Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->MultiCell(186,4,utf8_decode('Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');
    }

    public function bodyinfiltraciones($pdf)
    {
            $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,15);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->setX(74);
            $pdf->Cell(186, 4, utf8_decode('INFILTRACIONES SUBCUTANEAS '), 0, 0, 'L', 0);

            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-CE-041', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetX(90);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);
            $inicio =$pdf->GetY();

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+7);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Una infiltración subcutánea es una técnica poco invasiva que sirve para mitigar o eliminar el dolor crónico en una zona concreta, consiste en administrar una sustancia o fármaco directamente en el lugar afectado, de forma que el medicamento consigue un efecto más rápido y duradero.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Las infiltraciones permiten que el medicamento vaya directo al tejido que se quiere tratar, con un resultado más rápido y duradero, evitando los efectos secundarios sistémicos de los medicamentos que se administran por vía oral o intramuscular.

Las infiltraciones se pueden realizar en todas las articulaciones; en aquellos procesos que tienen dolor puntual, y no zonal. Se utilizan sobre todo en las grandes articulaciones como rodillas y hombros, siendo además estas zonas las menos dolorosas, dado que al ser cavidades mayores la introducción del líquido se tolera mejor.'),0,'J');

$pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('Como en un procedimiento poco invasivo y por causas independientes del actuar médico se pueden presentar algunos efectos secundarios no deseados como: dolor transitorio, durante unos días, en el punto de inyección, infecciones o hemorragias; y a pesar de aliviar el dolor y disminuir la hinchazón, las infiltraciones no curan. El efecto de las infiltraciones dura de tres semanas a un mes y medio y si no se corrige la causa que produce el dolor, las molestias pueden volver. En ese caso, es posible repetir la infiltración hasta tres veces al año.

En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que lleguen a presentarse.'),0,'J');
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ________________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, además comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X

c)	Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodysudermico($pdf)
    {
        $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,12);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(76);
            $pdf->Cell(186, 4, utf8_decode('PARA LA INSERCIÓN O RETIRO '), 0, 0, 'L', 0);
            $pdf->SetXY(78,20);
            $pdf->Cell(186, 4, utf8_decode('DE IMPLANTE SUBDÉRMICO'), 0, 0, 'L', 0);


            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-CE-036', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(70);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);
            $pdf->Text(160, 45, utf8_decode("Inserción"));
            $pdf->Text(180, 45, utf8_decode("Retiro"));
            $pdf->SetXY(174, 43);
            $pdf->cell(3, 3, "",1,1,'C');//check de insercion
            $pdf->SetXY(190, 43);
            $pdf->cell(3, 3, "",1,1,'C');//check de retiro

            $inicio =$pdf->GetY();

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+9);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('El implante anticonceptivo subdérmico, como su nombre indica, es un método anticonceptivo que se aplica debajo de la piel. Consiste en una varilla flexible que se coloca en la cara interna del brazo. Su mecanismo de acción se basa en liberar progestágenos, es decir, hormonas. La liberación tiene lugar de forma continua, impidiendo la ovulación entre otros efectos. Este método anticonceptivo está diseñado para ser utilizado de forma prolongada, sin necesidad de cambiarlo en 3 ó 5 años según el tipo de implante utilizado. Durante todo este tiempo sigue ofreciendo una protección del 99% frente al embarazo. Una vez retirado el implante, el efecto anticonceptivo desaparece. Por tanto, los ciclos ovulatorios se reanudan de forma normal.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Permitir que el paciente tenga una decisión libre, responsable y autónoma sobre su salud reproductiva. '),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención y por causas independientes del actuar profesional se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios. Al igual que con todos los métodos anticonceptivos, se han descrito algunos efectos secundarios a las hormonas. Entre los que pueden aparecer: acné, dolor de cabeza, irregularidad en el período menstrual y tensión mamaria. Entre los efectos secundarios muy poco habituales cabe mencionar: caída de cabello, cambios de humor, cambios de libido, dolor abdominal, cesación del sangrado menstrual (amenorrea).
En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Existen alternativas dentro de los métodos de planificación variados como son:'),0,'J');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos orales.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de barrera (condón y diafragma).'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de Aplicación Intramuscular mensuales o trimestrales.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos intrauterinos.'));
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Cada uno de estos métodos con sus posibles beneficios y complicaciones de acuerdo a la edad del paciente y las comorbilidades, para lo cual se deben tener en cuenta los criterios de elegibilidad.'),0,'J');

            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de no realizarse el procedimiento o intervención se pueden presentar embarazos no deseados ni planeados y en usuarios con patologías de comorbilidad de base como Hipertensión, Diabetes, patologías cardiacas, hereditarias complicaciones graves en el estado de salud del usuario.En caso de no retirarse el implante subdérmico en el tiempo estipulado por la casa productora 3 o 5 años según corresponda, este no tendrá el mismo efecto anticonceptivo por lo tanto es posible que se presente un embarazo no deseado.             '),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento se puede dirigir al médico tratante o al personal médico y de enfermería presente.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ______________________________________________________________________.

a)	Declaro que he entendido las condiciones y objetivos del procedimiento que se me va a practicar, los cuidados que debo tener antes y después, manifiesto que la información recibida ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyDIU($pdf)
    {
            $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,12);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->setX(74);
            $pdf->Cell(186, 4, utf8_decode('PARA LA INSERCIÓN  O RETIRO DE'), 0, 0, 'L', 0);
            $pdf->SetXY(76,20);
            $pdf->Cell(186, 4, utf8_decode('DISPOSITIVO INTRAUTERINO (DIU)'), 0, 0, 'L', 0);


            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-CE-034', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(70);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);
            $pdf->Text(160, 45, utf8_decode("Inserción"));
            $pdf->Text(180, 45, utf8_decode("Retiro"));
            $pdf->SetXY(174, 43);
            $pdf->cell(3, 3, "",1,1,'C');//check de insercion
            $pdf->SetXY(190, 43);
            $pdf->cell(3, 3, "",1,1,'C');//check de retiro

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+9);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('El dispositivo intrauterino es un dispositivo de distintos materiales, recubierto por metales que suele contener medicamentos, que se coloca dentro del útero con fines anticonceptivos y/o como tratamiento de algunas metrorragias. La colocación de este dispositivo y del modelo serán indicados en consulta ambulatoria por el ginecólogo, quien indicará los controles posteriores que debe tener, así como la duración de este.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Permitir que el paciente una decisión libre y autónoma sobre su salud reproductiva.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('En el momento de la inserción:'),0,'L');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Perforación uterina'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección en un periodo menor de un mes, pasado este periodo de tiempo la infección se debe a otras causas y no al dispositivo intrauterino.'));
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En la evolución:'),0,'L');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Gestación (1-3%) si esta se produce, existe un mayor riesgo de aborto y de embarazo ectópico. La tasa real de fracaso  como método anticonceptivo es mayor en el primer año, entre 1-3%.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Descenso y expulsión, puede ser asintomático o cursar con dolor o sangrado.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Alteraciones menstruales, aumento de la cantidad y/o duración del sangrado menstrual, manchado intermenstrual, así como la disminucióne e incluso ausencia de menstruación con los dispositivos intrauterinos con medicación.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Migración a cavidad abdominal con las complicaciones subsiguientes.'));
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En su extracción:'),0,'L');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Pérdida de referencia de los hilos y la rotura con retención de un fragmento.'));

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Existen alternativas dentro de los métodos de planificación variados como son:'),0,'J');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos orales.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de barrera (condón y diafragma).'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de Aplicación intramuscular mensuales o trimestrales.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de Aplicación intramuscular mensuales o trimestrales.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos hormonales mediante implante subdérmico.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Cada uno de estos métodos con sus posibles beneficios y complicaciones de acuerdo con la edad del paciente y las comorbilidades, para lo cual se deben tener en cuenta los criterios de elegibilidad.'));

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de no realizarse el procedimiento o intervención se pueden presentar embarazos no deseados ni planeados y, en usuarios con patologías de comorbilidad de base como hipertensión, diabetes, patologías cardiacas, hereditarias, puede generar complicaciones graves en el estado de salud del usuario. En caso de no retirarse el implante subdérmico en el tiempo indicado, este no tendrá el mismo efecto anticonceptivo por lo tanto es posible que se presente un embarazo no deseado.
            '),0,'J');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento se puede dirigir al médico tratante o al personal médico y de enfermería presente.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud. El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ______________________________________________________________________.

a)	Declaro que he entendido las condiciones y objetivos del procedimiento que se me va a practicar, los cuidados que debo tener antes y después, manifiesto que la información recibida ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyRetiroCuerpoExtraño($pdf)
    {
        $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,12);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->setX(80);
            $pdf->Cell(186, 4, utf8_decode('PARA RETIRO DE CUERPO '), 0, 0, 'L', 0);
            $pdf->SetXY(74,20);
            $pdf->Cell(186, 4, utf8_decode('EXTRAÑO (CÓRNEA, NARIZ, OIDO,'), 0, 0, 'L', 0);
            $pdf->SetXY(75,24);
            $pdf->Cell(186, 4, utf8_decode('TEJIDO CELULAR SUBCUTANEO)'), 0, 0, 'L', 0);

            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-CE-039', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(70);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+9);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('La intervención consiste en extirpar el cuerpo extraño localizado en: _____________________________________ Se denomina cuerpo extraño a todo elemento ajeno al organismo que penetra en este a través de cualquier vía, permaneciendo en el mismo y produciendo, por lo general, consecuencias desfavorables para el cuerpo humano. Las fosas nasales, los ojos, los oídos, y la piel,  por su localización, por su disposición y por su sensibilidad, son con frecuencia objeto de la presencia de cuerpos extraños. Ello puede producir síntomas diversos que deben de ser interpretados por su médico y que exigirían su extracción. Las técnicas de extracción son variables y dependen de la naturaleza del cuerpo extraño, del tamaño del mismo, del lugar concreto de su ubicación y del tiempo de permanencia. Una vez retirado el cuerpo extraño, los síntomas tienden a desaparecer, sea de forma inmediata o paulatinamente.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Permitir que el facultativo realice la extracción segura del cuerpo extraño, con la posterior mejoría de síntomas y recuperación del órgano o sitio     afectado.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('Después de la intervención podrá presentar molestias según el órgano y la zona afectada, y dependerá del procedimiento y al proceso de cicatrización, que pueden prolongarse durante algunas semanas, meses, o hacerse continuas. Aunque es excepcional, cabe la posibilidad de que se produzca una hemorragia de cierta intensidad, durante el período posterior a la intervención. Podría producirse una reacción inflamatoria local que dificulte el órgano o área afectada temporalmente. Según la localización y las características del cuerpo extraño, podría producirse una perforación, lesión de tejidos adyacentes, lo cual podría requerir otra intervención quirúrgica. No es frecuente que estas heridas se infecten, pero podría aparecer una pequeña infección, con supuración o salida de líquido por el oído, fosas o tejidos, si el estado general del paciente está debilitado, una septicemia, es decir, la propagación de la infección a través de la sangre del paciente. Podría producirse también un síncope vagal, o mareo con hipotensión e incluso pérdida de conocimiento, con posibilidad de parada cardiorrespiratoria, aunque esta situación es extremadamente improbable.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Inicialmente se puede mantener un tratamiento expectante pero, por dolor o si aumenta el volumen o cambian los síntomas, es recomendable su extracción. El tratamiento quirúrgico se recomienda cuando fracasan las medidas conservadoras. '),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Si no se realiza el procedimiento hay que suponer que el cuerpo extraño permanecerá en la zona afectada o alojada, causando las molestias y complicaciones que viene presentando.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento se puede dirigir al médico o profesional.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud. El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ______________________________________________________________________.

a)	Declaro que he entendido las condiciones y objetivos del procedimiento que se me va a practicar, los cuidados que debo tener antes y después, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)  Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodycirugiaParpadosYConjuntivas($pdf){
        $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,15);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->setX(83);
            $pdf->MultiCell(50, 4, utf8_decode('CIRUGIA DE PARPADOS Y CONJUNTIVAS'), 0, 'C');


            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-AI-061', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(81);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+7);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('La cirugía de párpados y conjuntiva es diseñada para cada paciente dependiendo de sus necesidades particulares. Puede hacerse de forma aislada o en combinación de distintos procedimientos quirúrgicos. El modo de actuación es distinto según la patología a intervenir, siendo en ocasiones necesarias blefaroplastias cuyo fin es eliminar el exceso de piel y de músculo, así como del tejido graso subyacente. En lesiones excrecentes palpebrales, ya sean tumorales quísticas o no quísticas, serán necesarias resecciones palpebrales con posterior reconstrucción anatómica y se hará toma de muestras para estudio diagnóstico.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Aunque existe un beneficio estético, el beneficio real es mejorar el pronóstico visual y procurar una reconstrucción anatómica.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). A pesar de la adecuada elección de la técnica y su correcta realización, todo procedimiento quirúrgico entraña ciertos riesgos. la decisión individual de someterse a una intervención quirúrgica se basa en la comparación del riesgo con el beneficio potencial. El riesgo más frecuente en este tipo de intervenciones es la reaparición de la patología inicial que puede resolverse, en ocasiones, con tratamiento médico o llegar a requerir intervención. Pueden producirse otros efectos menos frecuentes como son: cicatrices anormales y a veces antiestéticas, sangrado durante o después de la cirugía, infección y problemas de sequedad ocular.

En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse
            '),0,'J');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento, actualmente no existe otra alternativa terapéutica a la cirugía para el Pterigion. Sin embargo, en otras patologías: inyección de corticoide intralesional en chalazión, inyección de diversas sustancias peritumoralmente o radioterapia o quimioterapia en algún tipo de lesión premaligna o maligna, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado. '),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('La no realización de este tipo de cirugías conlleva en ocasiones la pérdida progresiva de la visión:  '),0,'J');
            $pdf->SetX(15);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Ptosis: caída del párpado superior sobre la pupila.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Pterigion: proliferación conjuntival hacia la córnea que avanza hacia el área pupilar y tracciona la misma, produciendo un astigmatismo irregular.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Ectropion y entropión: lesiones corneales severas con degeneraciones progresivas no reversibles. las tumoraciones malignas no extirpadas pueden tener mal pronóstico a nivel visual y/o sistémico.'));


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al oftalmólogo tratante o al personal médico presente.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis, marcapasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->SetTextColor(10,10,10);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

public function bodycirugiaAmputacionesMayores($pdf){
        $logo = public_path() . "/images/logo.png";
            $pdf->SetFont('Arial', 'B', 11);

            $pdf->Line(20, 5, 190, 5);
            $pdf->Line(20, 35, 190, 35);
            $pdf->Line(20, 5, 20, 35);
            $pdf->Line(190, 5, 190, 35);

            $pdf->Image($logo, 22, 7, -220);
            $pdf->Line(67, 5, 67, 35);
            $pdf->SetXY(76,15);
            $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->setX(71);
            $pdf->MultiCell(70, 4, utf8_decode('CIRUGÍA PARA AMPUTACIONES MAYORES'), 0, 'C');


            $pdf->Line(147, 5, 147, 35);
            $pdf->Line(147, 25, 190, 25);
            $pdf->Line(147, 16, 190, 16);
            $pdf->SetXY(112, 13);

            $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
            $pdf->SetX(149);
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 0, 'FO-AI-036', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(113, 22);
            $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

            $pdf->SetFont('Arial', '', 11);
            $pdf->SetXY(134, 22);
            $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetXY(136, 28);
            $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(115);
            $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY(20, 45);
            $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

            $pdf->SetTextColor(10, 10, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(81);
            $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

            $pdf->Line(10, 50, 200, 50);
            $pdf->Line(10, 65, 200, 65);
            $pdf->Line(10, 50, 10, 65);
            $pdf->Line(200, 50, 200, 65);
            $pdf->Line(25, 50, 25, 65);
            $pdf->Ln();
            $pdf->SetXY(10, 58);
            $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

            $y1 = $pdf->GetY();
            $pdf->Line(25, $y1+1, 200, $y1+1);
            $y2 = $pdf->GetY();
            $pdf->Line(55, $y2+7, 55, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(85, $y2+7, 85, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(145, $y2+7, 145, $y2-8);
            $y2 = $pdf->GetY();
            $pdf->Line(180, $y2+7, 180,$y2-8);

            $pdf->SetFillColor(252, 250, 250 );

            $y1=$pdf->GetY();
            $pdf->SetX(25);
            $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(50,$y1);
            $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
            $y3=$pdf->GetY();
            $pdf->SetXY(80,$y1);
            $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
            $y4=$pdf->GetY();
            $pdf->SetXY(148,$y1);
            $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
            $y5=$pdf->GetY();
            $pdf->SetXY(180,$y1);
            $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

            $y3 = $pdf->GetY();
            $pdf->SetXY(25,$y3+3);
            $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
            $pdf->SetXY(55,$y3+3);
            $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
            $pdf->SetXY(85,$y3+3);
            $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
            $pdf->SetXY(145,$y3+3);
            $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
            $pdf->SetXY(180,$y3+3);
            $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
            $cont = max($y1,$y2,$y3);
            $yt = $cont;


            $yt = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetXY(12,$yt+7);
            $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Se ha estudiado los síntomas que usted padece y realizado las exploraciones complementarias oportunas, encontrando un déficit de circulación y/o infección muy grave de su extremidad completa. Por este motivo se considera necesario someterlo a la amputación de la extremidad completa.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
            $pdf->SetFont('Arial', '', 8);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Los beneficios que se esperan son pensando siempre en una rehabilitación que le permita llevar una vida lo más normal posible y evitar complicaciones en su estado de salud, sobre todo mejorar su calidad de vida.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como: '),0,'J');
            $pdf->SetX(16);
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Locales: hemorragia, infección, necrosis de los tejidos, dehiscencia de la sutura del muñón, sensación de miembro fantasma (siente todavía el dolor de la pierna).'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Generales: infarto agudo de miocardio, trombosis venosa profunda, embolia pulmonar, neumonía, fallo cardíaco, insuficiencia respiratoria, insuficiencia renal, isquemia cerebral, Infección urinaria.'));
            $pdf->MultiCellBlt(183,4,'>',utf8_decode('Como riesgos poco frecuentes son flebitis superficial. escaras de decúbito, depresión, etc. '));
            $pdf->SetX(12);
            $pdf->Ln();
            $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud'),0,'J');

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

            $pdf->SetTextColor(10,10,10);

            $pdf->Ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReseccionCuaqdranteMamayColgajo($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('RESECCIÓN DE CUADRANTE DE MAMA Y COLGAJO'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-081', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Para la resección del cuadrante de mama se extirpa la lesión comprobando con radiografías la resección completa. En algunos casos el estudio anatomopatológico intra o postoperatorio puede indicar la necesidad de ampliar la resección a los ganglios de la axila y/o a parte o a toda la mama.

Cabe la posibilidad de que durante la cirugía haya que realizar modificaciones del procedimiento por los hallazgos intraoperatorios para proporcionar el tratamiento más adecuado. La intervención requiere la administración de anestesia. La reconstrucción mamaria es un procedimiento quirúrgico que devuelve la forma de la mama después de una mastectomía (cirugía que extrae la mama para tratar o prevenir el cáncer de mama) o resección de un cuadrante de la mama. La reconstrucción mamaria con cirugía con colgajos implica tomar una porción de tejido de un área del cuerpo (generalmente, del abdomen) y reubicarla para crear un nuevo montículo mamario.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Extirpar la lesión de la mama para un estudio anatomopatológico completo, evitando su crecimiento. Con la reconstrucción mamaria mejora la autoestima, calidad de vida y la condición emocional de la mujer.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->SetX(16);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Inflamación grave de los linfáticos del brazo, reproducción de la enfermedad y sangrado importante. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero pueden llegar a requerir una reintervención, generalmente de urgencia, incluyendo un riesgo mínimo de mortalidad.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Diferencia de tamaño entre los senos.'));
                $pdf->SetX(12);
                $pdf->Ln();
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al otorrinolaringólogo tratante o al personal médico y de enfermería presente.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.
                '),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.

a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

d)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }


    public function bodyHisteroscopiaDiagnostica($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('HISTEROSCOPIA DIAGNÓSTICA'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-066', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La histeroscopia diagnóstica consiste en la introducción a través de la vulva y la vagina, en el conducto endocervical y en el interior de la cavidad uterina de un sistema óptico que permite la visualización de las estructuras que recorre: conducto endocervical y cavidad endometrial. Para permitir esta visualización se introduce con el mismo sistema un haz de luz y se distiende la cavidad uterina por medio de un gas (CO2) o de un líquido (suero fisiológico). La exploración se suele completar con la práctica de biopsias endometriales que se realizan dentro de la misma exploración, tras retirar el histeroscopio.
La exploración puede no completarse siempre con absoluta seguridad, así puede no completarse por problemas para atravesar el conducto cervical y visualizar la cavidad o por reacciones o complicaciones que pueden aconsejar no seguir el procedimiento'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Aliviar o disminuir síntomas que usted presente. Realizar un diagnóstico oportuno'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).

A pesar de la adecuada elección y realización de la técnica pueden presentarse efectos indeseables: Reacciones vagales (mareos, sudoración, malestar), dolor de hombros, dolor precordial y dolor abdominal, por el paso de gas al peritoneo.

Pueden presentarse otros efectos que aunque son poco frecuentes revisten mayor gravedad: Embolias gaseosas, infecciones tuboperitoneales, perforación uterina, formación de falsas vías, algunas de las cuales pueden requerir la hospitalización inmediata, poner en peligro la vida y requerir tratamientos médicos y/o quirúrgicos adicionales.

En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al otorrinolaringólogo tratante o al personal médico y de enfermería presente.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.

El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.
                '),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReduccionAbiertaFactura($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('REDUCCIÓN ABIERTA DE FRACTURAS'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-041', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Los desplazamientos se producen por la tracción muscular al perder continuidad el hueso a nivel de la fractura. El traumatismo primero rompe el hueso y después lo desvía. La reducción es una maniobra manual o mecánica que permite colocar los fragmentos desplazados en una posición de total contacto, o en alineación, del segmento fracturado. De una manera muy simple, pero efectiva para reducir, primero se observan los desplazamientos y después se debe llevar el fragmento distal hacia el fragmento proximal encajando o alineando los fragmentos.
Reducción: Alineamiento correcto de los extremos del hueso fracturado.

En los casos de fracturas complicadas que no se pueden realinear (reducir) con una férula, o cuando el uso prolongado de una férula no es recomendable, se utiliza la reducción abierta y la fijación interna, la cual requiere una operación quirúrgica para reparar un hueso roto. Une las partes de un hueso roto para que puedan sanar. La reducción abierta quiere decir que los huesos se colocan de regreso en su lugar durante una cirugía. La fijación interna significa que se usan aparatos especiales para sostener juntos los pedazos de hueso. Esto ayuda a que el hueso se suelde correctamente, con frecuencia se utilizan varillas de metal, tornillos o placas para reparar el hueso, los cuales se mantienen fijos, debajo de la piel, después de la cirugía. '),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Mejoría y/o desaparición del dolor provocado por la fractura. Posible retorno a la actividad habitual. '),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hemorragia.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión en los nervios.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Que el hueso se suelde mal alineado.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Coágulos de sangre.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Embolia .'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tornillos o placas rotos.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Pérdida de movimiento.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Daños en los huesos debido a los elementos de sujeción.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Irritación de la piel causada por los elementos de sujeción.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud. '),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }
    public function bodyReseccionChalazion($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('RESECCIÓN DE CHALAZION'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-041', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('El chalazión es la inflamación y obstrucción de una o de varias glándulas de Meibomio. Las glándulas de Meibomio están en los párpados y su misión es secretar sustancias de la película lagrimal. El chalazión es una pequeña bola o quiste situado bajo la piel del párpado, que, en ocasiones, es visible a simple vista o por lo menos se notará palpándolo como un pequeño “bulto” en el párpado; este, puede ocasionar irritación conjuntival, y si es muy grande puede incluso ejercer presión sobre el globo ocular y producir astigmatismo. Este procedimiento consiste en realizar una pequeña incisión por la parte interior del párpado afectado para extraer el contenido de la glándula afectada sin dejar ninguna marca visible.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('El objetivo de esta cirugía es restaurar la visión del paciente. Se pretende lograr una visión útil, siempre que no existan otras patologías oftalmológicas asociadas que lo impidan. '),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel).'),0,'J');
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tras la intervención suele haber una inflamación del ojo pasajera responsable de tener una visión borrosa los primeros días postoperatorios. Esta inflamación puede acompañarse de aumento de la tensión ocular.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otra complicación es el hematoma en el párpado que puede permanecer durante 10 días.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Existen riesgos derivados de anestesia, que puede ser con gotas o con infiltración de anestesia alrededor del ojo. Entre los riesgos están la hemorragia retro bulbar, oclusión de la arteria central de la retina, lesión del nervio óptico, perforación ocular, depresión cardiovascular y pulmonar, reacción tóxico- alérgica. Las complicaciones más graves son la hemorragia expulsiva y las infecciones intraoculares, en menos del 0,4% que llevarían a la pérdida del ojo de forma inmediata.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Inicialmente se usan gotas recetadas por el oftalmólogo, pero si el chalazión aumenta de tamaño rápidamente la única alternativa es la resección del mismo.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud. Si la cirugía no se realiza aumentará la presión en el globo ocular y posiblemente le dará astigmatismo (visión borrosa a cualquier distancia).'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud. '),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyEseccionSimpleCicatrizAreaEspecial($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('RESECCIÓN SIMPLE DE CICATRIZ'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-059', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es el proceso quirúrgico a través del cual se estilizan las cicatrices de la piel. A lo largo de la vida son diferentes situaciones las que nos pueden dejar cicatrices en la piel. Acciones como caídas, golpes, accidentes o incluso operaciones, cirugías u otras intervenciones.
Igualmente, restaura la función y corrige los cambios en la piel (desfiguración) ocasionados por una herida, por una lesión, una mala curación o una cirugía previa.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Mejorar tu autoestima y autoconfianza y tu capacidad para participar en actividades físicas.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección, seromas, hematomas que se pueden controlar con el uso de antibióticos y drenajes.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Asimetrías: Cicatrización inadecuada que puede requerir posteriormente nuevas intervenciones para mejorarla por supuesto dependiendo de su cicatrización. Los fumadores tienen gran predisposición a una mala cicatrización. Complicaciones pulmonares como bronconeumonías, tromboembolismos etc.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyProtesisArticularCadera($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('PRÓTESIS DE CADERA'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-076', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La articulación de la cadera es una articulación esférica donde la cabeza del fémur (hueso de la cadera) se une con la pelvis en la cavidad denominada acetábulo. La sustitución de la articulación de la cadera es un procedimiento quirúrgico en el que se hace un cambio total o parcial de la articulación de la cadera utilizando unos dispositivos artificiales (prótesis) que van anclados al hueso mediante cementos acrílicos, o mediante ajustes a presión o atornillados, para restaurar el movimiento articular. El reemplazo de la articulación de la cadera se realiza, sobre todo, en personas adultas, cuando la articulación se ha visto dañada de forma irreparable e irreversible, produciendo una gran incapacidad y dolor. La cirugía se lleva a cabo utilizando anestesia general o espinal.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Aliviar el dolor, restaurando la función articular perdida.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos menos graves, aunque frecuentes, relacionados con los cambios en la movilidad articular, que puede verse disminuido por contracturas, o rigidez articular postoperatoria. Diferencias en la longitud de los miembros, debido a acortamientos o alargamientos por los implantes o su adaptación al hueso. Defectos en la rotación del miembro operado y/o defectos de angulación del mismo.
La cirugía de prótesis articular tiene éxito en más del 90 % de los casos, ahora bien, pueden existir complicaciones, la mayoría de las cuales pueden ser igualmente tratadas con éxito. Dentro de las complicaciones poco frecuentes pero graves, se incluyen:'),0,'J');
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infecciones: superficiales (en la herida) o profundas (cerca o en la misma prótesis). Las infecciones superficiales o menores pueden tratarse con antibióticos, pero las profundas pueden requerir la retirada la prótesis. Cualquier infección del cuerpo puede extenderse a la prótesis.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tromboembolismos: se sospechan si se desarrolla dolor y edema o inflamación en la pantorrilla o el muslo. Aunque se instauran medidas para su prevención tales como medicación pre y postoperatoria. medias elásticas, ejercicios de las piernas etc., pueden ocurrir y requerirán un tratamiento específico y a menudo largo.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Aflojamiento: el aflojamiento de la prótesis dentro del hueso puede ocurrir, a corto o medio plazo, provocando dolor y precisando la reintervención.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Luxación: ocasionalmente, después de una sustitución de cadera, puede existir una luxación por desencajamiento de la cabeza femoral de su cotilo. En la mayoría de los casos, la cadera puede volver a ser repuesta en su lugar sin necesidad de cirugía. Este es un problema que se da con mayor frecuencia en la cirugía de revisión o recambio protésico.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Desgaste: el uso continuado de las superficies articulares puede provocar un pequeño desgaste de las mismas. Si este desgaste fuera excesivo podría llegar a contribuir al aflojamiento de la prótesis ya la necesidad de nueva cirugía de recambio.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Roturas de los implantes: aunque cada vez es más raro, por la mejora de los materiales usados en las mismas, puede ocurrir por fatiga de material, implicando cirugía de revisión.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesiones nerviosas: son infrecuentes, pero pueden llegar a crear importantes incapacidades, aunque en algunos casos menos severos también se puede esperar su recuperación completa dependiendo del grado de lesión.'));

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReseccionGinecomastia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('RESECCION DE GINECOMASTIA'), 0, 'C');


                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-076', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La ginecomastia es un aumento del tamaño de la glándula mamaria en el hombre. Su etiología es variada, asociada principalmente a un hiperestrogenismo, aunque en muchos casos es idiopática. Esta anomalía produce una deformidad de carácter estético que provoca alteraciones de orden psicológico en el paciente.
La técnica quirúrgica para aplicar depende principalmente del grado de la ginecomastia, y de la distribución y proporción de los diversos componentes (graso y parenquimatoso) de la mama. Básicamente podemos dividir las técnicas en cuatro tipos:'),0,'J');
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Exéresis quirúrgica simple. Indicada en los casos con aumento mamario debido básicamente a hipertrofia glandular.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Liposucción simple. Indicada en los casos con predominio prácticamente de tejido graso.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Exéresis quirúrgica más liposucción. Indicada en los casos en que la hipertrofia glandular se limita únicamente al área retro o periareolar, mientras que el resto del aumento mamario se debe al componente graso.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Exéresis quirúrgica más resección cutánea. Indicada en los casos con exceso cutáneo, en los que puede ser necesario trasladar el complejo areola-pezón.'));
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La intervención se puede realizar tanto bajo anestesia general como local, dependiendo del grado de la ginecomastia, así como de las características del paciente. El marcaje de la zona a intervenir se realiza preoperatoriamente con el paciente de pie, dibujando un mapa topográfico de la deformidad que debe ser resecada y de la zona periférica que debe ser tratada, con el fin de remodelar el contorno torácico y prevenir deformidades secundarias.
El objetivo del tratamiento quirúrgico es conseguir un aspecto normal del tórax masculino, con la menor cicatriz posible.'),0,'J');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Mejora la autoestima, calidad de vida y la condición emocional del hombre.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Según el tipo y tamaño de la glándula quedará una cicatriz más o menos amplia.
A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables, tanto los comunes derivados de toda intervención y que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento: Infección o sangrado de la herida quirúrgica, colección de líquido en la cicatriz, flebitis, edema transitorio del brazo, cicatrices retráctiles y dolor prolongado en la zona de la operación. Pueden darse riesgos poco frecuentes, aunque graves, tales como: inflamación grave de los linfáticos del brazo, reproducción de la enfermedad y sangrado importante. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero pueden llegar a requerir una reintervención, generalmente de urgencia, incluyendo un riesgo mínimo de mortalidad.'),0,'J');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Continuar con la ginecomastia y las implicaciones emocionales que pueden repercutir en el paciente.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al mastólogo tratante o al personal médico y de enfermería presente.'),0,'J');


                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyMamoplastiaReduccionBilateral($pdf){
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('DE MAMOPLASTIA'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-082', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La cantidad de tejido mamario cambia proporcional al peso, al embarazo, y a cambios hormonales. Los ligamentos y el sistema de ductos en la glándula mamaria se estirarán por lo cual desciende el seno. Existen múltiples causas por las cuales se presenta la hipertrofia mamaria. Por una parte, hay factores hormonales en el momento del desarrollo de la mujer o después de la lactancia constituyéndose así unos senos grandes; en algunos genéticamente presentan un crecimiento exagerado del seno presentado gigantomastia. Otro factor para considerar es la obesidad que condiciona un aumento del tejido graso en la glándula mamaria aumentando su volumen. También se realiza con fines oncológicos, la aplicación de las técnicas de reducción mamaria a pacientes con cáncer de mama permite un tratamiento quirúrgico conservador, adecuado desde el punto de vista oncológico.
La reducción mamaria es un procedimiento que se utiliza para eliminar el exceso de grasa, tejido y piel de las mamas, busca varios objetivos como son reubicar en una posición más alta la areola y el pezón, mejorar la forma del seno mediante la reducción del tamaño de la glándula mamaria.

En algunas pacientes el tamaño del seno puede tener repercusiones en la espalda presentando dolor. Sin embargo, para llegar a este diagnóstico se debe descartar otras causas frecuentes de dolor de espalda como es la obesidad, mala postura, debilidad muscular tanto de los músculos abdominales como los de la espalda. También se realiza como procedimiento estético.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Mejorar la calidad de vida.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Facilitar terapias coadyuvantes en tratamiento oncológico'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Aliviar el dolor de espalda si es su caso.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Mejorar tu autoestima y autoconfianza y tu capacidad para participar en actividades físicas.'));
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Asimetrías: Esta demostrado que las dos mitades del cuerpo son diferentes y que en algunos casos el seno derecho puede ser ligeramente más grande y su areola estar un poco más abajo que la izquierda o lo contrario. Igualmente, dependiendo de su cicatrización, se podrán presentar cicatrices anchas o que el seno vuelva a caerse.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyOsteotomiaRodilla($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('OSTEOTOMIA'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-080', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En la rodilla existen tres compartimentos que pueden desgastarse sucesivamente o a la vez: compartimento interno, compartimento externo y el compartimento femoro-patelar. Este desgaste articular es ocasionado en la mayoría de las veces por la gonartrosis (artrosis de rodilla) siendo el tratamiento inicial en la mayoría de los casos de tipo médico. Sin embargo, la cirugía juega un papel muy importante y absoluto en los casos de gonartrosis secundaria y en las avanzadas.

Las causas secundarias más importantes de gonartrosis son las desviaciones de ejes en los miembros inferiores. Estas desviaciones producen trastornos de la transmisión de las cargas normales (peso corporal) desde la pelvis hasta los pies, produciendo la sobrecarga de alguno de los compartimentos y su desgaste posterior. Cuando la deformidad es en varo, las fuerzas se concentran medialmente y se aceleran los cambios degenerativos del compartimento medial de la rodilla.

Técnica: Osteotomía significa corte en el hueso y consiste en la extracción de una cuña ósea de la tibia y posterior cierre del defecto. Es como una fractura provocada que requiere posterior fijación con diferentes sistemas como placas, grapas o tornillos, dependiendo de la técnica elegida. El fundamento biomecánico de la osteotomía tibial proximal en los pacientes con artrosis unicompartimental de la rodilla es descargar el compartimento afectado de la articulación, corrigiendo la desalineación y redistribuyendo las presiones en la articulación.

Reorientación de las superficies articulares para lograr restaurar el eje de carga fisiológico o normal de la articulación. Retraso del proceso evolutivo rápido de la artrosis en las condiciones de sobrecarga ponderal en las condiciones previas a la cirugía en uno o ambos compartimentos.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Mejoría y/o desaparición del dolor provocado por la artrosis. Posible retorno a la actividad habitual.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('La complicación más frecuente de este tipo de intervención consiste en la infracorrección de la deformidad, seguida de la seudoartrosis o no unión de la fractura, la lesión del nervio ciático poplíteo externo con debilidad o ausencia para la flexión dorsal del pie, la trombosis venosa profunda que puede evolucionar a embolias o trombos pulmonares y la infección.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('La complicación poco frecuente es la lesión arterial que condicionaría la posible pérdida de la pierna. Fractura de algún segmento de la tibia durante la osteotomía de trazo intraarticular.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReseccionPteringion($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('RESECCION DE PTERIGION'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-084', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('El pterigión es un crecimiento anormal del tejido. Puede presentarse como un abultamiento y suele empezar en la parte blanca del ojo. Si se deja avanzar, puede llegar a cubrir la córnea y obstaculizar la visión. Una de las principales causas de esta patología es la exposición a los rayos ultravioleta sin la debida protección o por sequedad ocular.
La extracción del pterigion consiste, en primer lugar, se coloca un injerto de conjuntiva propio del paciente en el defecto conjuntival que queda tras extirpar el pterigion; posteriormente se colocan suturas absorbibles o se utilizaran pegamentos biológicos para evitar el uso de suturas.
La cirugía del Pterigion está indicada cuando existe una disminución de la agudeza visual, ya sea por proximidad/afectación del eje visual o por astigmatismo, así como si existe restricción de la motilidad ocular o presenta actividad en su crecimiento.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('El objetivo de esta cirugía es restaurar la visión del paciente. Se pretende lograr una visión útil, siempre que no existan otras patologías oftalmológicas asociadas que lo impidan.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tras la intervención suele haber una inflamación del ojo pasajera responsable de tener una visión borrosa los primeros días postoperatorios. Esta inflamación puede acompañarse de aumento de la tensión ocular. Existen otras complicaciones menos frecuentes, que conllevan cierta gravedad.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones son el enrojecimiento del ojo y se pueden apreciar molestias por algunos días.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Existen riesgos derivados de anestesia, que puede ser con gotas o con infiltración de anestesia alrededor del ojo. Entre los riesgos están la hemorragia retrobulbar, oclusión de la arteria central de la retina, lesión del nervio óptico, perforación ocular, depresión cardiovascular y pulmonar, reacción tóxico- alérgica. Las complicaciones más graves son la hemorragia expulsiva y las infecciones intraoculares, en menos del 0,4% que llevarían a la pérdida del ojo de forma inmediata.'));
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas: inicialmente se usan gotas recetadas por el oftalmólogo, pero si la visión está comprometida la única alternativa es la resección del Pterigion, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud, la pérdida de visión será progresiva y las molestias aumentarán.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al oftalmólogo tratante o al personal médico y de enfermería presente.'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCambioSondaGastrostomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('CAMBIO DE SONDA DE GASTROSTOMÍA'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-PS-063', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Una gastrostomía es un estoma (apertura o abocadura) a través de la pared abdominal hasta el estómago, donde se coloca un tubo flexible que permite la alimentación, la administración de líquidos y/o medicamentos directamente en el estómago, sin pasar por la boca y el esófago. El procedimiento para el cambio de sonda inicia con la tracción de la sonda antigua, fijando con una mano la pared abdominal circundante y con la otra haciendo un movimiento de tracción. Posteriormente mediante vía percutánea, se coloca nuevamente un tubo flexible nuevo para continuar con la terapia enteral del paciente. El cambio de sonda de gastrostomía está indicada cuando hay deterioro de la sonda o del estoma, hay rotura del balón, se sale el tubo de gastrostomía o hay obstrucción de la sonda.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora del estado de salud. Se espera que el paciente puede continuar con su vía alterna de alimentación y recibir su medicación.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento como: infección o sangrado del estoma, y la creación de una falsa ruta con ruptura de vísceras o filtración de liquido gástrico o intestinal a la cavidad, lo que probablemente requiera de una cirugía urgente para drenar y lavar la cavidad y colocar una nueva sonda.'),0,'J');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas _______________________________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyTratamientoQxHalluxValgus($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('TRATAMIENTO QUIRÚRGICO DEL HALLUX VALGUS'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-049', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La intervención consiste en la corrección de la deformidad a nivel del primer radio del pie con realineación de éste y eliminación de la prominencia ósea de la cara interna del primer metatarsiano ("juanete") y, si es necesario, una corrección de las deformidades asociadas de los dedos menores. Se realiza con el fin de corregir en lo posible la deformidad de los dedos, prevenir las metatarsalgias (dolor en la planta del pie. debajo de los dedos) y la aparición de otras deformidades, como dedos en garra, hiperqueratosis (callosidades) y subluxaciones de las articulaciones; mejorar la biomecánica del antepié e intentar la desaparición de los dolores.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Mejorar el aspecto estético del pie al corregir las deformidades, es previsible que desaparezca el dolor en la cara interna del primer dedo, así como las callosidades tanto del "juanete" como de los dedos menores si también hemos actuado sobre ellos.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Contractura de la primera membrana interdigital, limitación del movimiento de la articulación metatarsofalángica, sangrado excesivo en el postoperatorio, acortamiento del primer dedo, a veces quedan molestias residuales que pueden requerir tratamiento ortopédico y/o médico, y en algunas ocasiones una segunda intervención Existen otros riesgos como:'),0,'J');
                $pdf->Ln();
                $pdf->SetX(15);
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Recidiva de la deformidad.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Primer dedo en garra.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Adormecimiento del primer dedo por lesión de los nervios digitales.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Necrosis de los bordes de la herida Infección de la herida quirúrgica, ya sea superficial o profunda, con riesgo de afectación de estructuras internas (osteítis, artritis séptica, etc.).'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Necrosis avascular de la cabeza del primer metatarsiano.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('No unión de la osteotomía.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Fractura del metatarsiano.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Algodistrofia simpático-refleja.'));
                $pdf->MultiCellBlt(183,4,'>',utf8_decode('Trombosis venosa profunda. Y eventualmente tromboembolismo pulmonar de graves consecuencias.'));

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas ______________________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyAnestesia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
                $pdf->SetFont('Arial', 'B', 11);

                $pdf->Line(20, 5, 190, 5);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Line(20, 5, 20, 35);
                $pdf->Line(190, 5, 190, 35);

                $pdf->Image($logo, 22, 7, -220);
                $pdf->Line(67, 5, 67, 35);
                $pdf->SetXY(76,15);
                $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->setX(72);
                $pdf->MultiCell(70, 4, utf8_decode('DE ANESTESIA'), 0, 'C');

                $pdf->Line(147, 5, 147, 35);
                $pdf->Line(147, 25, 190, 25);
                $pdf->Line(147, 16, 190, 16);
                $pdf->SetXY(112, 13);

                $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
                $pdf->SetX(149);
                $pdf->SetFont('Arial', '', 11);
                $pdf->Cell(0, 0, 'FO-AI-099', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(113, 22);
                $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

                $pdf->SetFont('Arial', '', 11);
                $pdf->SetXY(134, 22);
                $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

                $pdf->SetFont('Arial', 'B', 11);
                $pdf->SetXY(136, 28);
                $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
                $pdf->SetFont('Arial', '', 11);
                $pdf->SetX(115);
                $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(20, 45);
                $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

                $pdf->SetTextColor(10, 10, 10);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(81);
                $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

                $pdf->Line(10, 50, 200, 50);
                $pdf->Line(10, 65, 200, 65);
                $pdf->Line(10, 50, 10, 65);
                $pdf->Line(200, 50, 200, 65);
                $pdf->Line(25, 50, 25, 65);
                $pdf->Ln();
                $pdf->SetXY(10, 58);
                $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

                $y1 = $pdf->GetY();
                $pdf->Line(25, $y1+1, 200, $y1+1);
                $y2 = $pdf->GetY();
                $pdf->Line(55, $y2+7, 55, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(85, $y2+7, 85, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(145, $y2+7, 145, $y2-8);
                $y2 = $pdf->GetY();
                $pdf->Line(180, $y2+7, 180,$y2-8);

                $pdf->SetFillColor(252, 250, 250 );

                $y1=$pdf->GetY();
                $pdf->SetX(25);
                $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(50,$y1);
                $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(80,$y1);
                $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(148,$y1);
                $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(180,$y1);
                $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

                $y3 = $pdf->GetY();
                $pdf->SetXY(25,$y3+3);
                $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
                $pdf->SetXY(55,$y3+3);
                $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
                $pdf->SetXY(85,$y3+3);
                $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
                $pdf->SetXY(145,$y3+3);
                $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
                $pdf->SetXY(180,$y3+3);
                $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
                $cont = max($y1,$y2,$y3);
                $yt = $cont;


                $yt = $pdf->GetY();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetXY(12,$yt+7);
                $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('La anestesia es el uso de medicamentos para prevenir el dolor durante una cirugía y otros procedimientos. Estos medicamentos se denominan anestésicos. Puede ser general, regional o local.

La anestesia general se administra mediante inyección intravenosa o por inhalación de anestésicos. Siempre es necesario asegurar la vía aérea y mantener una oxigenación adecuada, a través de la aplicación de un tubo en la tráquea o el uso máscaras laríngea o facial, según el caso. Mientras que, la anestesia regional, consiste en el bloqueo de la sensibilidad y motricidad de una región anatómica del cuerpo o una parte determinada en el caso de la anestesia local por la inyección de anestésicos cerca de los nervios correspondientes. Puede ser central (como peridural y raquídea) donde se bloquean los nervios en la medula espinal o periférica cuando se bloquean los nervios específicos de las extremidades.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Los beneficios ante la aplicación de anestesia general son: reducción de la pérdida de sangre y facilitar la eventual realización del procedimiento quirúrgico por la eliminación completa del dolor durante la realización del mismo. Con la aplicación de anestesia regional se busca, disminución de las acciones sobre los sistemas cardiovascular y respiratorio y la disminución en los tiempos de recuperación.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Puede generar alergia a los medicamentos anestésicos, daño en la vena durante la aplicación de la anestesia general que puede necrosar (gangrena) y perder el tejido donde está dicha vena, requiriendo manejo quirúrgico para su tratamiento, fiebre alta que puede llegar a ser de difícil manejo, dolor de garganta, ronquera, daño dental, lesión de cuerda vocal, arritmias (latidos anormales del corazón), infarto cardiaco, paro cardiorespiratorio, daño cerebral, náuseas, vomito, disminución de la presión arterial, lesión de nervios en las extremidades, úlceras en la córnea, trastornos del sueño, recuerdos intraoperatorios, broncoaspiración (que se llenen los pulmones de alimentos y secreciones), laringoespasmo (cierre de la vía aérea), edema pulmonar (agua en los pulmones) e incluso, la muerte. En el caso de la anestesia, raquídea o epidural (con carácter o sin carácter) puedo presentar dolor de espalda crónico, dolor de cabeza que puede requerir una nueva inyección para colocar un parche con sangre, lesión en la columna, hematoma en la médula (coágulo de sangre que comprime la médula) y que puede requerir manejo quirúrgico para evitar secuelas neurológicas en las piernas, infección en la columna o incluso la muerte, también puede ocasionar pérdida o malformación del producto del embarazo. La aplicación de un tipo de anestesia no descarta que sea necesario cambiar la técnica inicial de acuerdo a las circunstancias. A pesar de no tener alteraciones anatómicas detectadas en el examen físico, se pueden presentar problemas en el manejo de la vía aérea. Se puede llegar a necesitar equipos médicos especiales de monitoreo para su utilización requiere de invadir el cuerpo con agujas, catéteres, guías metálicas, que pueden producir daños como lesiones pulmonares, vasculares, arritmias, perforación cardiaca, isquemia (falta de sangre) de los dedos que requiera incluso de amputación, es importante saber que incluso con una mínima dosis de anestésico, se puede ocasionar la muerte por una reacción alérgica.'),0,'J');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('No se contemplan alternativas a la aplicación de la anestesia.'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse a la administración de la anestesia, lo más probable es que no pueda ser intervenido quirúrgicamente.'),0,'J');

                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto se puede dirigir al médico tratante'),0,'J');


                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

                $pdf->SetTextColor(10,10,10);

                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetX(12);
                $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyColporrafiaAnteriorPosterior($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('COLPORRAFIA ANTERIOR Y/O POSTERIOR'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-050', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Este procedimiento se indica como tratamiento para el descenso de la vejiga y recto (cistocele - retocele). La cirugía consiste básicamente en, la elevación de la vejiga por medio de unos puntos de sutura los cuales pueden realizase por vía vaginal, vía abdominal, vía laparoscópica, vía transuretral o en forma combinada abdominal y vaginal, dependiendo del criterio médico y de los recursos técnicos que tenga la institución, así como de las necesidades del paciente.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Otros beneficios específicos esperados de la realización de este procedimiento son: ____________________________________________________________________________________________________________________________________________________.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Otras complicaciones, infección de sitio operatorio, muerte.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');


    }

    public function bodyCorreccionLesionesManguitoRotador($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CORRECCIÓN DE LESIONES DEL MANGUITO ROTADOR'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-056', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El propósito principal de la intervención es disminuir el roce que provoca el acromion sobre los tendones del manguito de los rotadores. Asimismo, en caso de rotura de los tendones, proceder a la reparación de estos si es posible. La intervención precisa anestesia, cuyo tipo y modalidad serán valoradas por el anestesiólogo.

La intervención consiste en recortar la parte anterior del acromion (acromioplastia) y, eventualmente, el ligamento coracoacromial para aumentar el espacio por donde corren los tendones. Estos tendones, cuando están rotos se acortan y degeneran, convirtiéndose en frágiles, retraídos y con poca capacidad de cicatrizar, lo que puede hacer imposible su reparación. La descompresión y la reparación tendinosa puede llevarse a cabo mediante cirugía abierta convencional o cirugía artroscópica, o bien procedimientos combinados en los que se efectúa una parte mediante artroscopia y otra parte de la intervención mediante una pequeña incisión de 4 ó 5 cm aproximadamente (mini abordaje).'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Habitualmente, al día siguiente de la operación, ya se inician unos ejercicios muy sencillos (llamados pendulares) para ir movilizando la articulación del hombro, y de esta manera evitar en el mayor grado posible la presencia de una limitación de la movilidad articular. Entre el segundo y tercer mes después de la operación se espera tener una movilidad prácticamente completa de la articulación del hombro, iniciando entonces la pauta de tonificación muscular para ganar progresivamente la fuerza.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Otras complicaciones específicas son: infección de las heridas quirúrgicas o de la articulación, fracaso de la sutura de los tendones, que puede hacer necesaria una nueva intervención, lesión de estructuras vasculonerviosas adyacentes a la articulación que pueden llevar a la amputación del miembro y a secuelas neurológicas irreversibles, rigidez articular, que puede requerir un largo tiempo de rehabilitación o una nueva intervención para liberar las adherencias articulares, fractura de estructuras óseas cercanas a la articulación durante las manipulaciones requeridas, ruptura de tendones o ligamentos adyacentes, distrofia simpático-refleja, parálisis de los nervios de la mano, que habitualmente son recuperables y muy poco frecuentes, síndrome compartimental, fallos y roturas del material empleado, trombosis venosa y, eventualmente, tromboembolismo pulmonar de graves consecuencias.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyLesionTejidoRectalCondilomas($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('DE ABLACIÓN DE LESIÓN O TEJIDO RECTAL O ANAL
        '), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-054', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Teniendo en cuenta las condiciones del paciente se considera que la mejor opción en este momento es la realización del procedimiento de ablación de lesión tejido rectal o anal que se realiza a pacientes portadores de enfermedades malignas pélvicas como alternativa o complemento del tratamiento, fundamentalmente por neoplasias de cuello, útero y próstata.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado como disminución de sangrado rectal,contribuye a la desaparición de los neovasos, con una adecuada reepitelización, disminuye el riesgo de complicaciones; y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como: inflamación o infección del periné, incontinencia a gases y heces, estenosis del ano, dolor post quirúrgico.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyHistectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('HISTERECTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-053', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La histerectomía consiste en la extirpación del útero o matriz. Puede ser realizada por vía abdominal o por vía vaginal, con o sin cuello uterino (total o subtotal) y puede llevar asociada la extirpación de los anexos (ovarios y trompas) unilateral o bilateralmente según la edad, patologías asociadas y criterio médico en el momento de la intervención. La histerectomía está indicada principalmente como tratamiento en patologías uterinas sintomáticas como miomas, sangrado uterino anormal persistente, prolapsos o descensos genitales (vía vaginal) y en algunos estadios de cáncer en el tracto genital o en entidades ginecológicas con riesgo de ello.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Alivio o disminución de síntomas que presente actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
Dentro de los riesgos específicos también se encuentra:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Fístulas vesico-vaginales e intestinales (comunicaciones anormales entre vejiga y vagina o intestino y vagina).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Descenso o prolapso de la cúpula vaginal.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Eventraciones y/o evisceraciones posquirúrgicas.'));
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al especialista.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyTraqueostomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('TRAQUEOSTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-024', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Una traqueostomía es una abertura en frente del cuello que se hace bien sea durante un procedimiento de emergencia o en una cirugía planeada, la traqueotomía es aconsejable en pacientes intubados con previsión de permanecer bajo ventilación mecánica por tiempo prolongado. Durante un procedimiento de traqueostomía se hace una abertura en la tráquea, se considera un procedimiento "percutáneo", lo que significa que se puede hacer sin necesidad de una cirugía abierta; se inserta un tubo (cánula) en la tráquea a través de la abertura y la persona respira a travésde esa cánula.
Una traqueostomía puede ser temporal, se podría usar cuando hay una obstrucción o una lesión en la tráquea. También se puede usar cuando una persona necesita un respirador (ventilador), como en el caso de neumonía grave, un ataque cardíaco mayor o un accidente cerebrovascular; o puede ser permanente se podría necesitar cuando es necesario extirpar parte de la tráquea debido a una enfermedad como el cáncer.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Otros beneficios específicos esperados de la realización de este procedimiento son: son el alta precoz de la unidad de cuidados intensivos y la reducción del tiempo de ingreso hospitalario'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables, tanto los comunes derivados de toda intervención y que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento como: infección o sangrado de la estoma, desviación de la cánula, que se vaya a otro espacio, enfisema subcutáneo.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyLegradoGinecologico($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('DE LEGRADO GINECOLÓGICO'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-047', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El legrado es una intervención quirúrgica que consiste en la toma de material endometrial y endocervical para su estudio anatomopatológico, en los casos en que haya existido metrorragia o hemorragia uterina anormal, pudiendo tener valor terapéutico aliviando un sangrado funcional copioso y/o extirpando un pólipo o mioma endometrial.
Sólo se puede realizar por vía vaginal y precisa de anestesia que será administrada conforme lo considere el especialista.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Favorece una mejor contracción de la fibra uterina, disminuir y prevenir las hemorragias y las infecciones.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Como procedimiento quirúrgico que es, el legrado diagnóstico lleva implícitas, tanto por la propia técnica como por la situación vital de cada paciente, una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto comunes como quirúrgicos.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyBiopsiaMamaAnclaje($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('BIOPSIA DE MAMA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-055', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Mediante este procedimiento se extirpa la lesión comprobando con radiografías la resección parcial o completa, evitando su crecimiento. En algunos casos el estudio anatomopatológico intra o postoperatorio puede indicar la necesidad de ampliar la resección a los ganglios de la axila y/o aparte o a toda la mama.
Cabe la posibilidad de que durante la cirugía haya que realizar modificaciones del procedimiento por los hallazgos intraoperatorios para proporcionar el tratamiento más adecuado. La intervención requiere la administración de anestesia.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Extirpar la lesión de la mama para un estudio anatomopatológico completo, evitando su crecimiento.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:
A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables, tanto los comunes derivados de toda intervención y que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento: Infección o sangrado de la herida quirúrgica, colección de líquido en la cicatriz, flebitis, edema transitorio del brazo, cicatrices retráctiles, disminución del volumen de la mama, deformidad de la misma y dolor prolongado en la zona de la operación.
Pueden darse riesgos poco frecuentes, aunque graves, tales como: inflamación grave de los linfáticos del brazo, reproducción de la enfermedad y sangrado importante. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero pueden llegar a requerir una reintervención, incluyendo un riesgo mínimo de mortalidad. Según el tipo y tamaño de la lesión quedará una cicatriz más o menos amplia'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('No existen alternativas que permitan conocer con certeza el diagnóstico de la lesión.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud Al no poder conocer el diagnóstico de la lesión con certeza se está expuesta a que la enfermedad evolucione si es maligna a estadíos de mayor riesgo para la vida y a soluciones terapéuticas posteriores más complejas'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al mastólogo tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyTubectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('TUBECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-051', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La esterilización quirúrgica consiste en la obstrucción o extirpación de una parte o toda la trompa de Falopio, lo cual se realiza de forma bilateral. Se trata de una intervención en principio pensada como esterilización definitiva, aunque es posible en ocasiones la reversibilidad quirúrgica y, en todo caso, un futuro embarazo mediante fecundación in vitro.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas o la solución del motivo por el cual ha consultado, se evita el uso de anticonceptivos hormonales, se evita el uso de preservativo en cada acto sexual, 99% de efectividad para evitar el embarazo, se puede realizar en el mismo momento del parto, si este es por cesárea, es una operación sencilla que no requiere de hospitalización y, por ende, mejoramiento del estado de salud y calidad de vida.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Otras complicaciones, infección de sitio operatorio, muerte. Existen otros riesgos como: daños en los intestinos, la vejiga o vasos sanguíneos importantes. Reacción a la anestesia. Curación inadecuada de la herida o infección, Cierre incompleto de las trompas, lo cual podría aún hacer posible que se presente un embarazo. Aproximadamente 1 de cada 200 mujeres que ha tenido ligadura de trompas queda en embarazo posteriormente.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Existen alternativas como los siguientes métodos de planificación:'),0,'J');
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos orales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de barrera (condón y diafragma).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos de Aplicación Intramuscular mensuales o trimestrales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Contraceptivos intrauterinos.'));
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al mastólogo tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCirugiaLamparoscopicaGinecologica($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIRUGIA LAPAROSCOPICA GINECOLOGICA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-044', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La laparoscopia es una técnica quirúrgica que utiliza una óptica introducida en el abdomen a través de una pequeña incisión periumbilical. Se emplea una segunda, tercera y opcionalmente cuarta incisión para la introducción de tijeras, pinzas de coagulación y otros instrumentos durante su práctica. Se practica con anestesia general, y consiste en examinar órganos pélvicos, mediante el uso de un instrumento de visualización llamado laparoscopio. La cirugía también se usa para tratar ciertas enfermedades de los órganos pélvicos'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La recuperación suele ser muy rápida, pudiendo abandonar la hospitalización en horas (a menudo menos de 24 horas) y realizar vida normal en unos días.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Otras complicaciones, infección de sitio operatorio, muerte. Existen otros riesgos como:________________________________________________________________________________________________________________'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCistectomiaViaAbdominal($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CISTECTOMIA POR VIA ABDOMINAL'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-045', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La cistectomía es una cirugía que se realiza para extraer la vejiga. En los hombres, la extracción completa de la vejiga (cistectomía radical) generalmente consiste en extirpar la próstata y las vesículas seminales. En las mujeres, la cistectomía radical implica extirpar toda la vejiga y los ganglios linfáticos. Después de extraer la vejiga, el cirujano debe crear una derivación urinaria: una nueva forma de almacenar orina y permitir que se elimine del cuerpo. Hay varios métodos para almacenar y eliminar la orina después de la extracción de la vejiga.

La cistectomía también se puede realizar para tratar otros tumores pélvicos, como cáncer de colon, de próstata o de endometrio avanzado, y algunas afecciones no cancerosas (benignas), como cistitis intersticial o anomalías congénitas.

La técnica consiste en una cirugía ya sea abierta o por laparotomía o laparoscópica para acceder a la pelvis y la vejiga'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Mejorar la calidad de vida del paciente. Aliviar o disminuir los síntomas que padece actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Otras complicaciones, infección de sitio operatorio, muerte.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyLavadoDesbridamientoFracturas($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('LAVADO Y/O DESBRIDAMIENTO DE FRACTURAS'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-038', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El desbridamiento es la clave de la prevención de la infección en una fractura. Consiste en la extirpación quirúrgica del tejido desvitalizado, contaminado o muerto de una herida. Las heridas pueden estar contaminadas por tres materiales: cuerpos extraños y materia orgánica, bacterias y tejidos desvitalizados del propio paciente. El desbridamiento debe incluir la limpieza de la contaminación macroscópica, una reducción en el contaje de bacterias y la remoción de todo el tejido necrótico. Debido a que la contaminación bacteriana está presente en todas las heridas, es imperativo efectuar el desbridamiento tan pronto como sea posible para prevenir la colonización de la herida.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Reducir el riesgo de infección y evitar complicaciones futuras, eliminando tejido muerto que provoca más dolor e infección.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyMeniscectomiaArtoscopica($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('MENISCECTOMIA ARTROSCOPICA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-040', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La intervención consiste en la introducción por punción dentro de la articulación de un sistema de lentes conectado a una cámara de televisión que permita la visualización de las estructuras intraarticulares. Por otro acceso de similares características se introducen los instrumentos que permiten la extirpación y regularización de los meniscos.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Tras la intervención suelen desaparecer las crisis de bloqueos de esa rodilla, así como los derrames de la articulación. El dolor producido por el atrapamiento del menisco lesionado mejora considerablemente tras la meniscectomía, si no existen lesiones asociadas.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como: Dolor residual en los portales de entrada, cicatrices. En pacientes con lesiones degenerativas pueden quedar molestias residuales que pueden obligar al paciente modificar su actividad, trombosis venosa profunda y eventualmente tromboembolismo pulmonar de graves consecuencias, infección de la articulación que requerirá lavado artroscópico y tratamiento con antibiótico o eventualmente artrotomía, rigidez articular que requerir movilización bajo anestesia y/o sección de adherencias bajo control artroscópico o artrotomía, lesión vascular que puede llevar a la amputación del miembro o eventualmente a la muerte, lesión nerviosa por afectación de los nervios adyacentes que puede llevar a lesiones neurológicas irreversibles, síndrome compartimental, algodistrofia simpático- refleja.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCirugiaAmputacioneMenor($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIRUGÍA PARA AMPUTACIÓN MENOR Y DESBRIDAMIENTOS'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-035', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Se ha estudiado los síntomas que usted padece y realizado las exploraciones complementarias oportunas, encontrando un déficit de circulación y/o infección muy grave de su extremidad. El deterioro de las arterias es tan importante que no es posible en la actualidad aplicarle ningún tratamiento, ya sea médico o quirúrgico para conseguir llevar sangre al pie/mano. La amputación o desbridamiento será de las partes de su extremidad que se consideren no recuperables.

Cabe la posibilidad de que durante la cirugía haya que realizar modificaciones del procedimiento por los hallazgos intraoperatorios para proporcionar el tratamiento más adecuado o aumentar el alcance de la amputación.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios que se esperan son pensando siempre en una rehabilitación que le permita llevar una vida lo más normal posible y evitar complicaciones en su estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado por la zona de amputación, infección o necrosis de los tejidos vecinos, dehiscencia (no cicatrización) del muñón de amputación, sensación del miembro fantasma (sentir la zona amputada). No hay que descartar que debido a la enfermedad que usted padece, se pueda producir un defecto de cicatrización que obligue a realizar una amputación más alta.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Cómo usted sabe, usted sabe la arteriosclerosis es una enfermedad generalizada, por lo que todos sus órganos la padecen, por lo tanto puede ocurrir que tenga un infarto de miocardio, neumonía, insuficiencia respiratoria, insuficiencia renal, descompensación de la diabetes si la padece, isquemia cerebral.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hay riesgos menos frecuentas que pueden producirse: flebitis, escaras de decúbito, etc. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero puede llegar requerir una reintervención, en ocasiones de urgencia, incluyendo un riesgo de mortalidad.'));
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyradicalLamparoscopia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('PROSTATECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-031', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La prostatectomía radical es el procedimiento quirúrgico mediante el cual se pretende remover completamente su próstata y vesículas seminales, como tratamiento con intervención curativa de un tumor maligno de la próstata. En el mismo procedimiento puede requerirse o no, de la extirpación de los ganglios linfáticos del área pélvica con el propósito de clasificar la enfermedad.
Es una intervención quirúrgica que se lleva a cabo por una incisión (herida) en la parte baja del abdomen. Por esta herida se procede a remover completamente la próstata en conjunto con las vesículas seminales que la acompañan. En el mismo acto quirúrgico se puede llegar a requerir de la remoción de los ganglios linfáticos del área vecina a la próstata con el propósito de clasificar la enfermedad (linfadenectomía). Una vez removida la próstata, se debe reconstruir el paso de la orina por la vía urinaria inferior, uniendo la vejiga con la uretra por medio de suturas.
Posterior a la intervención quirúrgica saldrá de cirugía con una sonda por la uretra (conducto urinario) y un dren en la parte baja del abdomen. Estará recibiendo líquidos y medicación por vía venosa, subcutánea y oral en los siguientes días posteriores a la intervención. La sonda se conservará hasta tanto se completa la cicatrización de la unión entre la uretra y la vejiga, tiempo aproximado de 1 a 2 semanas, el dren se retira cuando el líquido que sale por él sea escaso.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Eliminar la próstata enferma, y así evitar la propagación a planos más profundos para evitar extensión de la enfermedad.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección del caso, así como de la vía por la cual se realiza el procedimiento, se pueden presentar efectos indeseables, tanto derivados del procedimiento quirúrgico mismo, como de complicaciones de otros órganos o sistemas del organismo, como los debidos a condiciones propias de cada paciente, por ejemplo. (Diabetes, hipertensión arterial, enfermedad vascular arterial, obesidad o complicaciones venosas).'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Linfoceles, infecciones urinarias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Incontinencia urinaria ya que es un riesgo de alta probabilidad de ocurrencia al igual que la disfunción eréctil.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('márgenes positivos, progresión de la enfermedad aun después de haberse sometido al procedimiento quirúrgico.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Las complicaciones propias de este procedimiento quirúrgico pueden ser: imposibilidad de remover la próstata debido a los hallazgos de la cirugía o apertura del recto que requiera de reparo y eventual colostomía (exteriorización del intestino). Sangrado intraoperatorio que requiera de transfusiones o complicaciones de la herida quirúrgica como son las infecciones, mala cicatrización con defectos estéticos de piel, presencia de hernias a nivel de la herida o eventración que es la salida de contenido intestinal por la herida quirúrgica. Tromboembolismo pulmonar, incontinencia urinaria.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Disfunción eréctil, imposibilidad para lograr una erección adecuada que permita penetración.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Estrechez de la unión entre cuello vesical y la uretra. Estas complicaciones son tratables, pero pueden requerir de otras medidas médicas, administración de líquidos y productos sanguíneos por vía venosa, medicamentos y llevar a hospitalizaciones prolongadas hasta tanto se resuelvan y puedan manejar de forma ambulatoria. En algunos casos se puede requerir de una o varias nuevas intervenciones quirúrgicas para lograr resolver las complicaciones.'));
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyUreterolitotomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('URETEROLITOTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-030', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Esta operación se realiza por vía endoscópica, consiste en introducir por la uretra (conducto por donde sale la orina) un equipo especial llamado ureteroscopio, este equipo se avanza hacia el uréter, observando todo el proceso en una pantalla, hasta identificar el cálculo. Una vez se identifica el cálculo se evalúa la forma de extracción (sacarlo), la cual se realiza con unas canastillas o pinzas especialmente diseñadas para este fin; en ocasiones el cálculo es muy grande y debe ser fragmentado antes de su extracción, para esto se usan equipos especiales como el litotriptor endoscópico o el láser, los cuales parten el cálculo en porciones más pequeñas, que permiten extraerlo. En ocasiones se debe dejar dentro del uréter un pequeño catéter o tubito (doble jota), el cual se debe retirar días o semanas luego de la cirugía; también en ocasiones se deja luego del procedimiento una sonda (manguerita) en la vejiga la cual se retira horas o días después del procedimiento. La duración de su hospitalización es variable, decidida por su cirujano en función de su estado de salud y del desarrollo de la cirugía.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Mejorar el paso o salida de la orina desde el riñón hacia la vejiga, sacando el o los cálculos que se encuentren y así aliviar el dolor y las complicaciones que se pueden presentar con la presencia del cálculo.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección del caso, así como de la vía por la cual se realiza el procedimiento, se pueden presentar efectos indeseables, tanto derivados del procedimiento quirúrgico mismo, como de complicaciones de otros órganos o sistemas del organismo, como los debidos a condiciones propias de cada paciente, por ejemplo. (Diabetes, hipertensión arterial, enfermedad vascular arterial, obesidad o complicaciones venosas).'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En los casos en los que se han realizado previamente cirugías en el uréter, el procedimiento puede ser más complejo y tiene mayor riesgo de complicaciones. Entre estas complicaciones están: sangrado, infecciones.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('No poder extraer el cálculo, el urólogo intentará derivar la vía urinaria con un catéter doble J, para disminuir el dolor y proteger la función del riñón, en caso de no ser posible se requerirá de una nefrostomía (catéter que va del riñón a la piel).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesiones del uréter'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Perforación vesical.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Estrechez del uréter o problemas renales derivados de enfermedad del uréter.'));
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyVasectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('VASECTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-033', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El procedimiento consiste en cortar y/o ligar los conductos deferentes por donde pasan los espermatozoides desde los testículos para salir en la eyaculación a través de la uretra.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reacciones adversas a medicamentos que incluyen reacciones alérgicas/anafilácticas.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado, dolor, infección, reintervención, hematomas que requiere procedimientos quirúrgicos'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor crónico (dolor que persiste en el tiempo que puede ser incluso de gran intensidad).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Recanalización tempranas o tardías (recuperar la fertilidad de manera espontánea).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Fugas de líquido seminal que pueden comprometer la piel, perdida de uno o ambos testículos.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Perdida parcial o total anatómica o funcional de órgano o tejido, muerte.'));
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReseccionTransurtralProstata($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('RESECCIÓN TRANSURETRAL DE PRÓSTATA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-032', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La resección transuretral de la próstata es una cirugía utilizada para tratar problemas urinarios causados por el agrandamiento de la próstata. Se utiliza un instrumento llamado receptor, el cual se introduce por la uretra hacia la vejiga (procedimiento endoscópico). Una vez dentro de la vejiga se realiza un examen visual de toda la vejiga para posteriormente proceder a la resección o extirpación poco a poco del tejido prostático obstructivo, utilizando energía eléctrica que corta y cauteriza el tejido.

Al final se evacúan los fragmentos de próstata resecados para su análisis histopatológico y se coloca una sonda vesical con un sistema de lavado con suero fisiológico continuo para prevenir la formación de coágulos.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Eliminar la obstrucción del tracto urinario para aliviar signos y síntomas que usted presenta actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado vesical (hematuria), la cual puede persistir durante un par de semanas después de la cirugía, en ocasiones la formación de coágulos obliga a un sondaje vesical y posterior evacuación de coágulos.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('infección urinaria que precisan de una pauta corta de antibióticos o sangrado importante que precisa de transfusiones.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado tardío, lesión ureteral.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Continuar con disfunción miccional.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Estrechez de uretral.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Incontinencia urinaria.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En casos muy raros disfunción eréctil.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Fistulas/fugas de orina comunicación con recto, colostomía, fistulas o fugas a piel.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Rupturas vesicales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Convertir a cirugía abierta.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tejido residual obstructivo que requiere segundo tiempo quirúrgico.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Trastornos hidroelectrolíticos.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Muerte.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyAmigdalectomiaAdenoidectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('AMIGDALECTOMÍA Y ADENOIDECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-106', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Las amígdalas y adenoides son masas de tejido linfoide que tienen la función de proteger al organismo de infecciones víricas o bacterianas. Igualmente pueden ser dañadas por microorganismos o crecer en exceso produciendo diversas alteraciones. Las amígdalas están situadas a ambos lados de la cavidad bucal, detrás de la lengua y suelen resultar visibles por la boca. Las adenoides están localizadas en la parte alta de la garganta, detrás de la nariz.
Llamamos amigdalectomía a la extirpación de las amígdalas y es necesaria cuando los episodios de amigdalitis sean tan frecuentes o graves que afecten su estado general de salud.
La adenoidectomía es la extirpación de las adenoides y está indicada en cuadros de infección adenoidea, que, aún sin dificultad respiratoria marcada, tenga repercusión ótica repetida o persistente.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Se pretende con este procedimiento mejorar la respiración, disminuir las infecciones de vías respiratorias altas, mejorar la audición y la deglución.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor que puede ser razonablemente controlado con medicación. Es frecuente su irradiación a los oídos.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En ocasiones se puede romper o perder alguna pieza dentaria.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('La hemorragia puede aparecer durante las dos primeras semanas tras la cirugía, aunque es más frecuente en el postoperatorio inmediato.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En raras ocasiones se precisa una transfusión sanguínea.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('A causa de que la deglución es dolorosa tras la cirugía, puede haber un escaso aporte de líquidos que precise tratamiento.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('lesiones a estructuras vecinas como cerebrales/oculares.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reintervenciones, nuevas y futuras cirugías o no mejoría clínica por enfermedades de base como alergias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reintervenciones, nuevas y futuras cirugías o no mejoría clínica por enfermedades de base como alergias'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones de tipo vasculares, las cuales pueden ser fatales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones, infección de sitio operatorio, muerte.'));


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a otras alternativas terapéuticas, generalmente farmacológicas, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyNefrectomiaRadicalAbierta($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('NEFRECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-029', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La operación pretende extraer el riñón y la grasa que lo rodea, además (según el caso), la glándula suprarrenal o los ganglios linfáticos cercanos. Se realiza por una incisión (cortada) de la piel, la ubicación de la incisión puede variar según el tamaño de la masa renal. La cirugía se realiza bajo anestesia general y amerita hospitalización para vigilancia postoperatoria. En algunos casos al final de la operación, se deja un dren (manguerita) que sale del abdomen y sirve para extraer el líquido sobrante, este dren se retira según su evolución e indicación de su cirujano, generalmente antes del alta.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Controlar la enfermedad, evitar los síntomas secundarios a la presencia de la masa renal y evitar la progresión de la enfermedad.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de las precauciones, todo procedimiento puede tener cierto porcentaje de complicaciones y riesgos, que dependen de la enfermedad que usted sufre y de las variaciones de cada paciente. Durante esta operación el urólogo puede verse enfrentado con situaciones o hallazgos no esperados, que hagan necesario procedimientos complementarios o diferentes de aquellos inicialmente planeados.'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En los casos que la indicación sea oncológica recidiva tumoral, márgenes de resección positivos, hernias incursiónales, fistulas/fugas intestinales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Entre estas complicaciones están: hematomas, hemorragia o sangrado que puede requerir transfusión u otra cirugía.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('No lograr sacar el tumor por dificultades intraoperatorias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión órganos (grandes vasos, duodeno, hígado, intestino, pulmón, bazo).'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Falla renal que amerite manejos adicionales o diálisis temporal o indefinida,.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infecciones o sepsis generalizada e incluso, la muerte.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a otras alternativas terapéuticas, generalmente farmacológicas, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyExtraccionCatterViaEndoscopica($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('EXTRACCIÓN DE CATÉTER VÍA ENDOSCOPICA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-028', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los catéteres doble J pielovesicales se emplean con cierta frecuencia en variadas situaciones clínicas en el campo de la urología. Cuando se utilizan en enfermedad benigna (tipo litiasis y/o postlitotricia), es habitual que, tras concluida su función, haya que extraerlos. La técnica de elección para su extracción es la vía endoscópica transuretral. Generalmente la extracción del catéter se hace de forma ambulatoria a través de una cistoscopia donde se inserta en la uretra un tubo hueco (cistoscopio) que tiene una lente y se lo desplaza lentamente hacia la vejiga.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Diagnosticar, controlar y tratar las afecciones que afectan la vejiga y la uretra, de manera oportuna para aliviar signos y síntomas que usted presenta actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección: En raras ocasiones, la cistoscopia puede introducir gérmenes en las vías urinarias, lo que puede causar una infección.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Traumatismo de la uretra o la vejiga, estrechez de uretra, no identificación de lesiones.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado: La cistoscopia puede hacer que orines con un poco de sangre. Los casos de sangrado grave son muy poco frecuentes.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor: Después del procedimiento, podrías tener dolor abdominal y una sensación de ardor al orinar. Por lo general, los síntomas son leves y desaparecen progresivamente después del procedimiento.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a otras alternativas terapéuticas, generalmente farmacológicas, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCistoscoipa($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CISTOSCOPIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-026', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La cistoscopia es un procedimiento para examinar el revestimiento de la vejiga y la uretra. Se inserta en la uretra un tubo hueco (cistoscopio) que tiene una lente y se lo desplaza lentamente hacia la vejiga. Usualmente se utiliza anestesia local, es un procedimiento ambulatorio.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Diagnosticar, controlar y tratar las afecciones que afectan la vejiga y la uretra, de manera oportuna para aliviar signos y síntomas que usted presenta actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección: En raras ocasiones, la cistoscopia puede introducir gérmenes en las vías urinarias, lo que puede causar una infección.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Traumatismo de la uretra o la vejiga, estrechez de uretra, no identificación de lesiones.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sangrado: La cistoscopia puede hacer que orines con un poco de sangre. Los casos de sangrado grave son muy poco frecuentes.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor: Después del procedimiento, podrías tener dolor abdominal y una sensación de ardor al orinar. Por lo general, los síntomas son leves y desaparecen progresivamente después del procedimiento.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyTurbinoplastia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('TURBINOPLASTIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-025', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los cornetes son estructuras que se ubican en las paredes laterales del interior de la nariz. Están compuestos de hueso esponjoso y mucosa y se extienden en ambos conductos nasales como un tejido en forma de cresta. Su función es hidratar y templar el aire que entra a través de las fosas nasales. Las alergias u otros problemas nasales pueden provocar que los cornetes se hinchen y bloqueen el flujo de aire, produciendo obstrucción nasal, congestión y picazón nasales.

La turbinoplastia es un procedimiento que consiste en la reducción de los cornetes, se realiza utilizando una fina fibra óptica rígida que se introduce a través de las fosas nasales. Una vez se localiza el lugar exacto de la apertura de los senos nasales, se procede a la resección del tejido que produce la obstrucción. Finalmente se lleva a cabo un control del sangrado y, en algunos casos, se coloca un taponamiento nasal o láminas de silicona para evitar la adherencia de las paredes y las hemorragias.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Mejoría de la respiración, alivio de síntomas.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias (cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos específicos como: '),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor nasal los primeros días tras la cirugía'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hemorragia.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sequedad nasal.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección de sitio operatorio..'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('esiones a estructuras vecinas como cerebrales/oculares, lesiones vasculares las cuales pueden ser fatales. Así como reintervenciones, nuevas y futuras cirugías o no mejoría clínica  por enfermedades de base como alergias.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('Los riesgos vitales, son poco frecuentes, aunque como en todo acto médico y teniendo en cuenta la necesidad de anestesia, en todos los casos, pueden producirse. Estos riesgos vitales, tanto durante como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCirugiaSafenoVaricectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIRUGÍA SAFENO VARICECTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-034', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Las venas varicosas son venas retorcidas y agrandadas que aparecen más comúnmente en las piernas y los pies. En las venas varicosas, las válvulas están dañadas o ausentes, esto provoca que las venas resulten llenas de sangre, especialmente cuando uno está de pie. Estas venas pueden aparecer como abultadas y retorcidas con un color morado oscuro o azul.
La técnica tradicional para la extracción quirúrgica es la ligadura y extracción de la vena safena mayor, la cual es la vena larga que se extiende desde el tobillo a lo largo del interior de la pierna y el muslo y desemboca en la vena femoral en la ingle y/o la safena menor o externa. La ligadura y extirpación implican atar la vena dañada (ligadura) y eliminarla físicamente (extirpación),
Se realiza una incisión a nivel inguinal (en el caso de la interna o mayor) y/o hueco poplíteo (safena menor o externa) se elimina la vena safena enferma, sus tributarias y concomitantemente la ligadura de las venas perforantes, detectadas insuficientes y que tiene mucho que ver con la recidiva varicosa.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Producir efectos beneficiosos al reducir el volumen venoso en la extremidad y de ese modo los efectos nocivos de la hipertensión venosa sobre los tejidos cutáneos.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Durante la extracción, las otras venas unidas a la vena safena mayor se rompen, lo que hace que la sangre se vaya a los tejidos circundantes. Debido a esto los pacientes pueden experimentar dolor postoperatorio y los moretones. Otras posibles complicaciones incluyen entumecimiento, dolor crónico, pérdida funcional por daño a los nervios adyacentes, hinchazón crónica de las piernas por tejido linfático dañado, cicatrices de incisión y reacción alérgica a la anestesia.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión del sistema vascular femoral, con posible isquemia y perdida del miembro.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Trombosis venosa profunda, tromboembolismo pulmonar.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('La muerte, entre otros.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodySeptoplastia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('SEPTOPLASTIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-023', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Usted padece una deformación en el tabique que separa los dos lados de la nariz. Esta intervención consiste en corregir la deformidad del tabique se puede realizar con anestesia general o local. La intervención se realiza por dentro de la nariz, por lo que no quedarán cicatrices visibles. Después de la intervención llevará la nariz taponada completamente por un tiempo. En algunas ocasiones se colocan unos plásticos. Es normal notar algunas molestias como sequedad nasal o formación de costras.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Mejoría de la obstrucción nasal, en caso de éxito de la intervención.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor nasal: los primeros días tras la cirugía.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hemorragia: que puede requerir transfusión sanguínea.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sequedad nasal.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('Los riesgos vitales, son poco frecuentes, aunque como en todo acto médico y teniendo en cuenta la necesidad de anestesia, pueden producirse. Estos riesgos vitales, tanto durante como postoperatoriamente, son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Los riesgos más frecuentes son:'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCirugiaEndoscopicaSenosParanasales($pdf)
    {

        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIRUGÍA DE SENOS PARANASALES'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-021', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Existen cuatro pares de senos paranasales, son cavidades abiertas dentro de varios de los huesos que rodean la nariz y las órbitas, ayudan en el calentamiento y humidificación del aire inspirado y drenan las secreciones de moco en las cavidades nasales.

La cirugía endoscópica de senos paranasales es el procedimiento que se realiza para corregir la anatomía y el funcionamiento de estas cavidades, principalmente cuando presentan afecciones como sinusitis crónica o poliposis nasal y presencia de masas benignas o malignas o de alteraciones anatómicas predisponentes de infecciones repetidas manifestando síntomas como: inflamación nasal, secreción espesa y descolorida de la nariz, secreción por la parte posterior de la garganta, obstrucción o congestión nasal, que dificultad para respirar por la nariz, dolor, sensibilidad e inflamación alrededor de los ojos, las mejillas, la nariz o la frente que no responden al tratamiento farmacológico.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Estos procedimientos son esencialmente funcionales, es decir buscan mejorar la respiración por la nariz y eliminar los síntomas.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor nasal: los primeros días tras la cirugía.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Hemorragia: que puede requerir transfusión sanguínea.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Sequedad nasal.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesiones a estructuras vecinas como cerebrales / oculares.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesiones vasculares las cuales pueden ser fatales.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reintervenciones, nuevas y futuras cirugías.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('No mejoría clínica por enfermedades de base como alergias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones, infección de sitio operatorio, muerte.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('Los riesgos vitales, son poco frecuentes, aunque como en todo acto médico y teniendo en cuenta la necesidad de anestesia, pueden producirse. Estos riesgos vitales, tanto durante como postoperatoriamente, son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Los riesgos más frecuentes son:'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyRealizacionHemorroidectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('DE HEMORROIDECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-062', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La hemorroidectomía, se realiza para el tratamiento de hemorroides que producen hemorragia, prolapso, prurito y/o dolor, por ende, la cirugía consiste básicamente en la extirpación de las venas próximas al ano (hemorroides).'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como: infección del periné, incontinencia a gases y heces, estenosis del ano.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCierreEstoma($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIERRE DE ESTOMA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-065', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Las ostomías son exteriorizaciones de vísceras abdominales, generalmente intestino, hacia el exterior y a través de la piel de la pared abdominal. Para cerrar una ostomía es necesario realizar una incisión de los tejidos alrededor de ella se encuentra y reestablecer la continuidad de la víscera exteriorizada mediante suturas, con su posterior introducción a la cavidad y cierre del defecto de la pared donde antes se encontraba, bien sea con suturas o de ser necesario con la interposición de un parche conocido con el nombre de malla. Lo que se logra con la cirugía en cuestión es reestablecer la continuidad del intestino para lograr que el paciente defeque por vía anal, como antes de su ostomía lo hacía.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La restauración de la continuidad del tubo digestivo con el fin de permitir que se pueda defecar normalmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos específicos del procedimiento como: Infección o sangrado de la herida quirúrgica, flebitis, retención aguda de orina, diarrea, alteraciones de la continencia fecal que habitualmente ceden tras un periodo de adaptación, retraso en la recuperación de la Motilidad intestinal, que puede requerir tratamiento prolongado con sueros y dolor prolongado en la zona de la operación. La complicación más compleja de manejar en este procedimiento es la filtración de la sutura intestinal con lo que el paciente puede hacer peritonitis, abscesos o infecciones de los tejidos de la pared que requieren la realización de una nueva colostomía para su control.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('No existe ningún otro método para realizar el cierre de estoma.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Dependiendo del tipo de estoma puede haber riesgo de alteraciones metabólicas a largo plazo si se mantiene sin cerrar largo tiempo.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyMenisectommiaArtroscopica($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('MENISECTOMIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-063', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La intervención consiste en la introducción por punción dentro de la articulación, de un sistema de lentes conectado a una cámara de televisión que permita la visualización de las estructuras intraarticulares. Por otro acceso de similares características se introducen los instrumentos que permiten la extirpación y regularización de los meniscos.

El propósito principal de la intervención consiste en la extirpación o regulación del menisco lesionado para evitar la pérdida de estabilidad que se produce al quedar atrapadas partes del menisco entre el fémur y la tibia durante el movimiento de articulación, valorando el grado de lesiones degenerativas y la posible regulación de estas.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');

        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor residual en los portales de entrada (cicatrices). En pacientes con lesiones degenerativas pueden quedar molestias residuales que pueden obligar al paciente modificar su actividad.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Trombosis venosa profunda y eventualmente tromboembolismo pulmonar de graves consecuencias.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Infección de la articulación que requerirá lavado artroscópico y tratamiento con antibiótico o eventualmente artrotomía.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Rigidez articular que requerir movilización bajo anestesia y/o sección de adherencias bajo control artroscópico o artrotomía.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión vascular que puede llevar a la amputación del miembro o eventualmente a la muerte.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Lesión nerviosa por afectación de los nervios adyacentes que puede llevar a lesiones neurológicas irreversibles.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Síndrome compartimental.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Algodistrofia simpático-refleja.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyReseccionTumorRectal($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('RESECCION DE TUMOR RECTAL'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-064', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La Resección de Tumor Rectal se realiza, como tratamiento para cáncer de colon, con el fin de extraer una masa, por ende, la cirugía consiste básicamente en un corte a través de cierta capa del recto para extirpar el tumor canceroso, así como algo del tejido circundante. Luego se cierra el orificio en la pared rectal.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). También se me informa la posibilidad de complicaciones severas como pelviperitonitis, choque hemorrágico o trombosis que, aunque son poco frecuentes, representan como en toda intervención quirúrgica un riesgo excepcional de perder la vida derivado del acto quirúrgico o de la situación vital de cada paciente. Existen otros riesgos como:_______________________________________________________________________________ ______________________________________________________________________________________________________________________.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyHernirrafia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('HERNIORRAFIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-089', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Una hernia es un defecto de tejido por un punto débil en la pared muscular abdominal por el que protruyen (salen) estructuras contenidas dentro del abdomen, como órganos y tejidos que al salirse (herniarse) producen una protuberancia. La herniorrafia es un procedimiento busca reparar el defecto de la pared abdominal evitando su aumento progresivo y eliminando el riesgo de estrangulación que obligaría a una cirugía urgente. En esta reparación, el tejido que se sale, se empuja de nuevo hacia adentro. La pared abdominal se fortalece y se sostiene con suturas o con especies de parches conocidos con el nombre de mallas. Es probable que se requiera de la extracción de tejidos o incluso porciones de vísceras que por su condición, no puedan ser reintroducidos al abdomen.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Permitir que el paciente siga realizando sus actividades cotidianas. La desaparición de las molestias abdominales (dolor, dificultad de tránsito intestinal, abombamiento de parte del abdomen) al mismo tiempo que se recupera total o parcialmente la forma y anatomía de la pared abdominal.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como rechazo de la sutura utilizada, reproducción de la hernia, e incluso adherencia de vísceras con la probabilidad de que se rompan con el tiempo generando infecciones crónicas que demanden de otras intervenciones. En personas obesas o con problemas pulmonares pueden presentarse o agravarse enfermedades respiratorias.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('No existe ningún otro método para realizar la reparación de la hernia.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de no realizarse la intervención, probablemente la hernia siga aumentando de tamaño, con una mayor dificultad en el tránsito intestinal, aumento de molestias dolorosas, mayor dificultad para realizar su actividad habitual y posibilidad de sufrir episodios de encarcelación o estrangulación.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyAmigdalectomia($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('AMIGDALECTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-022', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Las amígdalas son masas de tejido linfoide que tienen la función de proteger al organismo de infecciones víricas o bacterianas. Igualmente pueden ser dañadas por microorganismos o crecer en exceso produciendo diversas alteraciones. Esta función inmunitaria se desarrolla en los primeros años de vida, pero es menos importante a medida que el niño crece. Las amígdalas están situadas a ambos lados de la cavidad bucal, detrás de la lengua y suelen resultar visibles por la boca.

Llamamos amigdalectomía a la extirpación de las amígdalas y se recurre a este procedimiento cuando los episodios de amigdalitis sean tan frecuentes o graves que afecten su estado general de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Otros beneficios específicos esperados de la realización de este procedimiento son: ________________________________________________________________________________________________________________________________________________________________________________________.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->Ln();
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dolor que puede ser razonablemente controlado con medicación. Es frecuente su irradiación a los oídos.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En ocasiones se puede romper o perder alguna pieza dentaria.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('La hemorragia puede aparecer durante las dos primeras semanas tras la cirugía, aunque es más frecuente en el postoperatorio inmediato.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('En raras ocasiones se precisa una transfusión sanguínea.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('A causa de que la deglución es dolorosa tras la cirugía, puede haber un escaso aporte de líquidos que precise tratamiento.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones, infección de sitio operatorio, muerte.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a alternativas terapéuticas sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyLampartomiaExploratoria($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('LAPAROTOMÍA EXPLORATORIA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-090', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La laparotomía exploratoria es una cirugía para observar el interior del abdomen. Por lo general, se realiza para buscar problemas que no fueron diagnosticados mediante otras pruebas. Se realiza una incisión en el abdomen, a partir de la cual observa los órganos y, si es necesario, se extrae una muestra de tejido (biopsia) para luego analizarla en el laboratorio. Es también posible que, dependiendo de los hallazgos se requiera la extracción de algunos órganos o porciones de ellos, así como la exteriorización de vísceras (especialmente intestino), y la colocación de drenes temporales o definitivos. De igual forma existe la posibilidad de que durante un tiempo el abdomen deba quedar abierto y cubierto con un sistema de drenaje especial, conocido con el nombre de sistema VAC, para la realización de una o varias reintervenciones programadas, antes del cierre definitivo. Después, el cirujano utilizará suturas para cerrar la incisión.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Realizar un diagnóstico rápido y oportuno para iniciar tratamiento requerido por el paciente. Reducir o aliviar los síntomas que lo aquejan actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos poco frecuentes como: hernias posoperatorias, cicatrices internas con el riesgo de obstrucción intestinal en cualquier momento de la vida, dolor crónico del sitio operatorio, reacciones a los materiales requeridos durante el procedimiento, entre otros, incluyendo la muerte.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La principal alternativa y avance a la laparotomía, es la laparoscopia, la cual es una técnica menos invasiva, que permite al especialista observar la zona mediante un sistema de cámara e iluminación, pero que según la indicación para el procedimiento no es siempre posible.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, habrá persistencia o agravamiento de los síntomas actuales o aparición de nuevos, progresión de la enfermedad con deterioro del paciente. Seguirá dependiendo de medicamentos para controlar los síntomas y se puede agravar su estado de salud incluso con tendencia hacia la muerte.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyColectomiaTotalReservorio($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('COLECTOMÍA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-087', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Una colectomía es la extracción parcial o completa del intestino grueso. Este procedimiento se requiere cuando los pacientes tienen cáncer, enfermedad diverticular compleja, obstrucciones del intestino con isquemia y /o perforación del mismo o finalmente enfermedades inflamatorias o infecciosas del intestino que no se pudieron controlar con manejo médico. Una vez resecado el segmento intestinal o la totalidad del mismo y, dependiendo de la causa por la que se realice el procedimiento, pueden unirse con sutura las dos porciones restantes de intestino o puede requerirse la exteriorización del mismo a través de un orificio en la piel para que de forma temporal o definitiva, el paciente quede defecando a un sistema de recolección que tiene forma de bolsa. Este procedimiento se conoce con el nombre de ostomía.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora del estado de salud, aliviando o disminuyendo los los síntomas que presenta actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos poco frecuentes como: Apertura de la herida, fístula (escape de líquido del conducto digestivo) de la sutura intestinal por alteración en la cicatrización que a veces precisa intervención con la realización de una ostomía, sangrado o infección intra abdominal, obstrucción intestinal, disfunciones sexuales que pueden suponer impotencia, alteración de la continencia a gases e incluso a heces, inflamación del reservorio y reproducción de la enfermedad.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Actualmente, no existe una alternativa eficaz de tratamiento.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, habrá persistencia de los síntomas actuales o aparición de nuevos. Seguirá dependiendo de medicamentos para controlar los síntomas y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).


b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyCirugiaViaBiliar($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('CIRUGÍA DE LA VÍA BILIAR'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-071', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Este procedimiento está indicado en cuadro de obstrucción o de infección de la vía biliar, mediante esta técnica se realiza una exploración del conducto biliar para confirmar el nivel y la causa de la obstrucción, puede explorarse por vía laparoscopica o una incisión abdominal. Dependiendo de la causa y la localización se realizará una limpieza, extirpación o drenaje de la vía biliar. En algunos casos se asocia una cirugía sobre el hígado o el duodeno y páncreas. Estos procedimientos conllevan la realización de anastomosis y la colocación de drenajes y cabe la posibilidad de que durante la cirugía haya que realizar modificaciones del procedimiento por los hallazgos intraoperatorios para proporcionar el tratamiento más adecuado.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Evitar la progresión de la enfermedad y las complicaciones derivadas de la obstrucción o infección de la vía biliar.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables. Son tanto los comunes derivados de toda intervención y que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento: fístula biliar, pancreática o digestiva, estrechez de la vía biliar, hemorragia o infección interna, colangitis (infección de los conductos biliares), y también puede ocurrir reproducción de la enfermedad. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero pueden llegar a requerir una reintervención, generalmente de urgencia, incluyendo un riesgo de mortalidad.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La no realización de la intervención lleva al progreso de la enfermedad e incluye la posibilidad de requerir una intervención de urgencia, en peores condiciones del paciente y por lo tanto con mayor riesgo quirúrgico.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyColecistectomiaLaparoscopica($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('COLECISTECTOMÍA LAPAROSCÓPICA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-079', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Este procedimiento está indicado en cuadro de obstrucción o de infección de la vía biliar y busca evitar la progresión de la enfermedad y las complicaciones derivadas de la obstrucción o infección de la vía biliar. La colecistectomía es la extirpación quirúrgica de la vesícula biliar, se realiza debido a la presencia de cálculos biliares que causan dolor o una infección. La técnica consiste en la realización de varias incisiones en la pared del abdomen con la posterior introducción de unos dispositivos conocidos con el nombre de trócares y la posterior introducción de una cámara e instrumental quirúrgico, creando un espacio tras la introducción del aire dentro de la cavidad abdominal, para así poder extraer la vesícula. En casos en que técnicamente o por hallazgos intraoperatorios no sea posible concluir la cirugía por esta vía se procederá a realizar la incisión habitual. La intervención requiere la administración de anestesia.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Evitar la progresión de la enfermedad y las complicaciones derivadas de la obstrucción o infección de la vía biliar.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). A pesar de la adecuada elección de la técnica y de su correcta realización pueden presentarse efectos indeseables. Son tanto los comunes derivados de toda intervención y que pueden afectar a todos los órganos y sistemas, como otros específicos del procedimiento: fístula biliar, pancreática o digestiva, estrechez de la vía biliar, hemorragia o infección interna, colangitis (infección de los conductos biliares), y también puede ocurrir reproducción de la enfermedad. Estas complicaciones habitualmente se resuelven con tratamiento médico (medicamentos, sueros, etc.) pero pueden llegar a requerir una reintervención, generalmente de urgencia, incluyendo un riesgo de mortalidad.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La no realización de la intervención lleva al progreso de la enfermedad e incluye la posibilidad de requerir una intervención de urgencia, en peores condiciones del paciente y por lo tanto con mayor riesgo quirúrgico.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodySondajeLagrimal($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('SONDAJE LAGRIMAL'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-046', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El procedimiento de sondaje de vías lagrimales consiste en introducir una sonda en el lagrimal del ojo hacia el punto de obstrucción del conducto nasolagrimal para abrirlo y permitir el correcto drenaje de las lágrimas. A continuación, se inyecta una sustancia colorante en el conducto para asegurarse de que el procedimiento ha dado buenos resultados. El proceso tiene una duración aproximada de unos 20 minutos y los resultados suelen empezar a percibirse de forma inmediata, aunque los resultados definitivos se apreciarán pasadas unas semanas. El sondaje de vías lagrimales es un procedimiento quirúrgico que se emplea para tratar la obstrucción congénita del conducto lagrimal o en estenosis'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Otros beneficios específicos esperados de la realización de este procedimiento son: Aliviar los síntomas de obstrucción de las vías lagrimales.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud. Otros beneficios específicos esperados de la realización de este procedimiento son: Aliviar los síntomas de obstrucción de las vías lagrimales.'),0,'J');
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Tras la intervención suele haber una inflamación del ojo pasajera los primeros días postoperatorios.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Otras complicaciones son el enrojecimiento del ojo y se pueden apreciar molestias por algunos días.'));


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento también se pueden emplear otros procedimientos como el masaje sobre el saco lagrimal o el uso de productos antibióticos, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud, las molestias aumentaran y podría tener una infección conjuntival.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyDescompresionTunelCarpiano($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('DESCOMPRESIÓN DEL TÚNEL CARPIANO'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-058', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El síndrome del túnel carpiano se define como la compresión del nervio mediano a nivel de la parte anterior de la muñeca.

La intervención de descompresión del túnel carpiano consiste en la división del ligamento anular del carpo para lograr la descompresión de los elementos que discurren dentro del túnel del carpo, asociando o no una plastia (reconstrucción) de este, en el momento del cierre.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Los beneficios esperados son la mejora de los síntomas por los cuales ha consultado y, por ende, mejoramiento del estado de salud.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz). Existen otros riesgos como:'),0,'J');
        $pdf->SetX(15);
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Cicatrices dolorosas.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Dehiscencias de suturas.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Reaparición de la sintomatología por recidiva de la compresión nerviosa.'));
        $pdf->MultiCellBlt(183,4,'>',utf8_decode('Los riesgos poco frecuentes son las cicatrices hipertróficas, lesiones de las ramas motoras del nervio mediano, lesión de la rama sensitiva del nervio mediano, lesión del paquete cubital, lesión vascular del arco palmar superficial, cuerdas de arco con subluxación nerviosa o tendinosa, distrofia simpático refleja y alteraciones cutáneas, nerviosas o vasculares por lesión producida por el manguito de isquemia. Si se realiza anestesia troncular puede provocar neuritis de larga evolución por inyección intraneural y shock anafiláctico.'));

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas___________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud, las molestias aumentaran y podría tener una infección conjuntival.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');


        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyLigamentoCruzadoAnteriorExploracion($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('RECONSTRUCCIÓN DEL LIGAMENTO CRUZADO ANTERIOR Y EXPLORACIÓN DE LA RODILLA'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-086', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('El ligamento cruzado anterior es una potente estructura ligamentosa en forma de cordón cruzado que se encuentra en el centro de la articulación de la rodilla entre la tibia y el fémur, proporciona estabilidad frente a movimientos en el sentido anteroposterior y rotatorio de la rodilla. Este ligamento tiene una mínima capacidad de curación, cuando se rompe de forma completa, no puede esperarse su curación espontánea, por lo que precisa su sustitución para recuperar la función perdida. El procedimiento recibe el nombre de ligamentoplastia, la estructura que puede ser utilizada para su reemplazo podrá ser orgánica o inorgánica, dependiendo de si procede de ser vivo, o de fabricación artificial. En la mayoría de los casos, se utilizarán tendones humanos, tomados del propio paciente o de donantes (igualmente informados y donde se añade un documento de aceptación como receptor de tejidos biológicos homólogos). Puede ser necesaria una evaluación quirúrgica de lesiones asociadas de los ligamentos, meniscos, y cartílago articular.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos se intenta obtener una rodilla lo más estable posible; sin embargo, en el mejor de los casos no suele quedar exactamente igual que antes del accidente. La sensación de fallo al bajar pendientes o escaleras, caer después de un salto y a los giros, suele desaparecer casi por completo.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel). Existen otros riesgos como:

Complicaciones frecuentes (5-10%): Sinovitis (derrames de líquido en la articulación) de repetición, Rigidez o pérdida de movilidad en la articulación de la rodilla, No obtención de una estabilización adecuada de la rodilla, Dolor o molestias en zona donante (cara anterior rodilla o detrás del muslo- rodilla).

Complicaciones poco frecuentes (1-5%): Infección de la herida operatoria, Distrofia simpático-refleja o enfermedad de sudeck (dolor y tumefacción difusos en rodilla y pierna sin una causa aparente que lo justifique), Flebitis o tromboflebitis.

Complicaciones infrecuentes -1%: Hemorragia masiva por afectación de un gran vaso, que en algunos casos puede llevar a la amputación del miembro u ocasionar la muerte, lesión de los nervios adyacentes que podrían ocasionar trastornos sensitivos y/o motores permanentes, rotura o estallido del hueso que se manipula en la intervención, problemas vásculo-nerviosos secundarios, trombosis venosa profunda, que puede dar lugar en el peor de los casos a embolismo pulmonar y muerte.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('No es necesario intervenir quirúrgicamente todas las roturas de este ligamento. A menor edad, mayor sintomatología y actividad (deportes de contacto o trabajos que impliquen planos de sustentación irregulares) mayor indicación de cirugía. Si los síntomas son poco acusados, el paciente tiene más de 35-40 años y su actividad física no implica giros imprevistos, terreno irregular o deportes de contacto, es posible indicar un programa de rehabilitación especifico y una rodillera para actividades concretas. Si los fallos aumentaran o se produjeran derrames en la rodilla, sería conveniente realizar la intervención quirúrgica.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud, las molestias aumentaran y podría tener una infección conjuntival.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }

    public function bodyExtirpacionTumoresOvariosParaovaricos($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->SetFont('Arial', 'B', 11);

        $pdf->Line(20, 5, 190, 5);
        $pdf->Line(20, 35, 190, 35);
        $pdf->Line(20, 5, 20, 35);
        $pdf->Line(190, 5, 190, 35);

        $pdf->Image($logo, 22, 7, -220);
        $pdf->Line(67, 5, 67, 35);
        $pdf->SetXY(76,15);
        $pdf->Cell(186, 4, utf8_decode('CONSENTIMIENTO INFORMADO '), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->setX(72);
        $pdf->MultiCell(70, 4, utf8_decode('EXTIRPACION DE TUMORES OVARICOS, PARAOVARICOS Y DEL LIGAMENTO'), 0, 'C');

        $pdf->Line(147, 5, 147, 35);
        $pdf->Line(147, 25, 190, 25);
        $pdf->Line(147, 16, 190, 16);
        $pdf->SetXY(112, 13);

        $pdf->Cell(0, 0, utf8_decode('Código:'), 0, 0, 'C', 0);
        $pdf->SetX(149);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 0, 'FO-AI-048', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(113, 22);
        $pdf->Cell(0, 0, utf8_decode('Versión:'), 0, 0, 'C', 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(134, 22);
        $pdf->Cell(0, 0, '03', 0, 0, 'C', 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(136, 28);
        $pdf->Cell(0, 0, utf8_decode('Fecha de aprobación:'), 0, 0, 'C', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetX(115);
        $pdf->Cell(0, 10, '05/07/2022', 0, 0, 'C', 0);


        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(20, 45);
        $pdf->Cell(0,0,'Fecha y hora de diligenciamiento: '.self::$fechaHoy,0,0,'L',0);

        $pdf->SetTextColor(10, 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(81);
        $pdf->Cell(0,0,'Asegurador: '.self::$paciente->Ut,0,0,'C',0);

        $pdf->Line(10, 50, 200, 50);
        $pdf->Line(10, 65, 200, 65);
        $pdf->Line(10, 50, 10, 65);
        $pdf->Line(200, 50, 200, 65);
        $pdf->Line(25, 50, 25, 65);
        $pdf->Ln();
        $pdf->SetXY(10, 58);
        $pdf->Cell(0, 0, 'Paciente', 0, 0, 'L', 0);

        $y1 = $pdf->GetY();
        $pdf->Line(25, $y1+1, 200, $y1+1);
        $y2 = $pdf->GetY();
        $pdf->Line(55, $y2+7, 55, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(85, $y2+7, 85, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(145, $y2+7, 145, $y2-8);
        $y2 = $pdf->GetY();
        $pdf->Line(180, $y2+7, 180,$y2-8);

        $pdf->SetFillColor(252, 250, 250 );

        $y1=$pdf->GetY();
        $pdf->SetX(25);
        $pdf->MultiCell(25,-1,utf8_decode(self::$paciente->Primer_Ape),0,'C');
        $y2=$pdf->GetY();
        $pdf->SetXY(50,$y1);
        $pdf->MultiCell(30,-1,utf8_decode(self::$paciente->Segundo_Ape),0,'C');
        $y3=$pdf->GetY();
        $pdf->SetXY(80,$y1);
        $pdf->MultiCell(68,-1,utf8_decode(self::$paciente->Primer_Nom ."  ".self::$paciente->SegundoNom),0,'C');
        $y4=$pdf->GetY();
        $pdf->SetXY(148,$y1);
        $pdf->MultiCell(32,-1,utf8_decode(self::$paciente->Num_Doc),0,'C');
        $y5=$pdf->GetY();
        $pdf->SetXY(180,$y1);
        $pdf->MultiCell(20,-1,utf8_decode(self::$paciente->Edad_Cumplida),0,'C');

        $y3 = $pdf->GetY();
        $pdf->SetXY(25,$y3+3);
        $pdf->Cell(30, 6, 'Primer apellido', 0, 0,'C');
        $pdf->SetXY(55,$y3+3);
        $pdf->Cell(30, 6, 'Segundo apellido', 0, 0, 'C');
        $pdf->SetXY(85,$y3+3);
        $pdf->Cell(60, 6, 'Nombre completo', 0, 0, 'C');
        $pdf->SetXY(145,$y3+3);
        $pdf->Cell(35, 6, 'Documento', 0, 0, 'C');
        $pdf->SetXY(180,$y3+3);
        $pdf->Cell(20, 6, 'Edad', 0, 0, 'C');
        $cont = max($y1,$y2,$y3);
        $yt = $cont;


        $yt = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(12,$yt+7);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN DEL PROCEDIMIENTO:'),0,'J');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('La extirpación de dicho tumor puede realizarse mediante la quistectomía o tumorectomía que es la operación en la que se enuclea un quiste o un tumor sólido de ovario, respetando total o parcialmente el ovario o anejo donde asiente; la ovariectomía que es la resección completa del quiste de ovario y el ovario donde se encuentra este, y la anexectomía en la que además del ovario se extirpa la trompa del mismo lado. Estos procesos pueden afectar a uno o ambos anejos.
En principio se intentará la extirpación única del quiste o tumor, el desarrollo de la operación en si pueden aconsejar o hacer necesaria la ovariectomia total o parcial e incluso la anexectomía.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('BENEFICIOS DEL PROCEDIMIENTO'),0,'J');
        $pdf->SetFont('Arial', '', 8);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Aliviar o reducir los síntomas que presenta actualmente.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGOS DEL PROCEDIMIENTO'), 0, 0, 'J', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Como en toda intervención quirúrgica y por causas independientes del actuar médico se pueden presentar complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios, tanto médicos como quirúrgicos, siendo las complicaciones más frecuentes: náuseas, vómito, dolor, inflamación, moretones, seromas (acumulación de líquido en la cicatriz), granulomas (reacción a cuerpo extraño o sutura), queloide (crecimiento excesivo de la cicatriz), hematomas (acumulación de sangre), apraxias(cambios en la sensibilidad de la piel), cistitis, retención urinaria, sangrado o hemorragias con la posible necesidad de transfusión (intra o posoperatoria), infecciones (urinarias, de pared abdominal, pélvicas), reacciones alérgicas, irritación frénica, anemia, heridas involuntarias en vasos sanguíneos, vejiga, intestino u otros órganos, eventración (hernias en la cicatriz).
Los riesgos vitales son poco frecuentes, aunque, como en todo acto médico y teniendo en cuenta la necesidad de anestesia general en todos los casos, pueden producirse. Estos riesgos vitales, tanto intra como postoperatorios son los propios de cualquier cirugía mayor y están íntimamente relacionados con la edad, el estado general y la patología asociada que el paciente presente. Otras complicaciones, infección de sitio operatorio, muerte.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode('En todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos propios del procedimiento y dispone de los medios para el manejo de las complicaciones que llegaran a presentarse.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('ALTERNATIVAS DISPONIBLES AL PROCEDIMIENTO'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Si usted no acepta este procedimiento puede acceder a las siguientes alternativas terapéuticas______________________________________________________________________________________, sin embargo, no se asegura que tengan la misma efectividad que el procedimiento quirúrgico planteado.'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RIESGO DE NO ACEPTAR EL PROCEDIMIENTO'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de negarse al procedimiento indicado, probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de requerir más información al respecto de su tratamiento quirúrgico se puede dirigir al médico tratante.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudara a conocer mejor sus condiciones de salud.
El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->SetTextColor(10,10,10);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('DECLARACIÓN DE CONSENTIMIENTO INFORMADO:'), 0, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode('En caso de realización de procedimiento en menor de edad o persona en condición de discapacidad, nombre del representante legal ____________________________________________________________________________________________________________.
a)	Declaro que he entendido las condiciones y objetivos de la cirugía que se me va a practicar, los cuidados que debo tener antes y después de ella, manifiesto que la información recibida del médico tratante ha sido en un lenguaje claro y sencillo, y me ha dado la oportunidad de preguntar y resolver las dudas a satisfacción, comprendo que, si es necesario extraer algún tejido, se someterá a estudio anatomopatológico siendo mi deber reclamar el resultado e informarlo al médico; comprendo y acepto el alcance y los riesgos que conlleva el procedimiento quirúrgico que me indican, por lo que manifiesto sentirme satisfecho(a) con la información recibida:   SI ___ NO___ (marque con una X).

b)	El profesional me ha planteado la posibilidad de participar en estudios investigativos que adelanta la institución con fines de mejoramiento continuo, me ha explicado que en caso de que sea sujeto de investigación mis datos serán empleados guardando estricta confidencialidad, asimismo existe posibilidad de que se tomen registros fotográficos y/o audiovisuales en el proceso con fines exclusivamente académicos, por lo que manifiesto sentirme satisfecho(a) con la información recibida y aceptarlo :   SI ___ NO___ (marque con una X).

c)	 Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. para que se me realice el procedimiento arriba descrito (o a mi representado según el caso) el cual fue solicitado por mi médico tratante. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada del procedimiento. SI___ NO___ (marque con una X).

Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención.'),0,'J');

    }
}
