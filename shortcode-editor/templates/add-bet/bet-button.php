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
<a href="<?= $data['link'] ?>"
   target="_blank"
   class="button filled-green size-lg radius-4 button-default button-v filled-green-v size-large-v seo-button-bet <?= $data['class'] ?>"
   data-event-category="click"
   data-event-action="repeat-bet"
>
	<?= $data['title'] ?>
</a>
