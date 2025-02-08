<?php

namespace Src\Facades;

use DateTimeInterface;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;

/**
 * 
 * @method static EntityRepository getRepository(string $className)
 * @method static Cache|null getCache()
 * @method static Connection getConnection()
 * @method static ClassMetadataFactory getConnection()
 * @method static Expr getExpressionBuilder()
 * @method static void beginTransaction()
 * @method static mixed wrapInTransaction(callable $func)
 * @method static void commit()
 * @method static void rollback()
 * @method static void commit()
 * @method static Query createQuery(string $dql = '')
 * @method static NativeQuery createNativeQuery(string $sql, ResultSetMapping $rsm)
 * @method static QueryBuilder createQueryBuilder()
 * @method static object|null find(string $className, mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null)
 * @method static void refresh(object $object, LockMode|int|null $lockMode = null)
 * @method static void close()
 * @method static void lock(object $entity, LockMode|int $lockMode, DateTimeInterface|int|null $lockVersion = null)()
 * @method static EventManager getEventManager()
 * @method static Configuration getConfiguration()
 * @method static bool isOpen()
 * @method static UnitOfWork getUnitOfWork()
 * @method static AbstractHydrator newHydrator(string|int $hydrationMode)
 * @method static ProxyFactory getProxyFactory()
 * @method static FilterCollection getFilters()
 * @method static bool isFiltersStateClean()
 * @method static bool hasFilters()
 * @method static ClassMetadata getClassMetadata(string $className)
 * 
 * @method static void persist(object $object)
 * @method static void remove(object $object)
 * @method static void clear()
 * @method static void detach(object $object)
 * @method static void refresh(object $object)
 * @method static void flush()
 * @method static ClassMetadataFactory getMetadataFactory()
 * @method static void initializeObject(object $obj)
 * @method static bool isUninitializedObject(mixed $value)
 * @method static bool contains(object $object)
 * 
 * 
 * @see \Doctrine\Persistence\ObjectManager
 * @see \Doctrine\ORM\EntityManagerInterface
 */
class EntityManager extends Facade
{

    protected static function getFacadeAccessor(): string {
        return \Doctrine\ORM\EntityManagerInterface::class;
    }
}