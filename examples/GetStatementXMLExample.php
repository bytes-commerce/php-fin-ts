<?php

declare(strict_types=1);

namespace Fhp\Examples;

use DateTime;
use Fhp\Action\GetSEPAAccounts;
use Fhp\Action\GetStatementOfAccountXML;
use Fhp\Model\SEPAAccount;

/**
 * Example: Get statement of account in XML (CAMT) format.
 * Demonstrates how to retrieve raw XML statements using phpFinTS.
 */
class GetStatementXMLExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get statements in raw XML format for a specific account and date range.
     *
     * @param SEPAAccount $account The account to get statements for
     * @param DateTime $from Start date
     * @param DateTime $to End date
     * @return string[] Array of XML strings, one per statement
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function getStatementXml(SEPAAccount $account, DateTime $from, DateTime $to): array
    {
        $fints = $this->connection->getFinTs();

        $getStatementXML = GetStatementOfAccountXML::create($account, $from, $to);
        $fints->execute($getStatementXML);

        if ($getStatementXML->needsTan()) {
            $this->connection->getAuthHelper()->handleStrongAuthentication($getStatementXML);
        }

        return $getStatementXML->getBookedXML();
    }

    /**
     * Get XML statements for the first available account.
     */
    public function getStatementXmlForFirstAccount(DateTime $from, DateTime $to): array
    {
        $accounts = $this->getAccounts();
        if (empty($accounts)) {
            throw new \RuntimeException('No accounts available');
        }
        return $this->getStatementXml($accounts[0], $from, $to);
    }

    /**
     * Get all accounts from the connection.
     *
     * @return SEPAAccount[]
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    private function getAccounts(): array
    {
        return $this->connection->fetchAccounts()[1];
    }

    /**
     * Display XML statements and parse them.
     */
    public function displayXmlStatements(): void
    {
        $from = new DateTime('2022-07-15');
        $to = new DateTime();
        $xmlStrings = $this->getStatementXmlForFirstAccount($from, $to);

        foreach ($xmlStrings as $index => $xml) {
            echo "XML Document " . ($index + 1) . ":" . PHP_EOL;
            $doc = $this->parseXml($xml);
            if ($doc !== false) {
                echo "Successfully loaded XML document" . PHP_EOL;
            }
        }
    }

    /**
     * Parse XML string into a SimpleXMLElement.
     */
    private function parseXml(string $xml): \SimpleXMLElement|false
    {
        return simplexml_load_string($xml);
    }

    /**
     * Run this example.
     */
    public function run(): void
    {
        $this->displayXmlStatements();
    }
}