<?php
/**
 * Шаблон кода, который выводится в админке.
 *
 * wp-content/plugins/rb-shortcode-editor/classes/AddBet/Controller.php
 *
 * @var array $data [
 * @var string 'title' Заголовок кнопки
 * @var string 'js_class' Класс для обращения js
 * @var string 'action' Имя формы
 * ]
 */
?>
<button type="button"
        class="button js-rb-shortcode-button <?= $data['js_class'] ?>"
        data-action="<?= $data['action'] ?>"
        data-shortcode="<?= $data['shortcode'] ?>">

    <?= $data['title'] ?>
</button>