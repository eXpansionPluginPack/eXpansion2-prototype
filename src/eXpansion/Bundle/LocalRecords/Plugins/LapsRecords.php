<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Framework\GameTrackmania\DataProviders\Listener\ListenerInterfaceLapData;

/**
 * Class RaceRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LapsRecords extends BaseRecords implements ListenerInterfaceLapData
{
    /*
     * @inheritdoc
     */
    public function onPlayerEndLap(
        $login,
        $time,
        $lapTime,
        $stuntsScore,
        $cpInLap,
        $curLapCps,
        $blockId,
        $speed,
        $distance
    ) {
        $eventData = $this->recordsHandler->addRecord($login, $lapTime, $curLapCps);
        if ($eventData) {
            $this->dispatchEvent($eventData);
        }
    }
}
