<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Bundle\Acme\Plugins\Test;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;

use FML\Controls\Label;

class MemoryWidgetFactory extends WidgetFactory
{
    /** @var  Label */
    protected $memoryMessage;


    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $this->memoryMessage = new Label();
        $this->memoryMessage->setTextPrefix('$s')->setText("waiting data...");

        $manialink->getContentFrame()->setScale(0.8);
        $manialink->addChild($this->memoryMessage);

    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink); // TODO: Change the autogenerated stub
        $this->memoryMessage->setText(Test::$memoryMsg);
    }

}
