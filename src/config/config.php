<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */
return [
    'config' => [
        'crops' => [
            'square_small' => ['width' => 100, 'height' => 100, 'quality' => 80],
            'square_medium' => ['width' => 500, 'height' => 500, 'quality' => 80],
            'square_large' => ['width' => 1000, 'height' => 1000, 'quality' => 90],
            'social' => ['width' => 1600, 'quality' => 85],
            'thumb' => ['width' => 200, 'height' => 200, 'quality' => 80],
            'dashboard_discussioni' => ['width' => 150, 'quality' => 80],
            'dashboard_small_old' => ['width' => 55, 'quality' => 80],
            'scope_community_old' => ['width' => 285, 'quality' => 80],
            'scope_community' => ['width' => 447, 'quality' => 80],
            'table_small' => ['width' => 70, 'quality' => 80],
            'dashboard_partnership' => ['height' => 50, 'quality' => 80],
            'dashboard_community' => ['width' => 400, 'quality' => 80],
            'dashboard_sondaggi' => ['width' => 400, 'quality' => 80],
            'dashboard_news' => ['width' => 761, 'quality' => 80],
            'dashboard_news_old' => ['width' => 220, 'quality' => 80],
            'item_news' => ['width' => 358, 'quality' => 80],
            'card_users' => ['width' => 176, 'quality' => 80],
            'profile_view_users' => ['width' => 200, 'quality' => 80],
            'showcase_item' => ['width' => 160, 'quality' => 80],
            'item_community' => ['width' => 251, 'quality' => 80],
            'item_organizations' => ['width' => 146, 'quality' => 80],
            'landing_news' => ['width' => 500, 'height' => 220, 'quality' => 80],
            'default' => ['width' => 500, 'quality' => 80],
        ],

    ],
    'params' => [
        'searchParams' => [
            'attach-gallery' => [
                'enable' => true,
            ],
            'attach-databank-file' => [
                'enable' => true,
            ],

        ],

    ]
];
