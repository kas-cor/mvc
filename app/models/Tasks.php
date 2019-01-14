<?php

namespace app\models;

/**
 * @Entity
 * @Table(name="tasks")
 */
class Tasks extends \app\core\Model {

    const STATUS_NOT_SUCCESS = 0;
    const STATUS_SUCCESS = 1;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="string")
     */
    protected $email;

    /**
     * @Column(type="string")
     */
    protected $text;

    /**
     * @Column(type="integer")
     */
    protected $status;

    /**
     * @Column(type="integer")
     */
    protected $created_at;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreated_at() {
        return $this->created_at;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }

    static function getStatusList() {
        return [
            self::STATUS_NOT_SUCCESS => 'Не выполнена',
            self::STATUS_SUCCESS => 'Выполнена',
        ];
    }

}
