<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\OrderProcessing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\AddressingBundle\Matcher\ZoneMatcherInterface;
use Sylius\Bundle\CoreBundle\Model\Order;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;
use Sylius\Bundle\ResourceBundle\Model\RepositoryInterface;
use Sylius\Bundle\SettingsBundle\Model\Settings;
use Sylius\Bundle\TaxationBundle\Calculator\CalculatorInterface;
use Sylius\Bundle\TaxationBundle\Resolver\TaxRateResolverInterface;

/**
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class TaxationProcessorSpec extends ObjectBehavior
{
    function let(
        RepositoryInterface $adjustmentRepository,
        CalculatorInterface $calculator,
        TaxRateResolverInterface $taxRateResolver,
        ZoneMatcherInterface $zoneMatcher,
        Settings $taxationSettings
    )
    {
        $this->beConstructedWith($adjustmentRepository, $calculator, $taxRateResolver, $zoneMatcher, $taxationSettings);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\OrderProcessing\TaxationProcessor');
    }

    function it_implements_Sylius_taxation_processor_interface()
    {
        $this->shouldImplement('Sylius\Bundle\CoreBundle\OrderProcessing\TaxationProcessorInterface');
    }

    function it_doesnt_apply_any_taxes_if_order_has_no_items(OrderInterface $order)
    {
        $order->getItems()->willReturn(array());
        $order->removeTaxAdjustments()->shouldBeCalled();
        $order->addAdjustment(Argument::any())->shouldNotBeCalled();

        $this->applyTaxes($order);
    }

    function it_removes_existing_tax_adjustments(OrderInterface $order)
    {
        $order->getItems()->willReturn(array());
        $order->removeTaxAdjustments()->shouldBeCalled();

        $this->applyTaxes($order);
    }
}
