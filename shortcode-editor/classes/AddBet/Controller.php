<?php

namespace RB\WpShortCodeEditor\AddBet;

use RB\WpShortCodeEditor\BaseController;

class Controller extends BaseController
{
    /** Список разрешенных типов постов */
    protected array $postTypes = [
        'post',
        'wiki',
        'forecast',
        'appreviews',
        'news',
        'lotto',
        'bychkova',
        'events',
        'tournaments',
    ];

    protected Repository $repo;

    public function __construct()
    {
        parent::__construct();

        add_shortcode('rb_bet_button', [$this, 'shortcodeBetButton']);

        $this->directory = 'add-bet';
        $this->jsAction  = 'add_bet';
        $this->repo      = new Repository();
    }

    /**
     * Добавляет кнопку и форму поиска в админке.
     */
    public function actionDisplayBtnInWpEditor()
    {
        if (!$this->needToEnqueue()) {
            return;
        }

        echo $this->getTemplate('button', [
            'title'     => __('Добавить ставку (кнопка)', 'bmr'),
            'action'    => $this->jsAction,
            'js_class'  => $this->jsClass . '-button',
            'shortcode' => 'rb_bet_button'
        ], false);
        echo $this->getTemplate('search');
        echo $this->getTemplate('result');
    }

    /**
     * Получает объект с вариантами заполнителя.
     *
     * @return array
     */
    protected function getVariable()
    {
        return [
            'title'     => __('Введите заголовок кнопки и нажмите Enter', 'bmr'),
            'bookmaker' => __('Введите имя букмекера и нажмите Enter', 'bmr'),
        ];
    }


    /**
     * Преобразование шорткода для вывода.
     *
     * @param array $atts
     *
     * @return string
     */
    public function shortcodeBetButton($atts)
    {
        global $RBSite;

        $atts = shortcode_atts([
            'title'                        => __('Сделать ставку', 'bmr'),
            'link' . $RBSite->getPostfix() => '',
            'id'                           => '',
            'position'                     => '',
        ], $atts);

        $template = $this->directory . '/bet-button';

        $atts          = $this->repo->getData($atts);
        $atts['link']  = trim($atts['link']);
        $atts['class'] = $atts['link'] ? '' : 'redirect-bm';

        if ($this->isTurbo()) {
            $template .= '-turbo';
            if (!$atts['link']) {
                return '';
            }
        }

        return $this->getTemplate($template, $atts, false);
    }
}
