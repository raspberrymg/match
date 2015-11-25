<?php
/*
 * This file is part of the Truckee\Match package.
 * 
 * (c) George W. Brooks
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Truckee\MatchBundle\Tools\AdminMailer.php


namespace Truckee\MatchBundle\Tools;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Description of AdminMailer.
 *
 * @author George
 */
class AdminMailer
{
    protected $mailer;
    protected $twig;
    protected $address;
    protected $em;
    protected $tool;
    protected $parameters;
    protected $router;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig,
                                $address, Toolbox $tool,
                                array $parameters, Router $router)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->address = $address;
        $this->tool = $tool;
        $this->parameters = $parameters;
        $this->router = $router;
    }

    /**
     * Send eblast to selected volunteers re: new opportunity
     * called by AdminController::showMatchedVolunteersAction().
     */
    public function sendNewOppMail($bcc, $opportunity, $criteria)
    {
        $bccAddresses = [];
        foreach ($bcc as $volunteer) {
            $bccAddresses[] = $volunteer->getEmail();
        }
        $body = $this->twig->render(
            'Admin/newOppEmail.html.twig', array('opportunity' => $opportunity),
            'text/html');
        $message = \Swift_Message::newInstance()
            ->setSubject('New opportunity')
            ->setFrom($this->address)
            ->setBcc($bccAddresses)
            ->setContentType('text/html')
            ->setBody(
            $body
            )
        ;

        $this->mailer->send($message);
        $recipientCount = $this->recipientCount($message);

        $this->adminUpdateMessage($message);

        if (0 !== $recipientCount) {
            //record search criteria
//                $tool = $this->container->get('truckee_match.toolbox');
            $this->tool->setSearchRecord($criteria, 'volunteer');
            $oppId = $opportunity->getId();
            $orgId = $opportunity->getOrganization()->getId();
            $recipientArray = [];
            foreach ($bcc as $recipient) {
                $recipientArray['function'] = 'showMatchedVolunteersAction';
                $recipientArray['messageType'] = 'bcc';
                $recipientArray['oppId'] = $oppId;
                $recipientArray['orgId'] = $orgId;
                $recipientArray['id'] = $recipient->getId();
                $recipientArray['userType'] = 'volunteer';
            }
            $this->tool->populateAdminOutbox($recipientArray);
        }

        return $recipientCount;
    }

    /**
     * Send organization alert on expiring opportunities
     * called by AdminController::expiringAlertsAction().
     */
    public function sendExpiringAlerts($opportunities)
    {
        $expiringOppData = $this->getExpiringOpportunityData($opportunities);
        $adminRecipients = $this->tool->getAdminRecipients();
        $recipientCount = 0;
        foreach ($expiringOppData['expiring'] as $opp) {
            $user = $opp['user'];
            $addressee = $user->getEmail();
            $message = \Swift_Message::newInstance()
                ->setSubject('Expiring opportunities')
                ->setFrom($this->address)
                ->setTo($addressee)
                ->setBcc($adminRecipients)
                ->setContentType('text/html')
                ->setBody(
                $this->twig->render(
                    'Admin/expiringOppEmail.html.twig',
                    array(
                    'expiring' => $opp,
                    ), 'text/html'
                )
                )
            ;
            $this->mailer->send($message);
            ++$recipientCount;
        }

        //populate admin_outbox
        $recipientArray = [];
        foreach ($expiringOppData['expiring'] as $key => $value) {
            $recipientId = $key;
            foreach ($value['oppData'] as $opp) {
                $recipientArray['function'] = 'expiringAlertsAction';
                $recipientArray['messageType'] = 'to';
                $recipientArray['oppId'] = $opp['oppId'];
                $recipientArray['orgId'] = $opp['orgId'];
                $recipientArray['id'] = $recipientId;
                $recipientArray['userType'] = 'staff';
            }
            $this->tool->populateAdminOutbox($recipientArray);
        }

        return [
            'nOrgs' => $expiringOppData['stats']['nOrgs'],
            'nOpps' => $expiringOppData['stats']['nOpps'],
            'nRecipients' => $recipientCount,
        ];
    }

    /**
     * Alert admins to new org being created
     * called by RegistrationListener::onRegistrationSuccess().
     */
    public function sendNewOrganization($organization)
    {
        $adminRecipients = $this->tool->getAdminRecipients();
        $message = \Swift_Message::newInstance()
            ->setSubject('New organization')
            ->setFrom($this->address)
            ->setTo($adminRecipients)
            ->setContentType('text/html')
            ->setBody(
            $this->twig->render(
                'Email/new_organization.html.twig',
                array(
                'organization' => $organization,
                ), 'text/html'
            )
            )
        ;

        return $this->mailer->send($message);
    }

    /**
     * Send notice re: activated organization
     * called by AdminController::activateOrgAction().
     */
    public function activateOrgMail($organization, $to)
    {
        $recipient = [];
        foreach ($to as $user) {
            $recipient[] = $user->getEmail();
        }
        $adminRecipients = $this->tool->getAdminRecipients();
        if (!empty($recipient)) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Organization now active')
                ->setFrom($this->address)
                ->setTo($recipient)
                ->setCc($adminRecipients)
                ->setContentType('text/html')
                ->setBody(
                $this->twig->render(
                    'Admin/activatedOrgEmail.html.twig',
                    array(
                    'organization' => $organization,
                    ), 'text/html'
                )
            );

            return $this->mailer->send($message);
        } else {
            return 0;
        }
    }

    /**
     * Send e-mail re volunteer interest
     * called by DefaultController::oppFormAction().
     */
    public function sendOppInterestEmail($to, $from, $subject, $content)
    {
        $recipient = [];
        foreach ($to as $user) {
            $recipient[] = $user->getEmail();
        }
        $adminRecipients = $this->tool->getAdminRecipients();
        if (!empty($recipient)) {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($recipient)
                ->setBcc($adminRecipients)
                ->setContentType('text/html')
                ->setBody(
                $this->twig->render(
                    'TruckeeMatchBundle:Default:onlineOppEmailContent.html.twig',
                    array(
                    'content' => $content,
                    'recipient' => $recipient,
                    ), 'text/html'
                )
                )
            ;
            $headers = $message->getHeaders();
            $headers->addTextHeader('Opportunity-Interest', 'true');
            $this->mailer->send($message);
            $recipientCount = $this->recipientCount($message);

            return $recipientCount;
        } else {
            return 0;
        }
    }

    private function recipientCount($message)
    {
        $to = count($message->getTo());
        $cc = count($message->getCc());
        $bcc = count($message->getBcc());

        return $to + $cc + $bcc;
    }

    private function adminUpdateMessage($message)
    {
        $adminRecipients = $this->tool->getAdminRecipients();
        if ('New opportunity' === $message->getSubject()) {
            //send e-blast re: volunteers notified
            $count = $this->recipientCount($message);
            $body = $message->getBody();
            $adminMessage = \Swift_Message::newInstance()
                ->setSubject('E-blast results')
                ->setFrom($this->address)
                ->setTo($adminRecipients)
                ->setContentType('text/html')
                ->setBody(
                "$count e-mails have been sent with the following:".'<br>'.
                $body
                )
            ;

            $this->mailer->send($adminMessage);

            return;
        }
    }

    private function getExpiringOpportunityData($opportunities)
    {
        $nOpps = count($opportunities);
        $expiring = [];
        $nOrgs = 0;
        $org = '';
        foreach ($opportunities as $opp) {
            if ($org != $opp->getOrganization()) {
                $org = $opp->getOrganization();
                ++$nOrgs;
            }
            foreach ($org->getStaff() as $user) {
                $id = $user->getId();
                if (!array_key_exists($id, $expiring)) {
                    $expiring[$id]['user'] = $user;
                    $expiring[$id]['orgName'] = $org->getOrgName();
                    $expiring[$id]['oppData'] = [];
                }
                $expiring[$id]['oppData'][] = [
                    'oppId' => $opp->getId(),
                    'orgId' => $org->getId(),
                    'oppName' => $opp->getOppName(),
                    'expireDate' => $opp->getExpireDate(),
                ];
            }
        }

        return [
            'expiring' => $expiring,
            'stats' => [
                'nOpps' => $nOpps,
                'nOrgs' => $nOrgs,
            ],
        ];
    }

    /**
     * send registration confirmation to added staff
     * called by StaffController::addAction().
     */
    public function sendConfirmationEmailMessage($user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm',
            array('token' => $user->getConfirmationToken()), true);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );

        $this->sendMessage($template, $context,
            $this->parameters['from_email']['confirmation'],
            $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                    ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
