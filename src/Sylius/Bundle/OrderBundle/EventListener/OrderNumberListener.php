<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\OrderBundle\EventListener;

use Sylius\Bundle\OrderBundle\Generator\OrderNumberGeneratorInterface;
use Sylius\Bundle\OrderBundle\Repository\NumberRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Sets appropriate order number before saving.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class OrderNumberListener
{
    /**
     * Order number generator.
     *
     * @var OrderNumberGeneratorInterface
     */
    protected $generator;

    /**
     * Number repository.
     *
     * @var NumberRepositoryInterface
     */
    protected $numberRepository;

    /**
     * Number manager.
     *
     * @var ObjectManager
     */
    protected $numberManager;

    /**
     * Constructor.
     *
     * @param OrderNumberGeneratorInterface $generator
     * @param NumberRepositoryInterface     $numberRepository
     */
    public function __construct(OrderNumberGeneratorInterface $generator, NumberRepositoryInterface $numberRepository, ObjectManager $numberManager)
    {
        $this->generator = $generator;
        $this->numberRepository = $numberRepository;
        $this->numberManager = $numberManager;
    }

    /**
     * Use generator to add a proper number to order.
     *
     * @param GenericEvent $event
     */
    public function generateOrderNumber(GenericEvent $event)
    {
        $order = $event->getSubject();

        $number = $this->numberRepository->createNew();
        $number->setOrder($order);

        $this->numberManager->persist($number);

        $this->generator->generate($order);
    }
}
