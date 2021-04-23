<?php

namespace RB\WpShortCodeEditor\AddBanner;

use RB\WpShortCodeEditor\BaseController;

class Controller extends BaseController
{
    protected Repository $repo;
    protected array      $postTypes = [
        'all'
    ];

    public function __construct()
    {
        parent::__construct();

        // Перенести обработку шорткода
        //add_shortcode('bet_button', [$this, 'shortcodeBetButton']);

        $this->directory = 'add-banner';
        $this->jsAction  = 'add_banner';
        $this->repo      = new Repository();
    }

    /**
     * Добавляет кнопку и форму поиска в админке.
     */
    public function actionDisplayBtnInWpEditor()
    {
        echo $this->getTemplate('button', [
            'title'     => __('Добавить баннер', 'bmr'),
            'action'    => $this->jsAction,
            'js_class'  => $this->jsClass . '-button',
            'shortcode' => 'bookmaker_banner'
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
            'bookmaker' => __('Введите имя букмекера и нажмите Enter', 'bmr')
        ];
    }
}