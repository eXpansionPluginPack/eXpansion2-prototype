<?php

namespace eXpansion\Core\DataProviders;

class TimerDataProvider extends AbstractDataProvider
{

    protected $time = 0;

    public function onRun()
    {

    }

    public function onPreLoop()
    {
        $this->dispatch(__FUNCTION__, []);

        if ($this->time != time()) {
            $this->dispatch("onEverySecond", []);
            $this->time = time();
        }
    }


    public function onPostLoop()
    {
        $this->dispatch(__FUNCTION__, []);
    }

}
