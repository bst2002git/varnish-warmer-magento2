<?php
namespace LizardMedia\VarnishWarmer\Model\UrlProvider;

use LizardMedia\VarnishWarmer\Api\UrlProvider\CmsPageUrlProviderInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;

class CmsPageUrlProvider implements CmsPageUrlProviderInterface
{
    /**
     * @var ResourceConnectionFactory
     */
    protected $resourceConnectionFactory;

    /**
     * ProductUrlProvider constructor.
     * @param ResourceConnectionFactory $resourceConnectionFactory
     */
    public function __construct(
        ResourceConnectionFactory $resourceConnectionFactory
    ) {
        $this->resourceConnectionFactory = $resourceConnectionFactory;
    }

    public function getActiveUrls(): array
    {
        /** @var ResourceConnection $connection */
        $resourceConnection = $this->resourceConnectionFactory->create();
        /** @var AdapterInterface $connection */
        $connection = $resourceConnection->getConnection();

        $cmsPageIds = $this->getAvailableCmsPageIds();
        $select = $connection
            ->select()
            ->from(
                [
                    'u' => 'url_rewrite'
                ],
                'request_path'
            )->where(
                'u.entity_type = ?',
                'cms-page'
            )->where(
                'u.entity_id IN (' . implode(',', $cmsPageIds) . ')'
            );

        return $connection->fetchAll($select);
    }

    /**
     * @return array
     */
    protected function getAvailableCmsPageIds(): array
    {
        /** @var ResourceConnection $connection */
        $resourceConnection = $this->resourceConnectionFactory->create();
        /** @var AdapterInterface $connection */
        $connection = $resourceConnection->getConnection();

        $select = $connection
            ->select()
            ->from(
                [
                    'p' => 'cms_page'
                ],
                'page_id'
            )->where(
                'p.is_active = 1'
            );

        return array_map(function ($cmsPage) {
            return $cmsPage['page_id'];
        }, $connection->fetchAll($select));
    }
}
