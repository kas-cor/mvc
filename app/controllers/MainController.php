<?php

namespace app\controllers;

use app\App;
use app\core\Controller;
use app\models\Tasks;
use app\widgets\{Alerts, Sorting};
use ErrorException;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends Controller {

    /**
     * Index action
     * @return null
     * @throws ErrorException
     * @throws \Exception
     */
    public function indexAction() {
        /** @var array $post */
        // Generate CSRF token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $get = App::$request['get'];

        if ($post = App::$request['post']) {
            // Verify CSRF token
            if (empty($post['csrf_token']) || $post['csrf_token'] !== $_SESSION['csrf_token']) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Неверный CSRF токен');
                $pagination = Tasks::pagination([], Sorting::getSorts(Tasks::className()), $get['page']);
                return $this->render('index', [
                    'title' => 'Задачи',
                    'pagination' => $pagination,
                    'post' => $post,
                ]);
            }
            
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
                $task->setName(htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'));
                $task->setEmail(htmlspecialchars($post['email'], ENT_QUOTES, 'UTF-8'));
                $task->setText(htmlspecialchars($post['text'], ENT_QUOTES, 'UTF-8'));
                $task->setStatus(Tasks::STATUS_NOT_SUCCESS);
                $task->setCreated_at(time());
                $task->save();
                Alerts::addFlash(Alerts::TYPE_SUCCESS, 'Новая запись добавлена!');
                unset($post);
            }
        }

        $pagination = Tasks::pagination([], Sorting::getSorts(Tasks::className()), $get['page']);

        return $this->render('index', [
            'title' => 'Задачи',
            'pagination' => $pagination,
            'post' => $post,
        ]);
    }

    /**
     * Sorting action
     */
    public function sortAction() {
        if ($get = App::$request['get']) {
            Sorting::setSortToggle(Tasks::className(), $get['column']);
        }

        return $this->redirect('/');
    }

}
