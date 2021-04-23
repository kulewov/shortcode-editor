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
 * ]
 */
?>
<div class="rb-bet-shortcode">
    <a class="link d-flex" href="<?= $data['link'] ?>"
       style="background-color:<?= $data['bookmaker_bg'] ?>">
        <div class="container d-flex">
            <div class="title d-flex">
                <p><?= $data['title'] ?></p>
            </div>
            <div class="bookmaker d-flex"
                 style="background-color:<?= $data['bookmaker_bg'] ?>">
                <div class="wrapper">
                    <div class="odds">
                        <p><?= $data['odds'] ?></p>
                    </div>
                    <div class="logo d-flex">
                        <img src="<?= $data['logo'] ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php if ($data['note']) { ?>
        <div class="note">
            <a href="<?= $data['link'] ?>">
                <p><?= $data['note'] ?></p>
            </a>
        </div>
    <?php } ?>
</div>
