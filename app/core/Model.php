<?php

namespace app\core;

use Doctrine\ORM\Tools\Pagination\Paginator;
use src\Exception\SecurityException;

/**
 * Class Model
 * @package app\core
 */
class Model {

    /**
     * Save model
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save() {
        $entityManager = self::getEm();
        $entityManager->persist($this);
        $entityManager->flush();
        $entityManager->clear();
    }

    /**
     * Getting DB instant
     * @return mixed
     */
    static function getEm() {
        return App::$components['db']['em'];
    }

    /**
     * Find
     * @return mixed
     */
    static function find() {
        return self::getEm()->getRepository(get_called_class());
    }

    /**
     * Count
     * @param array $criteria
     * @return mixed
     */
    static function count(array $criteria = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->count($criteria);
    }

    /**
     * Clear
     */
    static function clear() {
        self::getEm()->getRepository(get_called_class())->clear();
    }

    /**
     * Find one
     * @param integer $id
     * @return mixed
     */
    static function findOne(int $id) {
        return self::getEm()->find(get_called_class(), $id);
    }

    /**
     * Find one by...
     * @param array $criteria
     * @param array $order
     * @return mixed
     */
    static function findOneBy(array $criteria = [], array $order = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->findOneBy($criteria, $order);
    }

    /**
     * Find all
     * @param array $criteria
     * @param array $order
     * @return mixed
     */
    static function findAll(array $criteria = [], array $order = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->findBy($criteria, $order);
    }

    /**
     * Pagination with parameterized queries (SQL Injection fix)
     * @param array $criteria
     * @param array $order
     * @param integer $page
     * @return array
     * @throws SecurityException
     * @throws \Exception
     */
    static function pagination(array $criteria = [], array $order = [], $page = 1): array {
        $repo = self::getEm()->getRepository(get_called_class());
        $query = $repo->createQueryBuilder('t');
        
        // Using parameterized queries to prevent SQL Injection
        $paramIndex = 0;
        foreach ($criteria as $column => $value) {
            // Validate column name to prevent SQL injection via column names
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
                throw new SecurityException("Invalid column name: {$column}");
            }
            $paramName = 'param_' . $paramIndex++;
            $query->andWhere('t.' . $column . ' = :' . $paramName)
                  ->setParameter($paramName, $value);
        }
        
        foreach ($order as $column => $o) {
            // Validate column name and order direction
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
                throw new SecurityException("Invalid column name: {$column}");
            }
            $direction = strtoupper($o) === 'DESC' ? 'DESC' : 'ASC';
            $query->addOrderBy('t.' . $column, $direction);
        }

        $paginator = new Paginator($query);
        $total = $paginator->count();
        $length = (int)App::$components['db']['pagination'];
        $page = $page < 1 ? 1 : (int)$page;
        $paginator->getQuery()->setFirstResult(($page - 1) * $length)->setMaxResults($length);

        return [
            'items' => $paginator->getIterator()->getArrayCopy(),
            'total' => $total,
            'current' => $page,
        ];
    }

    /**
     * Getting called class name
     * @return string
     */
    static function className(): string {
        return get_called_class();
    }

}
