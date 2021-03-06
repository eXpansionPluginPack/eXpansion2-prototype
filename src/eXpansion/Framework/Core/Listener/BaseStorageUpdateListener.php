<?php

namespace eXpansion\Framework\Core\Listener;

use eXpansion\Framework\Core\Model\Event\DedicatedEvent;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\MapStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BaseStorageUpdateListener
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Listner
 */
class BaseStorageUpdateListener implements EventSubscriberInterface
{
    /** @var Factory */
    protected $factory;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var MapStorage */
    protected $mapStorage;

    /** @var DispatcherInterface */
    protected $dispatcher;

    /**
     * BaseStorageUpdateListener constructor.
     *
     * @param Factory $factory
     * @param GameDataStorage $gameDataStorage
     * @param MapStorage $mapStorage
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        Factory $factory,
        GameDataStorage $gameDataStorage,
        MapStorage $mapStorage,
        DispatcherInterface $dispatcher
    ) {
        $this->factory = $factory;
        $this->gameDataStorage = $gameDataStorage;
        $this->mapStorage = $mapStorage;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'maniaplanet.game.BeginMap' => 'onManiaplanetGameBeginMap' ,
            Factory::EVENT_CONNECTED => 'onConnectionToDedicated'
        ];
    }

    /**
     *
     */
    public function onConnectionToDedicated()
    {
        $gameInfos = $this->factory->getConnection()->getCurrentGameInfo();
        $serverOptions = $this->factory->getConnection()->getServerOptions();

        $this->gameDataStorage->setServerOptions($serverOptions);
        $this->gameDataStorage->setScriptOptions($this->factory->getConnection()->getModeScriptSettings());
        $this->gameDataStorage->setSystemInfo($this->factory->getConnection()->getSystemInfo());
        $this->gameDataStorage->setGameInfos(clone $gameInfos);
        $this->gameDataStorage->setVersion($this->factory->getConnection()->getVersion());
        $this->gameDataStorage->setMapFolder($this->factory->getConnection()->getMapsDirectory());

        $this->gameDataStorage->setServerPlayer(
            $this->factory->getConnection()->getDetailedPlayerInfo($this->gameDataStorage->getSystemInfo()->serverLogin)
        );
    }

    /**
     * Called on the begining of a new map.
     *
     * @param DedicatedEvent $event
     */
    public function onManiaplanetGameBeginMap(DedicatedEvent $event)
    {
        $serverOptions = $this->factory->getConnection()->getServerOptions();
        $this->gameDataStorage->setScriptOptions($this->factory->getConnection()->getModeScriptSettings());
        $this->gameDataStorage->setServerOptions($serverOptions);
        $this->gameDataStorage->setSystemInfo($this->factory->getConnection()->getSystemInfo());

        $newGameInfos = $this->factory->getConnection()->getCurrentGameInfo();
        $prevousGameInfos = $this->gameDataStorage->getGameInfos();

        // TODO move this logic somewhere else.
        $this->dispatcher->reset($this->mapStorage->getMap($event->getParameters()[0]['UId']));

        if ($prevousGameInfos->gameMode != $newGameInfos->gameMode || $prevousGameInfos->scriptName != $newGameInfos->scriptName) {
            $this->gameDataStorage->setGameInfos(clone $newGameInfos);
            // TODO dispatch custom event to let it know?
        }
    }

}
