<?php
namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    private $cache;
    private $logger;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(string $host, string $user, string $password, CacheItemPoolInterface $cache)
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;
    }

    
    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function getResponse(array $input) : array
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    new DateTime('tomorrow')
                );
                
            $this->cache->save($cacheItem);

            return $result;
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return [];
    }

    /**
     * @param array $input
     *
     * @return string
     */
    private function getCacheKey(array $input) : string
    {
        ksort($input);
        return json_encode($input);
    }
}
