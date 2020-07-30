<?

namespace FacturaScripts\Plugins\Afip\Lib;


use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Dinamic\Model\FacturaCliente;

use Afip;

/**
 * 
 */
class DocumentoAfip 
{

	private $tipo_doc_fiscal;

	private $concepto = 1;

	private $punto_venta;

	private $tipo_de_documento = 99;//CONSUMIDOR FINAL

	private $nro_documento = 0;

	private $nro_factura;

	private $fecha;

	private $importe_gravado;

	private $importe_exento_iva;

	private $importe_iva;

	private $fecha_servicio_desde = null;

	private $fecha_servicio_hasta = null;

	private $fecha_vencimiento_pago = null;

	private $cantidad_facturas = 1;

	private $importe_neto_no_gravado = 0;

	private $importe_tota_tributo = 0;

	private $moneda = 'PES';

	private $moneda_cotizacion = 1;

	private $iva;//array con alicuotas discrimindas.

	private $docFiscalCodes = [
			'NC'=>['A'=> 3,'B'=> 8,'C'=> 13],
			'ND'=>['A'=> 2,'B'=> 7,'C'=> 12],
			'F'=>['A'=> 1,'B'=> 6,'C'=> 11]
		];

	private $docCode = [
			'DNI' => 96,
			'CUIT' => 80,
			'CUIL' => 86,
			'CONSUMIDOR FINAL'=> 99
	];


	private function setCodTipoDocFiscal($tipo,$letra){
			$this->tipo_doc_fiscal=$this->docFiscalCodes[$tipo][$letra];
	}

	private function setTipoDoc($tipo){
		$this->tipo_de_documento = $docCode[$tipo];
	}


	public function create(&$documentoCliente, Cliente $cliente){

	}

	public function prepareData(){
		$data = array(
			'CantReg' 	=> $this->cantidad_facturas, // Cantidad de facturas a registrar
			'PtoVta' 	=> $this->punto_venta,
			'CbteTipo' 	=> $this->tipo_doc_fiscal, 
			'Concepto' 	=> $this->concepto,
			'DocTipo' 	=> $this->tipo_de_documento,
			'DocNro' 	=> $this->nro_documento,
			'CbteDesde' => $this->nro_factura,
			'CbteHasta' => $numero_de_factura,
			'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
			'FchServDesde'  => $fecha_servicio_desde,
			'FchServHasta'  => $fecha_servicio_hasta,
			'FchVtoPago'    => $fecha_vencimiento_pago,
			'ImpTotal' 	=> $importe_gravado + $importe_iva + $importe_exento_iva,
			'ImpTotConc'=> 0, // Importe neto no gravado
			'ImpNeto' 	=> $importe_gravado,
			'ImpOpEx' 	=> $importe_exento_iva,
			'ImpIVA' 	=> $importe_iva,
			'ImpTrib' 	=> 0, //Importe total de tributos
			'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
			'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
			'Iva' 		=> array(// Alícuotas asociadas al factura
				array(
					'Id' 		=> 5, // Id del tipo de IVA (5 = 21%)
					'BaseImp' 	=> $importe_gravado,
					'Importe' 	=> $importe_iva 
				)
			), 
		);

		return $data;
	}	


}