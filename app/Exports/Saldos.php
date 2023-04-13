<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;

class Saldos implements FromView//FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;
    private $fi;
    private $consulta;
    private $sucursal;

    public function __construct($fi1,$consulta1,$sucursal1)
    {
        $this->fi = $fi1;
        $this->consulta = $consulta1;
        $this->sucursal = $sucursal1;
        
    }

    public function view():View
    {
   
        return view('reportes.saldos_excel',['consulta'=>$this->consulta,'sucursal'=>$this->sucursal,'fechaini'=>$this->fi,]);
    }

    public function collection()
    {
        //
    }
}
