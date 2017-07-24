<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Script\Features\ScriptFeature;
use FML\Types\Renderable;

abstract class abstractUiElement extends ScriptFeature implements Renderable
{
    protected $posX = 0;
    protected $posY = 0;
    protected $posZ = 0;

    /**
     * @param int $posX
     * @param int $posY
     * @param int $posZ
     */
    public function setPosition($posX = 0, $posY = 0, $posZ = 0)
    {
        $this->posX = $posX;
        $this->posY = $posY;
        $this->posZ = $posZ;

        return $this;

    }

    public function setX($X)
    {
        $this->posX = $X;

        return $this;
    }

    public function setY($Y)
    {
        $this->posY = $Y;

        return $this;
    }

    public function setZ($Z)
    {
        $this->posZ = $Z;

        return $this;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    abstract public function render(\DOMDocument $domDocument);

}