<?php

namespace RB\WpShortCodeEditor;

class BaseController
{
    /** Имя директории для шаблонов. */
    public string $directory = '';
    /** Имя экшеша формы и объекта с данными. */
    public string $jsAction = '';
    /** Список разрешенных типов постов */
    protected array $postTypes = [];

    protected string $styleHandle = 'rb-bet-shortcode';
    /**
     * JS класс кнопки
     *
     * Переопределять только в крайнем случае
     * Использовать в формате $this->JsClass . '{ свой добавочный подкласс }'
     *
     */
    protected string $jsClass = 'js-rb-shortcode';
    protected string $handle = 'WpEditShortCodeBtn';

    public function __construct()
    {
        add_action('media_buttons', [$this, 'actionDisplayBtnInWpEditor'], 10);
        add_action('admin_enqueue_scripts', [$this, 'actionAssetsEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'actionAssetsEnqueueFront']);
    }

    /**
     * Подключение стилей и скриптов в админке.
     */
    public function actionDisplayBtnInWpEditor()
    {
    }

    /**
     * Подключение стилей и скриптов в админке.
     */
    public function actionAssetsEnqueue()
    {
        if (!$this->needToEnqueue()) {
            return;
        }

        wp_enqueue_style($this->handle, RB_SHORTCODE_URL . '/assets/css/style.css', [],
            filemtime(RB_SHORTCODE_PATH . '/assets/css/style.css')
        );

        wp_enqueue_script($this->handle, RB_SHORTCODE_URL . '/assets/js/functions.js', [],
            filemtime(RB_SHORTCODE_PATH . '/assets/js/functions.js'),
            true
        );

        $data = $this->getVariable();
        $data && $this->jsAction && wp_localize_script($this->handle, $this->jsAction, $data);
    }

    /**
     * Подключение стилей и скриптов на фронте.
     */
    public function actionAssetsEnqueueFront()
    {
        if (!$this->needToEnqueue()) {
            return;
        }
        wp_enqueue_style(
            $this->styleHandle,
            RB_SHORTCODE_URL . '/assets/css/front-style.css',
            [],
            filemtime(RB_SHORTCODE_PATH . '/assets/css/front-style.css')
        );
    }

    /**
     * Получает масств данных.
     *
     * @return array
     */
    protected function getVariable()
    {
        return [];
    }

    /**
     * Проверка на турбо страницу
     *
     * @return bool
     */
    protected function isTurbo()
    {
        return defined('YANDEX_TURBO');
    }

    /**
     * Загружает шаблон.
     *
     * @param string $name
     * @param array $data
     * @param bool $once
     *
     * @return string
     */
    protected function getTemplate($name, $data = [], $once = true)
    {
        $path = RB_SHORTCODE_PATH . '/templates/' . $name . '.php';

        if (!file_exists($path)) {
            return '';
        }

        ob_start();

        if ($once) {
            include_once $path;
        } else {
            include $path;
        }

        return ob_get_clean();
    }

    protected function needToEnqueue()
    {
        global $post;
        return !empty($post) && in_array($post->post_type, $this->postTypes) || in_array('all', $this->postTypes);
    }
}
