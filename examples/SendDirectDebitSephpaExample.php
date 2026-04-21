<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetSEPAAccounts;
use Fhp\Action\SendSEPADirectDebit;
use Fhp\Model\SEPAAccount;

/**
 * Example: Send a SEPA direct debit using Sephpa library.
 * Demonstrates how to create and execute a SEPA direct debit using phpFinTS.
 *
 * Note: The phpFinTS library only implements the FinTS protocol. For SEPA direct debits,
 * you need a separate library to produce the SEPA XML data, which is then wrapped
 * into FinTS requests. This example uses the Sephpa library
 * (see https://github.com/AbcAeffchen/Sephpa).
 */
class SendDirectDebitSephpaExample
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
     * @param string $applicationName Name of your application
     * @param string $messageId Message identifier
     * @param string $sepaXml The SEPA XML data (pain.008.003.02)
     * @return SendSEPADirectDebit The executed direct debit action
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function sendDirectDebit(
        SEPAAccount $account,
        string $applicationName,
        string $messageId,
        string $sepaXml
    ): SendSEPADirectDebit {
        $fints = $this->connection->getFinTs();

        $sendSEPADirectDebit = SendSEPADirectDebit::create($account, $sepaXml);
        $fints->execute($sendSEPADirectDebit);

        // Handle VOP and authentication
        $this->handleVopAndAuthentication($sendSEPADirectDebit);

        // Ensure the direct debit is complete
        $sendSEPADirectDebit->ensureDone();

        return $sendSEPADirectDebit;
    }

    /**
     * Create a SephpaDirectDebit object for generating SEPA XML.
     */
    public static function createDirectDebitFile(
        string $applicationName,
        string $messageId,
        string $sepaVersion = 'pain.008.003.02'
    ): \AbcAeffchen\Sephpa\SephpaDirectDebit {
        return new \AbcAeffchen\Sephpa\SephpaDirectDebit(
            $applicationName,
            $messageId,
            \AbcAeffchen\Sephpa\SephpaDirectDebit::SEPA_PAIN_008_003_02
        );
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

        // Create a SephpaDirectDebit object
        $directDebitFile = self::createDirectDebitFile(
            'Name of Application',
            'Message Identifier'
        );

        // Configure the Direct Debit File
        // $directDebitCollection = $directDebitFile->addCollection([...]);
        // $directDebitCollection->addPayment([...]);
        // See documentation: https://github.com/AbcAeffchen/Sephpa

        $xml = $directDebitFile->generateOutput(['zipToOneFile' => false])[0]['data'];

        // Send the direct debit
        $this->sendDirectDebit(
            $oneAccount,
            'Name of Application',
            'Message Identifier',
            $xml
        );

        echo "Direct debit completed successfully.\n";
    }
}