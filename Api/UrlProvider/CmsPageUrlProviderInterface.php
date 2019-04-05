<?php
namespace LizardMedia\VarnishWarmer\Api\UrlProvider;

interface CmsPageUrlProviderInterface
{
    public function getActiveUrls(): array;
}
