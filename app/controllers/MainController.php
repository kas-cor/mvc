<?php

namespace app\controllers;

use app\App;
use app\core\Controller;
use app\models\Tasks;
use app\widgets\Alerts;
use app\widgets\Sorting;
use ErrorException;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends Controller {

    /**
     * Index action
     * @return string
     * @throws ErrorException
     */
    public function indexAction() {
        $get = App::$request['get'];

        if ($post = App::$request['post']) {
            if (empty($post['name'])) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Не введено имя');
            }
            if (empty($post['email'])) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Не введен e-mail');
            } else {
                if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, 'Введен не верный e-mail');
                }
            }
            if (empty($post['text'])) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Не введен текст');
            }
            if (!Alerts::isPresent()) {
                $task = new Tasks();
                $task->setName($post['name']);
                $task->setEmail($post['email']);
                $task->setText($post['text']);
                $task->setStatus(Tasks::STATUS_NOT_SUCCESS);
                $task->setCreated_at(time());
                $task->save();
                Alerts::addFlash(Alerts::TYPE_SUCCESS, 'Новая запись добавлена!');
                unset($post);
            }
        }

        $pagination = Tasks::pagination([], Sorting::getSorts(Tasks::className()), $get['page']);

        /** @var array $post */
        return $this->render('index', [
            'title' => 'Задачи',
            'pagination' => $pagination,
            'post' => $post,
        ]);
    }

    /**
     * Sorting action
     * @return void
     */
    public function sortAction() {
        if ($get = App::$request['get']) {
            Sorting::setSortToggle(Tasks::className(), $get['column']);
        }

        return $this->redirect('/');
    }

}
