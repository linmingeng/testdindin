<?php

/**
 * Redis的适配器类
 * 
 * @author JiangXh
 *
 */
class Redis_adapter {
    var $master = null;
    var $slave  = null;

    function __construct($master, $slave = null)
    {
        $this->master = new Predis\Client($master);

        if (!is_null($slave) && is_array($slave))
        {
            $this->slave = new Predis\Client($slave);
        }
    }

    function __call($name, $arguments)
    {
        $redis_db = $this->getCommandRW($name) == 'w' ? $this->master : $this->master; //目前读写都在Master上

        return call_user_func_array(array($this->master, $name), $arguments);
    }

    /**
     * 获取命令的读写类型
     * 
     * @author JiangXh
     * @param string $command
     * @return string
     */
    public function getCommandRW($command)
    {
        $comand_rw_type = array(
            /* miscellaneous commands */
            'ping'                            => 'r',
            'echo'                            => 'r',
            'auth'                            => 'w',
            /* connection handling */
            'quit'                            => 'w',
            /* commands operating on string values */
            'set'                             => 'w',
            'setnx'                           => 'w',
            'setPreserve'                     => 'w',
            'mset'                            => 'w',
            'setMultiple'                     => 'w',
            'msetnx'                          => 'w',
            'setMultiplePreserve'             => 'w',
            'get'                             => 'r',
            'mget'                            => 'r',
            'getMultiple'                     => 'r',
            'getset'                          => 'r',
            'getSet'                          => 'r',
            'incr'                            => 'w',
            'increment'                       => 'w',
            'incrby'                          => 'w',
            'incrementBy'                     => 'w',
            'decr'                            => 'w',
            'decrement'                       => 'w',
            'decrby'                          => 'w',
            'decrementBy'                     => 'w',
            'exists'                          => 'r',
            'del'                             => 'w',
            'delete'                          => 'w',
            'type'                            => 'r',
            /* commands operating on the key space */
            'keys'                            => 'r',
            'randomkey'                       => 'r',
            'randomKey'                       => 'r',
            'rename'                          => 'w',
            'renamenx'                        => 'w',
            'renamePreserve'                  => 'w',
            'expire'                          => 'w',
            'expireat'                        => 'w',
            'expireAt'                        => 'w',
            'dbsize'                          => 'r',
            'databaseSize'                    => 'r',
            'ttl'                             => 'r',
            'timeToLive'                      => 'r',
            /* commands operating on lists */
            'rpush'                           => 'w',
            'pushTail'                        => 'w',
            'lpush'                           => 'w',
            'pushHead'                        => 'w',
            'llen'                            => 'r',
            'listLength'                      => 'r',
            'lrange'                          => 'r',
            'listRange'                       => 'r',
            'ltrim'                           => 'w',
            'listTrim'                        => 'w',
            'lindex'                          => 'r',
            'listIndex'                       => 'r',
            'lset'                            => 'w',
            'listSet'                         => 'w',
            'lrem'                            => 'w',
            'listRemove'                      => 'w',
            'lpop'                            => 'w',
            'popFirst'                        => 'w',
            'rpop'                            => 'w',
            'popLast'                         => 'w',
            'rpoplpush'                       => 'w',
            'listPopLastPushHead'             => 'w',
            /* commands operating on sets */
            'sadd'                            => 'w',
            'setAdd'                          => 'w',
            'srem'                            => 'w',
            'setRemove'                       => 'w',
            'spop'                            => 'w',
            'setPop'                          => 'w',
            'smove'                           => 'w',
            'setMove'                         => 'w',
            'scard'                           => 'r',
            'setCardinality'                  => 'r',
            'sismember'                       => 'r',
            'setIsMember'                     => 'r',
            'sinter'                          => 'r',
            'setIntersection'                 => 'r',
            'sinterstore'                     => 'w',
            'setIntersectionStore'            => 'w',
            'sunion'                          => 'w',
            'setUnion'                        => 'w',
            'sunionstore'                     => 'w',
            'setUnionStore'                   => 'w',
            'sdiff'                           => 'r',
            'setDifference'                   => 'r',
            'sdiffstore'                      => 'w',
            'setDifferenceStore'              => 'w',
            'smembers'                        => 'r',
            'setMembers'                      => 'r',
            'srandmember'                     => 'r',
            'setRandomMember'                 => 'r',
            /* commands operating on sorted sets */
            'zadd'                            => 'w',
            'zsetAdd'                         => 'w',
            'zincrby'                         => 'w',
            'zsetIncrementBy'                 => 'w',
            'zrem'                            => 'w',
            'zsetRemove'                      => 'w',
            'zrange'                          => 'r',
            'zsetRange'                       => 'r',
            'zrevrange'                       => 'r',
            'zsetReverseRange'                => 'r',
            'zrangebyscore'                   => 'r',
            'zsetRangeByScore'                => 'r',
            'zcard'                           => 'r',
            'zsetCardinality'                 => 'r',
            'zscore'                          => 'r',
            'zsetScore'                       => 'r',
            'zremrangebyscore'                => 'w',
            'zsetRemoveRangeByScore'          => 'w',
            /* multiple databases handling commands */
            'select'                          => 'w',
            'selectDatabase'                  => 'w',
            'move'                            => 'w',
            'moveKey'                         => 'w',
            'flushdb'                         => 'w',
            'flushDatabase'                   => 'w',
            'flushall'                        => 'w',
            'flushDatabases'                  => 'w',
            /* sorting */
            'sort'                            => 'w',
            /* remote server control commands */
            'info'                            => 'w',
            'slaveof'                         => 'w',
            'slaveOf'                         => 'w',
            /* persistence control commands */
            'save'                            => 'w',
            'bgsave'                          => 'w',
            'backgroundSave'                  => 'w',
            'lastsave'                        => 'w',
            'lastSave'                        => 'w',
            'shutdown'                        => 'w',
            'bgrewriteaof'                    => 'w',
            'backgroundRewriteAppendOnlyFile' => 'w',
            /* transactions */
            'multi'                           => 'w',
            'exec'                            => 'w',
            'discard'                         => 'w',
            /* commands operating on string values */
            'setex'                           => 'w',
            'setExpire'                       => 'w',
            'append'                          => 'w',
            'substr'                          => 'r',
            /* commands operating on lists */
            'blpop'                           => 'w',
            'popFirstBlocking'                => 'w',
            'brpop'                           => 'w',
            'popLastBlocking'                 => 'w',
            /* commands operating on sorted sets */
            'zunionstore'                     => 'w',
            'zsetUnionStore'                  => 'w',
            'zinterstore'                     => 'w',
            'zsetIntersectionStore'           => 'w',
            'zcount'                          => 'w',
            'zsetCount'                       => 'r',
            'zrank'                           => 'r',
            'zsetRank'                        => 'r',
            'zrevrank'                        => 'r',
            'zsetReverseRank'                 => 'r',
            'zremrangebyrank'                 => 'w',
            'zsetRemoveRangeByRank'           => 'w',
            /* commands operating on hashes */
            'hset'                            => 'w',
            'hashSet'                         => 'w',
            'hsetnx'                          => 'w',
            'hashSetPreserve'                 => 'w',
            'hmset'                           => 'w',
            'hashSetMultiple'                 => 'w',
            'hincrby'                         => 'w',
            'hashIncrementBy'                 => 'w',
            'hget'                            => 'r',
            'hashGet'                         => 'r',
            'hmget'                           => 'r',
            'hashGetMultiple'                 => 'r',
            'hdel'                            => 'w',
            'hashDelete'                      => 'w',
            'hexists'                         => 'r',
            'hashExists'                      => 'r',
            'hlen'                            => 'r',
            'hashLength'                      => 'r',
            'hkeys'                           => 'r',
            'hashKeys'                        => 'r',
            'hvals'                           => 'r',
            'hashValues'                      => 'r',
            'hgetall'                         => 'r',
            'hashGetAll'                      => 'r',
            'zrevrangebyscore'                => 'r',
            'brpoplpush'                      => 'w',
            /* publish - subscribe */
            'subscribe'                       => 'w',
            'unsubscribe'                     => 'w',
            'psubscribe'                      => 'w',
            'punsubscribe'                    => 'w',
            'publish'                         => 'w',
            /* remote server control commands */
            'config'                          => 'w',
            'configuration'                   => 'w'
        );

        return $comand_rw_type[$command];
    }

}
