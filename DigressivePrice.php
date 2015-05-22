<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace DigressivePrice;

use Thelia\Module\BaseModule;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;

class DigressivePrice extends BaseModule
{
    const DOMAIN = 'digressiveprice';

    public function postActivation(ConnectionInterface $con = null)
    {
        parent::postActivation($con);
        if (!is_null($con)) {
            $database = new Database($con);
            $database->insertSql(null, array(__DIR__ . '/Config/create.sql'));
        }
    }

    public function destroy(ConnectionInterface $con = null, $deleteModuleData = false)
    {
        parent::destroy($con, $deleteModuleData);
        if (!is_null($con) && $deleteModuleData === true) {
            $database = new Database($con);
            $database->insertSql(null, array(__DIR__ . '/Config/delete.sql'));
        }
    }
}
