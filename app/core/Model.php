<?php

namespace app\core;

use app\App;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Model {

    public function save() {
        $entityManager = self::getEm();
        $entityManager->persist($this);
        $entityManager->flush();
        $entityManager->clear();
    }
    
    static function getEm() {
        return App::$components['db']['em'];
    }

    static function find() {
        return self::getEm()->getRepository(get_called_class());
    }

    static function count($criteria = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->count($criteria);
    }

    static function clear() {
        self::getEm()->getRepository(get_called_class())->clear();
    }

    static function findOne($id) {
        return self::getEm()->find(get_called_class(), $id);
    }

    static function findOneBy($criteria = [], $order = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->findOneBy($criteria, $order);
    }

    static function findAll($criteria = [], $order = []) {
        $repo = self::getEm()->getRepository(get_called_class());
        return $repo->findBy($criteria, $order);
    }

    static function pagination($criteria = [], $order = [], $page = 1) {
        $repo = self::getEm()->getRepository(get_called_class());
        $query = $repo->createQueryBuilder('t');
        foreach ($criteria as $column => $value) {
            $query->where($query->expr()->eq('t.' . $column, $value));
        }
        foreach ($order as $column => $order) {
            $query->addOrderBy('t.' . $column, $order);
        }

        $paginator = new Paginator($query);
        $total = $paginator->count();
        if (!$length) {
            $length = (int) App::$components['db']['paginations'];
        }
        $page = $page < 1 ? 1 : $page;
        $paginator->getQuery()->setFirstResult(((int) $page - 1) * $length)->setMaxResults($length);

        return [
            'items' => $paginator->getIterator()->getArrayCopy(),
            'total' => $total,
            'current' => $page,
        ];
    }
    
    static function className() {
        return get_called_class();
    }

}
