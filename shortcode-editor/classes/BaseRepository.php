<?php

namespace RB\WpShortCodeEditor;

class BaseRepository
{
    /** Имя обработчика ajax*/
    public string  $name         = '';
    private string $ajaxBaseName = 'rb_shortcode_';

    function __construct()
    {
        $this->name && add_action('wp_ajax_' . $this->ajaxBaseName . $this->name, [$this, 'ajaxGetData']);
    }

    /**
     * Отдает данные через ajax.
     */
    public function ajaxGetData(): array
    {
        $searchValue = $_POST['search'];
        if (empty($searchValue)) {
            die(json_encode([]));
        }

        global $wpdb;

        $limit = 50;
        $sql   = "
            SELECT
                p.ID as id,
                pm1.meta_value as title,
                p2.guid as logoUrl
            FROM $wpdb->posts p
            INNER JOIN $wpdb->postmeta pm1
            	ON pm1.post_id = p.ID AND pm1.meta_key = 'reviews_info_bmr'
            LEFT JOIN $wpdb->postmeta pm2
            	ON pm2.post_id = p.ID AND pm2.meta_key = 'about_bmr_general_white_logo'
            LEFT JOIN $wpdb->posts p2
            	ON p2.ID = pm2.meta_value AND p2.post_type = 'attachment'
            WHERE p.post_status = 'publish' AND p.post_type = 'bookreviews'
                AND pm1.meta_value LIKE %s
			ORDER BY title
			LIMIT %d
        ";

        $data = $wpdb->get_results(
            $wpdb->prepare($sql, $wpdb->esc_like($searchValue) . '%', $limit)
        );

        die(json_encode($data));
    }

    /**
     * Формурует данные для вывода.
     *
     * @param array $atts
     */
    protected function getData($atts)
    {
    }
}