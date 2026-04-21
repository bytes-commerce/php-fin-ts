<?php

declare(strict_types=1);

namespace Fhp\Examples;

use DateInterval;
use DateTime;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Action\SendSEPATransfer;
use Fhp\Model\SEPAAccount;

/**
 * Example: Send a SEPA transfer.
 * Demonstrates how to create and execute a SEPA transfer using phpFinTS.
 *
 * Note: The phpFinTS library only implements the FinTS protocol. For SEPA transfers,
 * you need a separate library to produce the SEPA XML data, which is then wrapped
 * into FinTS requests. This example uses the phpSepaXml library
 * (see https://github.com/nemiah/phpSepaXml).
 */
class SendTransferExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Send a SEPA transfer from a given account.
     *
     * @param SEPAAccount $account The account to send from
     * @param string $targetName Name of the recipient
     * @param string $targetIban IBAN of the recipient
     * @param string $targetBic BIC of the recipient
     * @param float $amount Amount to transfer
     * @param string $currency Currency (default EUR)
     * @param string|null $description Description/end-to-end reference
     * @return SendSEPATransfer The executed transfer action
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function sendTransfer(
        SEPAAccount $account,
        string $targetName,
        string $targetIban,
        string $targetBic,
        float $amount,
        string $currency = 'EUR',
        ?string $description = null
    ): SendSEPATransfer {
        $fints = $this->connection->getFinTs();

        // Build SEPA XML using phpSepaXml library
        $sepaXml = $this->buildSepaXml(
            $account->getIban(),
            $account->getBic(),
            $account->getAccountNumber(),
            $targetName,
            $targetIban,
            $targetBic,
            $amount,
            $currency,
            $description
        );

        $sendSEPATransfer = SendSEPATransfer::create($account, $sepaXml);
        $fints->execute($sendSEPATransfer);

        // Handle VOP and authentication
        $this->handleVopAndAuthentication($sendSEPATransfer);

        // Ensure the transfer is complete
        $sendSEPATransfer->ensureDone();

        return $sendSEPATransfer;
    }

    /**
     * Build SEPA XML for the transfer.
     * This uses the phpSepaXml library.
     */
    private function buildSepaXml(
        string $debitorIban,
        string $debitorBic,
        string $debitorName,
        string $creditorName,
        string $creditorIban,
        string $creditorBic,
        float $amount,
        string $currency,
        ?string $description
    ): string {
        // Import phpSepaXml classes - this requires the library to be installed
        // use nemiah\phpSepaXml\SEPACreditor;
        // use nemiah\phpSepaXml\SEPADebitor;
        // use nemiah\phpSepaXml\SEPATransfer;

        $dt = new DateTime();
        $dt->add(new DateInterval('P1D'));

        $sepaTransfer = new \nemiah\phpSepaXml\SEPATransfer([
            'messageID' => time(),
            'paymentID' => time(),
        ]);

        $sepaTransfer->setDebitor(new \nemiah\phpSepaXml\SEPADebitor([
            'name' => $debitorName,
            'iban' => $debitorIban,
            'bic' => $debitorBic,
        ]));

        $sepaTransfer->addCreditor(new \nemiah\phpSepaXml\SEPACreditor([
            'info' => $description ?? (string) time(),
            'name' => $creditorName,
            'iban' => $creditorIban,
            'bic' => $creditorBic,
            'amount' => $amount,
            'currency' => $currency,
            'reqestedExecutionDate' => $dt,
        ]));

        return $sepaTransfer->toXML();
    }

    /**
     * Handle VOP (Verification of Payee) and authentication if required.
     */
    private function handleVopAndAuthentication(SendSEPATransfer $action): void
    {
        $vopHelper = new VopHelper($this->connection);
        $vopHelper->handleActionUntilDone($action);
    }

    /**
     * Run this example - send a sample transfer.
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

        // Example transfer parameters
        $this->sendTransfer(
            $oneAccount,
            'Max Mustermann',           // recipient name
            'CH9300762011623852957',   // recipient IBAN
            'GENODEF1P15',              // recipient BIC
            48.78,                      // amount
            'EUR',                      // currency
            'Test transfer'             // description
        );

        echo "Transfer completed successfully.\n";
    }
}