<?php
namespace Cable\Caching\Redis;


use Cable\Caching\Driver\BootableDriverInterface;
use Cable\Caching\Driver\DriverInterface;
use Cable\Caching\Driver\FlushableDriverInterface;
use Cable\Caching\Driver\TimeableDriverInterface;
use Predis\Client;

class RedisDriver implements BootableDriverInterface,
    DriverInterface,
    FlushableDriverInterface,
    TimeableDriverInterface
{

    /**
     * @var Client
     */
    private $predis;

    /**
     * @param array $configs
     * @return mixed
     */
    public function boot($configs = array())
    {
        if (!isset($configs['redis'])) {
            $configs['redis'] = array(
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
            );
        }
        $redis = $configs['redis'];

        $host = isset($redis['host']) ? $redis['host'] : '127.0.0.1';
        $port = isset($redis['port']) ? $redis['port'] : 6379;
        $scheme  = isset($redis['scheme']) ? $redis['scheme'] : 'tcp';

        $this->predis = new Client(
            compact(
                'host', 'port', 'scheme'
            )
        );
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (!$this->predis->exists($name)) {
            return $default;
        }

        return $this->predis->get($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function delete($name)
    {
        return $this->predis->del(array($name));
    }

    /**
     * @return $this
     */
    public function flush()
    {
        return $this->predis->flushall();
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param int $time
     * @return mixed
     */
    public function set($name, $value, $time)
    {
        return $this->predis->setex($name,$time, $value);
    }
}