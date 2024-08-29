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

namespace ViraXpress\Sales\Block\Order;

use Magento\Sales\Block\Order\Link as OrderLink;

class Link extends OrderLink
{
    /**
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    private function getOrder()
    {
        return $this->_registry->registry('current_order');
    }
    
    /**
     * @inheritdoc
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        if ($this->hasKey()
            && method_exists($this->getOrder(), 'has' . $this->getKey())
            && !$this->getOrder()->{'has' . $this->getKey()}()
        ) {
            return '';
        }

        $highlight = '';

        if ($this->getIsHighlighted()) {
            $highlight = ' currentlinkedclass';
        }

        if ($this->isCurrent()) {
            $html = '<li class="nav item">';
            $html .= '<span class="primary-btn">'
                . $this->escapeHtml(__($this->getLabel()))
                . '</span>';
            $html .= '</li>';
            return $html;
        } else {
            $html = '<li class="nav item' . $highlight . '"><a class="secondary-btn" href="' . $this->escapeHtml($this->getHref()) . '"';
            $html .= $this->getTitle()
                ? ' title="' . $this->escapeHtml(__($this->getTitle())) . '"'
                : '';
            $html .= $this->getAttributesHtml() . '>';

            if ($this->getIsHighlighted()) {
                $html .= '<strong>';
            }

            $html .= $this->escapeHtml(__($this->getLabel()));

            if ($this->getIsHighlighted()) {
                $html .= '</strong>';
            }

            $html .= '</a></li>';
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
