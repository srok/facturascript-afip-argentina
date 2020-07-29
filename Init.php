<?php
namespace FacturaScripts\Plugins\Afip;
use FacturaScripts\Core\Base\InitClass;

class Init extends InitClass
{

    public function init()
    {
        $this->loadExtension(new Extension\Model\Cliente());
    }
    public function update()
    {
        
    }

}
