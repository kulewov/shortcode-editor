<?php
/**
 * Шаблон кода позиций, выводимых в списке результатов поиска.
 *
 * wp-content/plugins/rb-shortcode-editor/classes/AddBanner/Controller.php
 *
 * @var array $data [
 * @var string|int 'id' Id букмекера
 * @var string 'title' Заголовок
 * @var string 'logo_url' Url логотива букмекера
 * ]
 */

$data = [
    'id'       => '<%=id%>',
    'title'    => '<%=title%>',
    'logo_url' => '<%=logoUrl%>',
]
?>
<script id="js-rb-shortcode-template" type="text/template">
    <div class="add-banner-item js-add-bookmaker-id" data-id="<?= $data['id'] ?>">
        <div class="add-banner-item-title"><?= $data['title'] ?></div>
        <div class="add-banner-item-logo" style="background-image: url(<?= $data['logo_url'] ?>)"></div>
    </div>
</script>

<script id="js-rb-shortcode-template-no-result" type="text/template">
    <div class="add-banner-item"><?= __('Результатов не найдено') ?></div>
</script>