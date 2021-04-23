<?php

namespace RB\WpShortCodeEditor\RbBet;

use RB\WpShortCodeEditor\BaseRepository;

class Repository extends BaseRepository
{
    /** Имя обработчика ajax*/
    public string $name = 'rb_bet';

    /**
     * Формурует данные для вывода.
     *
     * @param array $atts
     *
     * @return array
     */
    public function getData($atts)
    {
        global $RBSite;

        if ($atts['id']) {
            $atts['link'] = \ReviewHelper::getReferralLink($atts['id']);

            if (get_current_blog_id() == BLOG_ID_BMR &&
                !$RBSite->isMirror() &&
                \BmrLinkHelper::bookmakerHasBonus((int)$atts['id'])
            ) {
                $link = \BmrLinkHelper::getBookmakerBonus((int)$atts['id'], 'RewiewBr');
                !empty($link['bonusDeskLink']) && $atts['link'] = $link['bonusDeskLink'];
            }

            $atts['bookmaker_bg'] = get_post_meta($atts['id'], 'reviews_color', true);
            !$atts['bookmaker_bg'] && $atts['bookmaker_bg'] = '#0077EE';

            $atts['logo'] = \ReviewHelper::bookmakerLogo($atts['id'], $atts['is_turbo']);
        } else {
            $atts['link'] = $atts['link' . $RBSite->getPostfix()] ?: '';
        }

        /** Белый список ссылок РБ (из админке), только на RU версии. */
        if (get_current_blog_id() === BLOG_ID_BMR) {
            $atts['link'] = replace_nonwhitelist_links([0 => $atts['link'], 1 => $atts['link'], 2 => '']);
        }
        return $atts;
    }
}