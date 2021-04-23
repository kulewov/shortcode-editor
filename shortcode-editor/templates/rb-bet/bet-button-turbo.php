<?php
/**
 * Шаблон кода, вставляемого шорткодом.
 *
 * wp-content/plugins/rb-shortcode-editor/classes/AddBet/Controller.php
 *
 * @var array $data [
 *          @var string 'title' Заголовок
 *          @var string 'link' Ссылка
 *          @var string 'odds' Коэффициент
 *          @var string 'logo' Лого букмекера
 *          @var string 'note' Дополнительное
 *          @var string 'bookmaker_bg' Цвет букмекера
 *          @var string 'book_name' Имя бука
 * ]
 */

?>

<div data-block="cards">
    <div data-block="card">
        <figure
            class="turbo-banner-logo"><?= $data['logo'] ? ('<img itemprop="logo" src="' . $data['logo'] . '" alt="">') : '<figcaption>' . $data['book_name'] . '</figcaption>' ?></figure>
        <button formaction="<?= $data['link'] ?>"
                data-background-color="<?= $data['bookmaker_bg'] ?>"
                data-color="white"
                data-turbo="true"
                data-primary="true"
        ><?= sprintf('K: %s - %s', $data['odds'], $data['title']) ?></button>

        <?php if ($data['note']) { ?>
            <br><?= $data['note'] ?>
        <?php } ?>
    </div>
</div>
