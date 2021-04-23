<?php

namespace RB\WpShortCodeEditor\AddBet;

use RB\WpShortCodeEditor\BaseRepository;

class Repository extends BaseRepository
{
    /** Имя обработчика ajax*/
    public string $name = 'add_bet';

    /**
     * Формурует данные для вывода.
     *
     * @param $atts
     *
     * @return array
     */
    public function getData($atts) {
        global $post, $RBSite;

        if ($atts['id']) {
            switch ($atts['position']) {
                case 'visit_app':
                    $atts['link'] = \AppReviewsHelper::getAppUri($atts['id']);
                    break;
                case 'getbonus':
                    $atts['link'] = \BonusesHelper::getBonusLink($atts['id']);
                    break;
                default:
                    $atts['link'] = \ReviewHelper::getReferralLink($post->ID, $atts['id']);
            }

            if (\BmrLinkHelper::bookmakerHasBonus((int)$atts['id'])) {
                $link = \BmrLinkHelper::getBookmakerBonus((int)$atts['id'], 'RewiewBr');
                !empty($link['bonusDeskLink']) && $atts['link'] = $link['bonusDeskLink'];
            }
        } else {
            $atts['link'] = $atts['link' . $RBSite->getPostfix()] ?: '';
        }

        /** Белый список ссылок РБ (из админке), только на RU версии. */
        if (get_current_blog_id() == BLOG_ID_BMR) {
            $atts['link'] = replace_nonwhitelist_links([0 => $atts['link'], 1 => $atts['link'], 2 => '']);
        }

        return apply_filters('rb/shortcode/add_bet/attributes', $atts, $post);
    }
}
