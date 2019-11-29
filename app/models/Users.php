<?php

namespace app\models;

use app\core\Model;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, Table};

/**
 * @Entity
 * @Table(name="users")
 */
class Users extends Model {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $login;

    /**
     * @Column(type="string")
     */
    protected $password;

    /**
     * getting ID
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getting Login
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Setting Login
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * Getting Password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Setting Password
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Sing in user
     * @param string $login Login
     * @param string $password Password
     * @return Users|null
     */
    static function singIn($login, $password) {
        if ($user = Users::findOneBy(['login' => $login, 'password' => md5($password)])) {
            $_SESSION['user'] = [
                'login' => $login,
            ];
        }
        return $user;
    }

    /**
     * Sing out user
     */
    static function singOut() {
        unset($_SESSION['user']);
    }

    /**
     * Getting is auth user
     * @return bool
     */
    static function isAuth() {
        return isset($_SESSION['user']);
    }

}
