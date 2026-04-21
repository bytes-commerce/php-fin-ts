<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\BaseAction;
use Fhp\CurlException;
use Fhp\FinTs;
use Fhp\Protocol\ServerException;
use Fhp\Protocol\UnexpectedResponseException;
use RuntimeException;

/**
 * Helper class for handling Verification of Payee (VOP) workflows.
 * This class provides methods for handling VOP confirmation, polling, and TAN requirements.
 */
class VopHelper
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Main entry point: handle an action until it is done.
     * This loops through TAN, polling, and VOP confirmation as needed.
     */
    public function handleActionUntilDone(BaseAction $action): void
    {
        while (!$action->isDone()) {
            if ($action->needsTan()) {
                $this->getAuthHelper()->handleStrongAuthentication($action);
            } elseif ($action->needsPollingWait()) {
                $this->handlePollingWait($action);
            } elseif ($action->needsVopConfirmation()) {
                $this->handleVopConfirmation($action);
            } else {
                throw new \AssertionError(
                    'Action is not done but also does not need anything to be done. Did you execute() it?'
                );
            }
        }
    }

    /**
     * Handle a polling wait scenario.
     */
    public function handlePollingWait(BaseAction $action): void
    {
        $fints = $this->connection->getFinTs();

        // Tell the user what the bank had to say (if anything).
        $pollingInfo = $action->getPollingInfo();
        if ($infoText = $pollingInfo->getInformationForUser()) {
            echo $infoText . PHP_EOL;
        }

        // Optional: Persist state for web applications
        if ($optionallyPersistEverything = false) {
            $this->persistState($action);
        }

        // Wait for the prescribed amount of time
        $waitSecs = $pollingInfo->getNextAttemptInSeconds() ?: 5;
        echo "Waiting for $waitSecs seconds before polling the bank server again..." . PHP_EOL;
        sleep($waitSecs);

        // Optional: Restore state if persistence was used
        if ($optionallyPersistEverything) {
            $this->restoreState();
        }

        $fints->pollAction($action);
    }

    /**
     * Handle VOP confirmation - ask user to confirm the transfer.
     */
    public function handleVopConfirmation(BaseAction $action): void
    {
        $fints = $this->connection->getFinTs();

        $vopConfirmationRequest = $action->getVopConfirmationRequest();
        if ($infoText = $vopConfirmationRequest->getInformationForUser()) {
            echo $infoText . PHP_EOL;
        }

        echo $this->getVerificationResultDescription($vopConfirmationRequest) . PHP_EOL;

        // Optional: Persist state for web applications
        if ($optionallyPersistEverything = false) {
            $this->persistState($action);
        }

        echo "In light of the information provided above, do you want to confirm the execution of the transfer?" . PHP_EOL;
        echo "If so, please type 'confirm' and hit Return. Otherwise, please kill this PHP process." . PHP_EOL;

        while (trim(fgets(STDIN)) !== 'confirm') {
            echo "Try again." . PHP_EOL;
        }

        echo "Confirming the transfer." . PHP_EOL;
        $fints->confirmVop($action);
        echo "Confirmed" . PHP_EOL;
    }

    /**
     * Get a human-readable description of the verification result.
     */
    private function getVerificationResultDescription($vopConfirmationRequest): string
    {
        return match ($vopConfirmationRequest->getVerificationResult()) {
            \Fhp\Model\VopVerificationResult::CompletedFullMatch =>
                'The bank says the payee information matched perfectly, but still wants you to confirm.',
            \Fhp\Model\VopVerificationResult::CompletedCloseMatch =>
                'The bank says the payee information does not match exactly, so please confirm.',
            \Fhp\Model\VopVerificationResult::CompletedPartialMatch =>
                'The bank says the payee information does not match for all transfers, so please confirm.',
            \Fhp\Model\VopVerificationResult::CompletedNoMatch =>
                'The bank says the payee information does not match, but you can still confirm the transfer if you want.',
            \Fhp\Model\VopVerificationResult::NotApplicable =>
                $vopConfirmationRequest->getVerificationNotApplicableReason() == null
                    ? 'The bank did not provide any information about payee verification, but you can still confirm.'
                    : 'The bank says: ' . $vopConfirmationRequest->getVerificationNotApplicableReason(),
            default => 'The bank failed to provide information about payee verification, but you can still confirm.',
        };
    }

    /**
     * Get the authentication helper.
     */
    private function getAuthHelper(): AuthenticationHelper
    {
        return $this->connection->getAuthHelper();
    }

    /**
     * Persist state for later restoration.
     */
    private function persistState(BaseAction $action): void
    {
        $fints = $this->connection->getFinTs();
        $persistedAction = serialize($action);
        $persistedFints = $fints->persist();
        file_put_contents(__DIR__ . '/state.txt', serialize([$persistedFints, $persistedAction]));
    }

    /**
     * Restore state from a previous persistence.
     */
    private function restoreState(): void
    {
        $restoredState = file_get_contents(__DIR__ . '/state.txt');
        [$persistedInstance, $persistedAction] = unserialize($restoredState);
        // In real usage, you would recreate the FinTs instance with the persisted state
    }
}