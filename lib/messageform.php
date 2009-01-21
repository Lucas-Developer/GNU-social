<?php
/**
 * Laconica, the distributed open-source microblogging tool
 *
 * Form for posting a direct message
 *
 * PHP version 5
 *
 * LICENCE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Form
 * @package   Laconica
 * @author    Evan Prodromou <evan@controlyourself.ca>
 * @author    Sarven Capadisli <csarven@controlyourself.ca>
 * @copyright 2009 Control Yourself, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://laconi.ca/
 */

if (!defined('LACONICA')) {
    exit(1);
}

require_once INSTALLDIR.'/lib/form.php';

/**
 * Form for posting a direct message
 *
 * @category Form
 * @package  Laconica
 * @author   Evan Prodromou <evan@controlyourself.ca>
 * @author   Sarven Capadisli <csarven@controlyourself.ca>
 * @license  http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link     http://laconi.ca/
 *
 * @see      HTMLOutputter
 */

class MessageForm extends Form
{
    /**
     * User to send a direct message to
     */

    var $to = null;

    /**
     * Pre-filled content of the form
     */

    var $content = null;

    /**
     * Constructor
     *
     * @param HTMLOutputter $out     output channel
     * @param User          $to      user to send a message to
     * @param string        $content content to pre-fill
     */

    function __construct($out=null, $to=null, $content=null)
    {
        parent::__construct($out);

        $this->to      = $to;
        $this->content = $content;
    }

    /**
     * ID of the form
     *
     * @return int ID of the form
     */

    function id()
    {
        return 'message_form';
    }

    /**
     * Action of the form
     *
     * @return string URL of the action
     */

    function action()
    {
        return common_local_url('newmessage');
    }

    /**
     * Data elements
     *
     * @return void
     */

    function formData()
    {
        $user = common_current_user();

        $mutual_users = $user->mutuallySubscribedUsers();

        $mutual = array();

        while ($mutual_users->fetch()) {
            if ($mutual_users->id != $user->id) {
                $mutual[$mutual_users->id] = $mutual_users->nickname;
            }
        }

        $mutual_users->free();
        unset($mutual_users);

        $this->out->dropdown('to', _('To'), $mutual, null, false,
                             $this->to->id);

        $this->out->elementStart('p');

        $this->out->element('textarea', array('id' => 'message_content',
                                              'cols' => 60,
                                              'rows' => 3,
                                              'name' => 'content'),
                            ($this->content) ? $this->content : '');

        $this->out->elementEnd('p');
    }

    /**
     * Action elements
     *
     * @return void
     */

    function formActions()
    {
        $this->out->element('input', array('id' => 'message_send',
                                           'name' => 'message_send',
                                           'type' => 'submit',
                                           'value' => _('Send')));
    }
}