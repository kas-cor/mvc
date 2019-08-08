<?php

namespace app\models;

use app\core\Model;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

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
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getting Login
     *
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Setting Login
     *
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * Getting Password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Setting Password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Singin user
     *
     * @param string $login
     * @param string $password
     *
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
     * Singout user
     */
    static function singOut() {
        unset($_SESSION['user']);
    }

    /**
     * Getting is auth user
     *
     * @return bool
     */
    static function isAuth() {
        return isset($_SESSION['user']);
    }

}
