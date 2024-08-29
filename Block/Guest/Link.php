<?php
/**
 * ViraXpress - https://www.viraxpress.com
 *
 * LICENSE AGREEMENT
 *
 * This file is part of the ViraXpress package and is licensed under the ViraXpress license agreement.
 * You can view the full license at:
 * https://www.viraxpress.com/license
 *
 * By utilizing this file, you agree to comply with the terms outlined in the ViraXpress license.
 *
 * DISCLAIMER
 *
 * Modifications to this file are discouraged to ensure seamless upgrades and compatibility with future releases.
 *
 * @category    ViraXpress
 * @package     ViraXpress_Sales
 * @author      ViraXpress
 * @copyright   © 2024 ViraXpress (https://www.viraxpress.com/)
 * @license     https://www.viraxpress.com/license
 */

namespace ViraXpress\Sales\Block\Guest;

use Magento\Customer\Model\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\LayoutFactory;
use ViraXpress\Configuration\Helper\Data;

/**
 * Block representing link with two possible states.
 * "Current" state means link leads to URL equivalent to URL of currently displayed page.
 *
 */
class Link extends \Magento\Sales\Block\Guest\Link
{
    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var DefaultPathInterface
     */
    protected $defaultPath;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param TemplateContext $context
     * @param DefaultPathInterface $defaultPath
     * @param HttpContext $httpContext
     * @param ScopeConfigInterface $scopeConfig
     * @param LayoutFactory $layoutFactory
     * @param Data $helperData
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        DefaultPathInterface $defaultPath,
        HttpContext $httpContext,
        ScopeConfigInterface $scopeConfig,
        LayoutFactory $layoutFactory,
        Data $helperData,
        array $data = []
    ) {
        parent::__construct($context, $defaultPath, $httpContext, $data);
        $this->scopeConfig   = $scopeConfig;
        $this->layoutFactory = $layoutFactory;
        $this->helperData = $helperData;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $enableViraXpress = $this->scopeConfig->getValue('viraxpress_config/general/enable_viraxpress', ScopeInterface::SCOPE_STORE);
        $isViraXpressTheme = $this->helperData->isViraXpressEnable();
        if ($enableViraXpress && $isViraXpressTheme) {
            if ($this->httpContext->getValue(Context::CONTEXT_AUTH)) {
                return '';
            }
            $footerLinks = $this->layoutFactory->create()->createBlock(\Magento\Framework\View\Element\Template::class)
                ->setTemplate('Magento_Theme::html/footer/links.phtml')
                ->setIsHighlighted($this->getIsHighlighted())
                ->setIsCurrent($this->isCurrent())
                ->setLabel($this->getLabel())
                ->setTitle($this->getTitle())
                ->setAttributesHtml($this->getAttributesHtml())
                ->setHref($this->getHref());
            $html = $footerLinks->toHtml();
            return $html;
        }

        return parent::_toHtml();
    }

    /**
     * Generate attributes' HTML code
     *
     * @return string
     */
    private function getAttributesHtml()
    {
        $attributesHtml = '';
        $attributes = $this->getAttributes();
        if ($attributes) {
            foreach ($attributes as $attribute => $value) {
                $attributesHtml .= ' ' . $attribute . '="' . $this->escapeHtml($value) . '"';
            }
        }

        return $attributesHtml;
    }
}
