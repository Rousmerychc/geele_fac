<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\Exportable;

use Maatwebsite\Excel\Concerns\FromQuery;

class KardexProducto implements FromView//FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    private $fi;
    private $ff;
    private $consulta;
    private $resul;
    private $sucursal;
    private $producto;

    public function __construct($fechaini1,$fechafin1,$consulta1,$resul1,$sucursal1,$producto1)
    {
        $this->fi = $fechaini1;
        $this->ff = $fechafin1;
        $this->consulta = $consulta1;
        $this->resul = $resul1;
        $this->sucursal = $sucursal1;
        $this->producto = $producto1;
    }

    public function view():View
    {
   
        return view('reportes.kardex_producto_excel',['consulta'=>$this->consulta,'sucursal'=>$this->sucursal,'fechaini'=>$this->fi,'fechafin'=>$this->ff, 'resul'=>$this->resul, 'producto'=>$this->producto]);
    }
    public function collection()
    {
        //
    }
}
