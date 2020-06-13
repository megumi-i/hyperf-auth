<?php

namespace Niexiawei\Auth\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeServerStart;
use Hyperf\Framework\Event\BeforeWorkerStart;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Niexiawei\Auth\SwooleTableIncr;

class AppBootListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BeforeServerStart::class,
            BootApplication::class
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof BeforeServerStart){
            $app = ApplicationContext::getContainer();
            $config = $app->get(ConfigInterface::class);
            if (!file_exists(BASE_PATH . '/auth_key')) {
                throw new \Exception('key文件不存在，请使用命令生成');
            }
            $key = file_get_contents(BASE_PATH . '/auth_key');
            $config->set('auth.key', $key);
        }
        
        if ($event instanceof BootApplication){
            ApplicationContext::getContainer()->get(SwooleTableIncr::class)->init();
        }
    }

}
