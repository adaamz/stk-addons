<?php

/**
 * copyright 2009 Lucas Baudin <xapantu@gmail.com>
 *           2012 Stephen Just <stephenjust@users.sf.net>
 *           2014 Daniel Butum <danibutum at gmail dot com>
 * This file is part of stkaddons
 *
 * stkaddons is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * stkaddons is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with stkaddons.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class AccessControl
 */
class AccessControl
{

    // Define permission levels
    /**
     * @var array
     */
    private static $permissions = array(
        'basicUser'     => array(
            'basicPage'            => true,
            'addAddon'             => true,
            'manageaddons'         => false,
            'managebasicUsers'     => false,
            'managemoderators'     => false,
            'manageadministrators' => false,
            'manageroots'          => false,
            'managesettings'       => false
        ),
        'moderator'     => array(
            'basicPage'            => true,
            'addAddon'             => true,
            'manageaddons'         => true,
            'managebasicUsers'     => true,
            'managemoderators'     => false,
            'manageadministrators' => false,
            'manageroots'          => false,
            'managesettings'       => false
        ),
        'administrator' => array(
            'basicPage'            => true,
            'addAddon'             => true,
            'manageaddons'         => true,
            'managebasicUsers'     => true,
            'managemoderators'     => true,
            'manageadministrators' => false,
            'manageroots'          => false,
            'managesettings'       => true
        ),
        'root'          => array(
            'basicPage'            => true,
            'addAddon'             => true,
            'manageaddons'         => true,
            'managebasicUsers'     => true,
            'managemoderators'     => true,
            'manageadministrators' => true,
            'manageroots'          => true,
            'managesettings'       => true
        )
    );

    /**
     * @return array
     */
    public static function getPermissionTypes()
    {
        return array_keys(AccessControl::$permissions);
    }

    /**
     * @param string $accessLevel
     *
     * @return bool
     */
    public static function setLevel($accessLevel)
    {
        $role = User::getRole();
        if (is_null($accessLevel))
        {
            return true;
        }

        if ($role === 'unregistered' && $accessLevel === null)
        {
            $allow = true;
        }
        elseif ($role === 'unregistered')
        {
            $allow = false;
        }
        else
        {
            $allow = AccessControl::$permissions[$role][$accessLevel];
        }

        if ($allow === false)
        {
            AccessControl::showAccessDeniedPage();
        }
    }

    /**
     * Show a 404 page
     */
    public static function showAccessDeniedPage()
    {
        header('HTTP/1.0 401 Unauthorized');
        $tpl = new StkTemplate('access-denied.tpl');
        $tpl->assign('ad_reason', htmlspecialchars(_('You do not have permission to access this page.')));
        $tpl->assign('ad_action', htmlspecialchars(_('You will be redirected to the home page.')));
        $tpl->assign('ad_redirect_url', File::rewrite('index.php'));
        echo $tpl;

        exit;
    }
}
