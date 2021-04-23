<?php
/**
 * Шаблон кода, вставляемого шорткодом.
 *
 * wp-content/plugins/rb-shortcode-editor/classes/AddBet/Controller.php
 *
 * @var array $data [
 *      @var string 'title' Заголовок
 *      @var string 'link' Ссылка
 * ]
 */
?>

<button data-background-color="#15a863"
        formaction="<?= $data['link'] ?>"
        data-color="white"
        data-turbo="false"
        data-primary="true"
><?= $data['title'] ?></button>
