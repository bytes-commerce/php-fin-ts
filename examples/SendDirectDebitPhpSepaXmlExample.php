<?php

declare(strict_types=1);

namespace Fhp\Examples;

use DateInterval;
use DateTime;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Action\SendSEPADirectDebit;
use Fhp\Model\SEPAAccount;

/**
 * Example: Send a SEPA direct debit using phpSepaXml library.
 * Demonstrates how to create and execute a SEPA direct debit using phpFinTS.
 *
 * Note: The phpFinTS library only implements the FinTS protocol. For SEPA direct debits,
 * you need a separate library to produce the SEPA XML data, which is then wrapped
 * into FinTS requests. This example uses the phpSepaXml library
 * (see https://github.com/nemiah/phpSepaXml).
 */
class SendDirectDebitPhpSepaXmlExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Send a SEPA direct debit from a given account.
     *
     * @param SEPAAccount $account The account to debit from
     * @param string $creditorName Name of the creditor (you)
     * @param string $creditorIban Your IBAN
     * @param string $creditorBic Your BIC
     * @param string $creditorIdentifier Your creditor identifier
     * @param string $debitorName Name of the debitor
     * @param string $debitorIban IBAN of the debitor
     * @param string $debitorBic BIC of the debitor
     * @param float $amount Amount to debit
     * @param string $currency Currency (default EUR)
     * @param string|null $description Description/reference
     * @return SendSEPADirectDebit The executed direct debit action
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function sendDirectDebit(
        SEPAAccount $account,
        string $creditorName,
        string $creditorIban,
        string $creditorBic,
        string $creditorIdentifier,
        string $debitorName,
        string $debitorIban,
        string $debitorBic,
        float $amount,
        string $currency = 'EUR',
        ?string $description = null
    ): SendSEPADirectDebit {
        $fints = $this->connection->getFinTs();

        // Build SEPA XML using phpSepaXml library
        $sepaXml = $this->buildSepaXml(
            $creditorName,
            $creditorIban,
            $creditorBic,
            $creditorIdentifier,
            $debitorName,
            $debitorIban,
            $debitorBic,
            $amount,
            $currency,
            $description
        );

        $sendSEPADirectDebit = SendSEPADirectDebit::create($account, $sepaXml);
        $fints->execute($sendSEPADirectDebit);

        // Handle VOP and authentication
        $this->handleVopAndAuthentication($sendSEPADirectDebit);

        // Ensure the direct debit is complete
        $sendSEPADirectDebit->ensureDone();

        return $sendSEPADirectDebit;
    }

    /**
     * Build SEPA direct debit XML using phpSepaXml library.
     */
    private function buildSepaXml(
        string $creditorName,
        string $creditorIban,
        string $creditorBic,
        string $creditorIdentifier,
        string $debitorName,
        string $debitorIban,
        string $debitorBic,
        float $amount,
        string $currency,
        ?string $description
    ): string {
        // Import phpSepaXml classes - requires the library to be installed
        // use nemiah\phpSepaXml\SEPACreditor;
        // use nemiah\phpSepaXml\SEPADebitor;
        // use nemiah\phpSepaXml\SEPADirectDebitBasic;

        $dt = new DateTime();
        $dt->add(new DateInterval('P1D'));

        $sepaDD = new \nemiah\phpSepaXml\SEPADirectDebitBasic([
            'messageID' => time(),
            'paymentID' => time(),
        ]);

        $sepaDD->setCreditor(new \nemiah\phpSepaXml\SEPACreditor([
            'name' => $creditorName,
            'iban' => $creditorIban,
            'bic' => $creditorBic,
            'identifier' => $creditorIdentifier,
        ]));

        $sepaDD->addDebitor(new \nemiah\phpSepaXml\SEPADebitor([
            'transferID' => (string) time(),
            'mandateID' => 'aeicznaeibcnt',
            'mandateDateOfSignature' => '2017-05-05',
            'name' => $debitorName,
            'iban' => $debitorIban,
            'bic' => $debitorBic,
            'amount' => $amount,
            'currency' => $currency,
            'info' => $description ?? (string) time(),
            'requestedCollectionDate' => $dt,
            'sequenceType' => 'OOFF',
            'type' => 'CORE',
        ]));

        return $sepaDD->toXML('pain.008.001.02');
    }

    /**
     * Handle VOP and authentication if required.
     */
    private function handleVopAndAuthentication(SendSEPADirectDebit $action): void
    {
        $vopHelper = new VopHelper($this->connection);
        $vopHelper->handleActionUntilDone($action);
    }

    /**
     * Run this example with sample values.
     */
    public function run(): void
    {
        $fints = $this->connection->getFinTs();

        // Get accounts
        [$getSepaAccounts, $accounts] = $this->connection->fetchAccounts();
        if (empty($accounts)) {
            throw new \RuntimeException('No accounts available');
        }

        $oneAccount = $accounts[0];

        // Example direct debit parameters
        $this->sendDirectDebit(
            $oneAccount,
            'My Company',                    // creditor name
            'DE68210501700012345678',        // creditor IBAN
            'DEUTDEDB400',                   // creditor BIC
            'DE98ZZZ09999999999',            // creditor identifier
            'Max Mustermann',                // debitor name
            'CH9300762011623852957',         // debitor IBAN
            'GENODEF1P15',                   // debitor BIC
            48.78,                           // amount
            'EUR',                           // currency
            'Test direct debit'              // description
        );

        echo "Direct debit completed successfully.\n";
    }
}