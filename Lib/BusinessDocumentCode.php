<?php

namespace FacturaScripts\Plugins\Afip\Lib;


use FacturaScripts\Core\Lib\BusinessDocumentCode as BusinessDocumentCodeCore;

/**
 * Description of BusinessDocumentCode
 *
 * @author Carlos García Gómez      <carlos@facturascripts.com>
 * @author Juan José Prieto Dzul    <juanjoseprieto88@gmail.com>
 */
class BusinessDocumentCode extends BusinessDocumentCodeCore{


	 /**
     * Generates a new identifier for humans from a document.
     *
     * @param BusinessDocument $document
     * @param bool             $newNumber
     */
    public static function getNewCode(&$document, $newNumber = true)
    {
        $sequence = static::getSequence($document);
        if ($newNumber) {
            $document->numero = static::getNewNumber($sequence, $document);
        }

        $document->codigo = \strtr($sequence->patron, [
            '{EJE}' => $document->codejercicio,
            '{EJE2}' => \substr($document->codejercicio, -2),
            '{SERIE}' => $document->codserie,
            '{0SERIE}' => \str_pad($document->codserie, 2, '0', \STR_PAD_LEFT),
            '{NUM}' => $document->numero,
            '{PVENTA}' => \str_pad($document->codpv, 4, '0', \STR_PAD_LEFT),
            '{0NUM}' => \str_pad($document->numero, $sequence->longnumero, '0', \STR_PAD_LEFT)
        ]);
    }


}