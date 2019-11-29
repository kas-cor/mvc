<?php

namespace app\core;

use app\App;
use Doctrine\ORM\Tools\Pagination\Paginator;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class Model
 * @package app\core
 */
class Model {

    /**
     * Save model
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
     * Pagination
     * @param array $criteria
     * @param array $order
     * @param integer $page
     * @return array
     * @throws \Exception
     */
    static function pagination(array $criteria = [], array $order = [], $page = 1): array {
        $repo = self::getEm()->getRepository(get_called_class());
        $query = $repo->createQueryBuilder('t');
        foreach ($criteria as $column => $value) {
            $query->where($query->expr()->eq('t.' . $column, $value));
        }
        foreach ($order as $column => $o) {
            $query->addOrderBy('t.' . $column, $o);
        }

        $paginator = new Paginator($query);
        $total = $paginator->count();
        $length = (int)App::$components['db']['pagination'];
        $page = $page < 1 ? 1 : $page;
        $paginator->getQuery()->setFirstResult(((int)$page - 1) * $length)->setMaxResults($length);

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
