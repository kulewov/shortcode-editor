<?php
/**
 * Шаблон кода, который выводится в админке.
 *
 * wp-content/plugins/rb-shortcode-editor/classes/AddBet/Controller.php
 */
?>
<div class="rb-shortcode js-rb-shortcode">
    <div class="rb-shortcode-group js-rb-shortcode-group">
        <div class="errors-container">
            <span class="error js-rb-shortcode-error is-hidden"><?= __('Поле обязательно к заполнению', 'bmr') ?></span>
            <span class="error-num js-rb-shortcode-error-num is-hidden"><?= __('Неверный формат', 'bmr') ?></span>
        </div>
        <input class="rb-shortcode-input js-rb-shortcode-input" form="rb-shortcode">
        <div class="rb-shortcode-close js-rb-shortcode-close">✕</div>
    </div>
    <div class="rb-shortcode-result js-rb-shortcode-result"></div>
</div>