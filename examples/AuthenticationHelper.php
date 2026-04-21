<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetSEPAAccounts;
use Fhp\BaseAction;
use Fhp\CurlException;
use Fhp\FinTs;
use Fhp\Model\FlickerTan\TanRequestChallengeFlicker;
use Fhp\Model\TanRequestChallengeImage;
use Fhp\Protocol\ServerException;
use Fhp\Protocol\UnexpectedResponseException;
use RuntimeException;

/**
 * Helper class for handling strong authentication (TAN) and decoupled authentication.
 * This class provides static methods for handling TAN workflows in FinTS operations.
 */
class AuthenticationHelper
{
    private FinTs $fints;
    private TanModeHelper $tanModeHelper;

    public function __construct(FinTs $fints, TanModeHelper $tanModeHelper)
    {
        $this->fints = $fints;
        $this->tanModeHelper = $tanModeHelper;
    }

    /**
     * Main entry point for handling strong authentication.
     * Dispatches to either TAN or decoupled handling based on the selected TAN mode.
     *
     * @param BaseAction $action Some action that requires strong authentication.
     * @throws CurlException|UnexpectedResponseException|ServerException
     */
    public function handleStrongAuthentication(BaseAction $action): void
    {
        if ($this->fints->getSelectedTanMode()->isDecoupled()) {
            $this->handleDecoupled($action);
        } else {
            $this->handleTan($action);
        }
    }

    /**
     * Handles strong authentication for the case where the user needs to enter a TAN.
     *
     * @param BaseAction $action Some action that requires a TAN.
     * @throws CurlException|UnexpectedResponseException|ServerException
     */
    public function handleTan(BaseAction $action): void
    {
        // Find out what sort of TAN we need, tell the user about it.
        $tanRequest = $action->getTanRequest();
        echo 'The bank requested a TAN.';
        if ($tanRequest->getChallenge() !== null) {
            echo ' Instructions: ' . $tanRequest->getChallenge();
        }
        echo "\n";
        if ($tanRequest->getTanMediumName() !== null) {
            echo 'Please use this device: ' . $tanRequest->getTanMediumName() . "\n";
        }

        // Challenge Image for PhotoTan/ChipTan
        if ($tanRequest->getChallengeHhdUc()) {
            try {
                $flicker = new TanRequestChallengeFlicker($tanRequest->getChallengeHhdUc());
                echo 'There is a challenge flicker.' . PHP_EOL;
                $flickerPattern = $flicker->getFlickerPattern();
                $svg = new \Fhp\Model\FlickerTan\SvgRenderer($flickerPattern);
                echo $svg->getImage();
            } catch (\InvalidArgumentException $e) {
                // was not a flicker
                $challengeImage = new TanRequestChallengeImage($tanRequest->getChallengeHhdUc());
                echo 'There is a challenge image.' . PHP_EOL;
                echo '<img src="data:' . htmlspecialchars($challengeImage->getMimeType()) . ';base64,' . base64_encode($challengeImage->getData()) . '" />' . PHP_EOL;
            }
        }

        // Optional: Instead of printing the above to the console, you can relay the information
        // to the user in any other way. If waiting for the TAN requires you to interrupt this PHP
        // process and the TAN will arrive in a fresh request, you can persist the state.
        if ($optionallyPersistEverything = false) {
            $this->persistState($action);
        }

        // Ask the user for the TAN.
        echo "Please enter the TAN:\n";
        $tan = trim(fgets(STDIN));

        // Optional: If the state was persisted above, we can restore it now.
        if ($optionallyPersistEverything) {
            $this->restoreState();
        }

        echo "Submitting TAN: $tan\n";
        $this->fints->submitTan($action, $tan);
    }

    /**
     * Handles strong authentication for the case where the user needs to confirm
     * the action on another device (decoupled authentication).
     *
     * @param BaseAction $action Some action that requires decoupled authentication.
     * @throws CurlException|UnexpectedResponseException|ServerException
     */
    public function handleDecoupled(BaseAction $action): void
    {
        $tanMode = $this->fints->getSelectedTanMode();
        $tanRequest = $action->getTanRequest();
        echo 'The bank requested authentication on another device.';
        if ($tanRequest->getChallenge() !== null) {
            echo ' Instructions: ' . $tanRequest->getChallenge();
        }
        echo "\n";
        if ($tanRequest->getTanMediumName() !== null) {
            echo 'Please check this device: ' . $tanRequest->getTanMediumName() . "\n";
        }

        if ($optionallyPersistEverything = false) {
            $this->persistState($action);
        }

        if ($tanMode->allowsAutomatedPolling()) {
            echo "Polling server to detect when the decoupled authentication is complete.\n";
            sleep($tanMode->getFirstDecoupledCheckDelaySeconds());
            for ($attempt = 0;
                 $tanMode->getMaxDecoupledChecks() === 0 || $attempt < $tanMode->getMaxDecoupledChecks();
                 ++$attempt
            ) {
                if ($optionallyPersistEverything) {
                    $this->restoreState();
                }

                if ($this->fints->checkDecoupledSubmission($action)) {
                    echo "Confirmed.\n";
                    return;
                }
                echo "Still waiting...\n";

                if ($optionallyPersistEverything) {
                    $this->persistState($action);
                }

                sleep($tanMode->getPeriodicDecoupledCheckDelaySeconds());
            }
            throw new RuntimeException("Not confirmed after $attempt attempts, which is the limit.");
        } elseif ($tanMode->allowsManualConfirmation()) {
            echo "Please type 'done' and hit Return when you've completed the authentication on the other device.\n";
            while (trim(fgets(STDIN)) !== 'done') {
                echo "Try again.\n";
            }
            echo "Confirming that the action is done.\n";
            if (!$this->fints->checkDecoupledSubmission($action)) {
                throw new RuntimeException(
                    "You confirmed that the authentication for action was completed, but the server does not think so."
                );
            }
            echo "Confirmed\n";
        } else {
            throw new \AssertionError('Server allows neither automated polling nor manual confirmation');
        }
    }

    /**
     * Persists the FinTs instance and action to a file for later restoration.
     */
    private function persistState(BaseAction $action): void
    {
        $persistedAction = serialize($action);
        $persistedFints = $this->fints->persist();
        file_put_contents(__DIR__ . '/state.txt', serialize([$persistedFints, $persistedAction]));
    }

    /**
     * Restores the FinTs instance and action from a persisted file.
     */
    private function restoreState(): void
    {
        $restoredState = file_get_contents(__DIR__ . '/state.txt');
        [$persistedInstance, $persistedAction] = unserialize($restoredState);
        // Note: In real usage, you would need to pass the persisted instance to FinTs::new()
        // along with the original options and credentials.
    }
}