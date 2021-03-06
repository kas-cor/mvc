<?php

namespace app\models;

use app\core\Model;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, Table};

/**
 * @Entity
 * @Table(name="tasks")
 */
class Tasks extends Model {

    /**
     * Task status (not success)
     */
    const STATUS_NOT_SUCCESS = 0;

    /**
     * Task status (success)
     */
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

    /**
     * Getting ID
     * @return integer
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Getting Name
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Setting Name
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Getting Email
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Getting Text
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * Getting Created at
     * @return integer
     */
    public function getCreated_at(): int {
        return $this->created_at;
    }

    /**
     * Setting Email
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * Setting Text
     * @param string $text
     */
    public function setText(string $text) {
        $this->text = $text;
    }

    /**
     * Getting Status
     * @return integer
     */
    public function getStatus(): int {
        return $this->status;
    }

    /**
     * Setting Status
     * @param integer $status
     */
    public function setStatus(int $status) {
        $this->status = $status;
    }

    /**
     * Setting Created at
     * @param integer $created_at
     */
    public function setCreated_at(int $created_at) {
        $this->created_at = $created_at;
    }

    /**
     * Getting status list
     * @return array
     */
    static function getStatusList(): array {
        return [
            self::STATUS_NOT_SUCCESS => 'Не выполнена',
            self::STATUS_SUCCESS => 'Выполнена',
        ];
    }

}
