<?php
/**
 * File: CacheAll.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2019 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\VarnishWarmer\Controller\Adminhtml\Purge;

use LizardMedia\VarnishWarmer\Console\Command\RegenerateAllCommand;
use LizardMedia\VarnishWarmer\Controller\Adminhtml\Purge;

/**
 * Class CacheAll
 * @package LizardMedia\VarnishWarmer\Controller\Adminhtml\Purge
 */
class CacheAll extends Purge
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->isLocked()) {
            $this->runCommand();
            $this->addProcessNotification();
        } else {
            $this->addProcessLockWarning();
        }

        return $this->getRedirect();
    }

    /**
     * @return string
     */
    protected function getCliCommand(): string
    {
        return RegenerateAllCommand::CLI_COMMAND;
    }
}
