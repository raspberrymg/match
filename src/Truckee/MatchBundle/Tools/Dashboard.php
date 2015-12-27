<?php

/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tools\Dashboard.php


namespace Truckee\MatchBundle\Tools;

use Doctrine\ORM\EntityManager;

/**
 * Description of Dashboard.
 * Brooks <truckeesolutions@gmail.com>
 */
class Dashboard
{
    private $em;
    private $then;
    private $expiry;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $date = new \DateTIme();
        $this->then = date_format($date->sub(new \DateInterval('P30D')), 'Y-m-d');
        $this->expiry = date_format($date->add(new \DateInterval('P1Y')), 'Y-m-d');
    }

    public function oppSearchForm30Day()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.date >= '$this->then' AND a.function = 'oppFormAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function oppSearchFormAll()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.function = 'oppFormAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOppEmails30Day()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.date >= '$this->then' AND a.function = 'showMatchedVolunteersAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOppEmails()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.function = 'showMatchedVolunteersAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function expiringOppEmails30Day()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.date >= '$this->then' AND a.function = 'expiringAlertsAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function expiringOppEmails()
    {
        $sql = "
            SELECT COUNT(a) FROM TruckeeMatchBundle:AdminOutbox a
            WHERE a.function = 'expiringAlertsAction'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newVols30Day()
    {
        $sql = "
            SELECT COUNT(v) FROM TruckeeMatchBundle:Volunteer v
            WHERE v.addDate >= '$this->then'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newVols()
    {
        $sql = '
            SELECT COUNT(v) FROM TruckeeMatchBundle:Volunteer v
         ';

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function volReceivingMailOn()
    {
        $sql = "
            SELECT COUNT(v) FROM TruckeeMatchBundle:Volunteer v
            WHERE v.receiveEmail = '1' AND v.locked = '0'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function volReceivingMailOff()
    {
        $sql = "
            SELECT COUNT(v) FROM TruckeeMatchBundle:Volunteer v
            WHERE v.receiveEmail = '0' AND v.locked = '0'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function volLocked()
    {
        $sql = "
            SELECT COUNT(v) FROM TruckeeMatchBundle:Volunteer v
            WHERE v.locked = '1'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOrg30Day()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Organization o
            WHERE o.addDate >= '$this->then'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOrg()
    {
        $sql = '
            SELECT COUNT(o) FROM TruckeeMatchBundle:Organization o
         ';

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOpps30Day()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Opportunity o
            WHERE o.addDate >= '$this->then'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function newOpps()
    {
        $sql = '
            SELECT COUNT(o) FROM TruckeeMatchBundle:Opportunity o
         ';

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function orgActive()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Organization o
            WHERE o.active = '1'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function orgInactive()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Organization o
            WHERE o.active = '0'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function oppActive()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Opportunity o
            WHERE o.active = '1' AND o.expireDate < '$this->expiry'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function oppInactive()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Opportunity o
            WHERE o.active = '0' AND o.expireDate < '$this->expiry'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }

    public function oppExpired()
    {
        $sql = "
            SELECT COUNT(o) FROM TruckeeMatchBundle:Opportunity o
            WHERE o.expireDate >= '$this->expiry'
         ";

        return  $this->em->createQuery($sql)->getSingleScalarResult();
    }
}
