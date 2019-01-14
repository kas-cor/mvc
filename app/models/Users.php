<?php

namespace app\models;

/**
 * @Entity
 * @Table(name="users")
 */
class Users extends \app\core\Model {

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

    public function getId() {
        return $this->id;
    }

    public function getLogin() {
        return $this->name;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    static function singIn($login, $password) {
        if ($user = Users::findOneBy(['login' => $login, 'password' => md5($password)])) {
            $_SESSION['user'] = [
                'login' => $login,
            ];
        }
        return $user;
    }

    static function singOut() {
        unset($_SESSION['user']);
    }

    static function isAuth() {
        return isset($_SESSION['user']);
    }

}
