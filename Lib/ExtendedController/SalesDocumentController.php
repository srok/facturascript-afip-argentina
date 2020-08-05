<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2017-2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Afip\Lib\ExtendedController;

use FacturaScripts\Core\Lib\ExtendedController\SalesDocumentController as SalesDocumentControllerCore;
// use FacturaScripts\Core\Lib\ExtendedController\BusinessDocumentView;
// use FacturaScripts\Dinamic\Model\Cliente;
// 
/*use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Lib\ExtendedController\BaseView;
use FacturaScripts\Dinamic\Lib\Accounting\InvoiceToAccounting;
use FacturaScripts\Dinamic\Lib\BusinessDocumentGenerator;
use FacturaScripts\Dinamic\Lib\ExtendedController\SalesDocumentController;
use FacturaScripts\Dinamic\Lib\ReceiptGenerator;
use FacturaScripts\Dinamic\Model\FacturaCliente;*/
use FacturaScripts\Dinamic\Lib\BusinessDocumentCode;
use FacturaScripts\Dinamic\Model\EstadoDocumento;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Plugins\Afip\Lib\DocumentoAfip;

/**
 * Description of SalesDocumentController
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 */
abstract class SalesDocumentController extends SalesDocumentControllerCore
{

 protected function saveDocumentAction()
 {
  $this->setTemplate(false);
  if (!$this->permissions->allowUpdate) {
    $this->response->setContent($this->toolBox()->i18n()->trans('not-allowed-modify'));
    return false;
}

        // duplicated request?
/*if ($this->multiRequestProtection->tokenExist($this->request->request->get('multireqtoken', ''))) {
    $this->response->setContent($this->toolBox()->i18n()->trans('duplicated-request'));
    return false;
}*/

        /// loads model
$data = $this->getBusinessFormData();
$this->views[$this->active]->model->setAuthor($this->user);
$this->views[$this->active]->loadFromData($data['form']);
$this->views[$this->active]->lines = $this->views[$this->active]->model->getLines();


   //load extrainfo for code change if altpattern is enabled

$data = $this->getBusinessFormData();

$newEstado = $this->request->request->get('idestado'); 

$estadoDocumento = new EstadoDocumento();

$estadoDocumento->loadFromCode($newEstado);

if($estadoDocumento->altpattern){

    $this->getInvoiceNumber($this->views[$this->active]->model,$data);

    BusinessDocumentCode::getNewCode($this->views[$this->active]->model,false,true);

}


        /// save
$result = $this->saveDocumentResult($this->views[$this->active], $data);
$this->response->setContent($result);

        // Event finish
$this->views[$this->active]->model->pipe('finish');
return false;
}


protected function getInvoiceNumber(&$document,Array $data){
        //ver tipo de punto de venta

        //TODO: si es manual validar que numero2 no exista, no esté vacio

        //si es electrónico lo siguiente:

            //Cargo el cliente desde la factura

  $cliente = new Cliente();

  $cliente->loadFromCode($data['subject']['codcliente']);

  $facturaAfip = new DocumentoAfip();

  $facturaAfip->create($document, $cliente, $data);


}
}