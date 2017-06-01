<?php

namespace Cable\Caching\Redis;


use Cable\Container\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{

    /**
     * register new providers or something
     *
     * @return mixed
     */
    public function boot()
    {

    }

    /**
     * register the content
     *
     * @return mixed
     */
    public function register()
    {
       $caching = $this->getContainer()->resolve('caching');

       $caching->addDriver('redis', RedisDriver::class);
    }
}
