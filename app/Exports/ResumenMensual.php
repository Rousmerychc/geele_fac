<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;

class ResumenMensual implements FromView//FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    private $fi;
    private $ff;
    private $sucu;
    private $consulta;
    private $sucursal;

    public function __construct($fi1,$ff1,$sucu1,$consulta1,$sucursal1)
    {
        $this->fi = $fi1;
        $this->ff = $ff1;
        $this->sucu = $sucu1;
        $this->consulta = $consulta1;
        $this->sucursal = $sucursal1;
        
    }

    public function view():View
    {
   
        return view('reportes.resumen_mensual_excel',['consulta'=>$this->consulta,'sucursal'=>$this->sucursal,'fechaini'=>$this->fi,'fechafin'=>$this->ff,'sucu'=>$this->sucu]);
    }
    public function collection()
    {
        //
    }
}
