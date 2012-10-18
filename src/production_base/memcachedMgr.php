<?
/********************************************************************************
 * The contents of this file are subject to the Common Public Attribution License
 * Version 1.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 * http://www.wikiarguments.net/license/. The License is based on the Mozilla
 * Public License Version 1.1 but Sections 14 and 15 have been added to cover
 * use of software over a computer network and provide for limited attribution
 * for the Original Developer. In addition, Exhibit A has been modified to be
 * consistent with Exhibit B.
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 *
 * The Original Code is Wikiarguments. The Original Developer is the Initial
 * Developer and is Wikiarguments GbR. All portions of the code written by
 * Wikiarguments GbR are Copyright (c) 2012. All Rights Reserved.
 * Contributor(s):
 *     Andreas Wierz (andreas.wierz@gmail.com).
 *
 * Attribution Information
 * Attribution Phrase (not exceeding 10 words): Powered by Wikiarguments
 * Attribution URL: http://www.wikiarguments.net
 *
 * This display should be, at a minimum, the Attribution Phrase displayed in the
 * footer of the page and linked to the Attribution URL. The link to the Attribution
 * URL must not contain any form of 'nofollow' attribute.
 *
 * Display of Attribution Information is required in Larger Works which are
 * defined in the CPAL as a work which combines Covered Code or portions
 * thereof with code not governed by the terms of the CPAL.
 *******************************************************************************/

/*
* Memcached Connection Interface
* current types:
* - session: Session data used by php instances
* - data: profiles, files, matchings, settings
*/
class MemcachedMgr
{
    public function MemcachedMgr()
    {
        $this->link      = NULL;
        $this->numQuerys = 0;
        $this->numMisses = 0;

        $this->init();
    }

    /*
     * connect to memcached servers
    */
    public function init()
    {
        $this->link_session = new Memcache();
        $this->link_data    = new Memcache();

        foreach(getMemcachedHosts() as $key => $val)
        {
            if($val[0] == 'session')
            {
                $this->link_session->addServer($val[1], $val[2]);
            }else if($val[0] == 'data')
            {
                $this->link_data->addServer($val[1], $val[2]);
            }
        }
    }

    /*
     * write to memcached server
    */
    public function set($type, $prefix, $key, $val)
    {
        $link = $this->getLinkByType($type);

        if($link == -1)
        {
            return false;
        }

        $link->set($prefix.$key, $val);
    }

    /*
     * read from memcached server
    */
    public function get($type, $prefix, $key)
    {
        $link = $this->getLinkByType($type);
        if($link == -1)
        {
            return false;
        }

        $val = $link->get($prefix.$key);
        $this->numQuerys++;

        if($val == false) $this->numMisses++;

        return $val;
    }

    /*
    * delete from memcached server
    */

    public function delete($type, $prefix, $key)
    {
        $link = $this->getLinkByType($type);
        if($link == -1)
        {
            return false;
        }
        return $link->delete($prefix.$key);
    }

    /*
    * get Link to Session Store
    */
    public function getLinkByType($type)
    {
        if($type == 'session')
        {
            return $this->link_session;
        }else if($type == 'data')
        {
            return $this->link_data;
        }else
        {
            return -1;
        }
    }

    public function getNumQuerys()
    {
        return $this->numQuerys;
    }

    public function getNumMisses()
    {
        return $this->numMisses;
    }

    private $link_session;
    private $link_data;
    private $numQuerys;
    private $numMisses;
}
?>
