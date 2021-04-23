<?php

namespace RB\WpShortCodeEditor\RbBet;

use RB\WpShortCodeEditor\BaseController;

class Controller extends BaseController
{
    /** Список разрешенных типов постов */
    protected array      $postTypes = [
        'events',
        'tournaments',
        'news',
        'forecast',
    ];
    protected Repository $repo;

    public function __construct()
    {
        parent::__construct();

        add_shortcode('rb_bet', [$this, 'shortcodeBetRender']);

        $this->directory = 'rb-bet';
        $this->jsAction  = 'rb_bet';
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
            'title'     => __('Добавить коэффициент', 'bmr'),
            'action'    => $this->jsAction,
            'js_class'  => $this->jsClass . '-button',
            'shortcode' => 'rb_bet'
        ], false);
        echo $this->getTemplate('search');
        echo $this->getTemplate('result');
    }

    /**
     * Получает масств данных.
     *
     * @return array
     */
    protected function getVariable()
    {
        return [
            'title'     => __('Введите заголовок и нажмите Enter', 'bmr'),
            'note'      => __('Введите текст сообщения', 'bmr'),
            'bookmaker' => __('Введите имя букмекера и нажмите Enter', 'bmr'),
            'odds'      => __('Введите коэффициент', 'bmr'),
            'required'  => ['title', 'bookmaker', 'odds'],
        ];
    }


    /**
     * Преобразование шорткода для вывода.
     *
     * @param array $atts
     *
     * @return string
     */
    public function shortcodeBetRender($atts)
    {
        global $RBSite;

        $atts = shortcode_atts([
            'title'                        => '',
            'note'                         => '',
            'link' . $RBSite->getPostfix() => '',
            'id'                           => '',
            'odds'                         => '',
            'logo'                         => '',
            'bookmaker_bg'                 => '',
        ], $atts);

        $template = $this->directory . '/bet-button';

        $atts['is_turbo'] = $this->isTurbo();

        $atts          = $this->repo->getData($atts);
        $atts['link']  = trim($atts['link']);
        $atts['class'] = $atts['link'] ? '' : 'redirect-bm';

        if ($atts['is_turbo']) {
            $template .= '-turbo';
            if (!$atts['link']) {
                return '';
            }
        }

        if (\AmpHelper::isAmp()) {
            $template         .= '-amp';
            $shortCodeContent = $this->getTemplate($template, $atts, false);
            $shortCodeContent = \AmpHelper::convertContentImages($shortCodeContent);
            return $shortCodeContent;
        }

        return $this->getTemplate($template, $atts, false);
    }
}
