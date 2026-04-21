<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\Action\GetBalance;
use Fhp\Action\GetSEPAAccounts;
use Fhp\FinTs;
use Fhp\Options\Credentials;
use Fhp\Options\FinTsOptions;
use stdClass;

/**
 * Example: Browser-based FinTS application.
 * Demonstrates how phpFinTS can be used in a web application setting.
 *
 * WARNING: This implementation serves only to demonstrate how the phpFinTS library
 * can be used in a web application setting. It follows no coding best practices.
 * Given that these applications handle sensitive data like bank credentials and
 * financial information, any real application should follow security-related best
 * practices like XSRF protection, encryption, etc., and this application should
 * not be deployed to a publicly accessible web server.
 *
 * To run it:
 * 1. $ php -S 0.0.0.0:8080 -t ./examples
 * 2. http://localhost:8080/BrowserExample.php
 */
class BrowserExample
{
    private ?FinTs $fints = null;
    private ?Credentials $credentials = null;
    private ?FinTsOptions $options = null;
    private ?string $persistedAction = null;

    /**
     * Handle a JSON request from the browser.
     *
     * @param stdClass $request The parsed JSON request
     * @param string $sessionId Session identifier
     * @return array Response data to be JSON-encoded
     * @throws \Fhp\CurlException
     * @throws \Fhp\Protocol\UnexpectedResponseException
     * @throws \Fhp\Protocol\ServerException
     */
    public function handleRequest(stdClass $request, string $sessionId): array
    {
        $this->initializeSession($request, $sessionId);

        return match ($request->action) {
            'getTanModes' => $this->getTanModes(),
            'getTanMedia' => $this->getTanMedia($request),
            'login' => $this->login($request),
            'submitTan' => $this->submitTan($request),
            'checkDecoupledSubmission' => $this->checkDecoupledSubmission(),
            'getBalances' => $this->getBalances(),
            'logout' => $this->logout(),
            default => throw new \InvalidArgumentException("Unknown action {$request->action}"),
        };
    }

    /**
     * Initialize the session from the request.
     */
    private function initializeSession(stdClass $request, string $sessionId): void
    {
        $this->options = new FinTsOptions();
        $this->options->productName = $request->productName;
        $this->options->productVersion = $request->productVersion;
        $this->options->url = $request->url;
        $this->options->bankCode = $request->bankCode;

        $this->credentials = Credentials::create($request->username, $request->pin);

        // Try to restore persisted session
        $sessionFile = __DIR__ . "/session_$sessionId.data";
        if (file_exists($sessionFile)) {
            $sessionData = unserialize(file_get_contents($sessionFile));
            [$persistedInstance, $this->persistedAction] = $sessionData;
            $this->fints = FinTs::new($this->options, $this->credentials, $persistedInstance);
        } else {
            $this->fints = FinTs::new($this->options, $this->credentials);
        }
    }

    /**
     * Get available TAN modes.
     */
    private function getTanModes(): array
    {
        return array_map(function ($mode) {
            return [
                'id' => $mode->getId(),
                'name' => $mode->getName(),
                'isDecoupled' => $mode->isDecoupled(),
                'needsTanMedium' => $mode->needsTanMedium(),
            ];
        }, array_values($this->fints->getTanModes()));
    }

    /**
     * Get TAN media for a specific TAN mode.
     */
    private function getTanMedia(stdClass $request): array
    {
        return array_map(function ($medium) {
            return ['name' => $medium->getName(), 'phoneNumber' => $medium->getPhoneNumber()];
        }, $this->fints->getTanMedia((int) $request->tanmode));
    }

    /**
     * Perform login.
     */
    private function login(stdClass $request): array
    {
        $this->fints->selectTanMode((int) $request->tanmode, $request->tanmedium ?? null);
        $login = $this->fints->login();

        if ($login->needsTan()) {
            $this->persistedAction = serialize($login);
            return ['result' => 'needsTan', 'challenge' => $login->getTanRequest()->getChallenge()];
        }

        return ['result' => 'success'];
    }

    /**
     * Submit a TAN for the pending action.
     */
    private function submitTan(stdClass $request): array
    {
        $action = unserialize($this->persistedAction);
        $this->fints->submitTan($action, $request->tan);
        $this->persistedAction = null;
        return ['result' => 'success'];
    }

    /**
     * Check decoupled submission status.
     */
    private function checkDecoupledSubmission(): array
    {
        $action = unserialize($this->persistedAction);
        if ($this->fints->checkDecoupledSubmission($action)) {
            $this->persistedAction = null;
            return ['result' => 'success'];
        }

        return ['result' => 'ongoing'];
    }

    /**
     * Get account balances.
     */
    private function getBalances(): array
    {
        $getAccounts = GetSEPAAccounts::create();
        $this->fints->execute($getAccounts);

        if ($getAccounts->needsTan()) {
            throw new \Fhp\UnsupportedException(
                "This simple example code does not support strong authentication on GetSEPAAccounts calls."
            );
        }

        $getBalances = GetBalance::create($getAccounts->getAccounts()[0], true);
        $this->fints->execute($getBalances);

        if ($getBalances->needsTan()) {
            throw new \Fhp\UnsupportedException(
                "This simple example code does not support strong authentication on GetBalance calls."
            );
        }

        $balances = [];
        foreach ($getBalances->getBalances() as $balance) {
            $sdo = $balance->getGebuchterSaldo();
            $balances[$balance->getAccountInfo()->getAccountNumber()] = $sdo->getAmount() . ' ' . $sdo->getCurrency();
        }

        return $balances;
    }

    /**
     * Logout and close the session.
     */
    private function logout(): array
    {
        $this->fints->close();
        return ['result' => 'success'];
    }

    /**
     * Persist the current session state.
     */
    public function persist(string $sessionId): void
    {
        $sessionFile = __DIR__ . "/session_$sessionId.data";
        file_put_contents($sessionFile, serialize([$this->fints->persist(), $this->persistedAction]));
    }

    /**
     * Get the FinTs instance for this session.
     */
    public function getFinTs(): FinTs
    {
        return $this->fints;
    }

    /**
     * Get the HTML for the browser example form.
     */
    public static function getHtml(): string
    {
        return '<!doctype html>
<html lang="de">
<head>
    <title>phpFinTS Beispielanwendung</title>
    <style>
        fieldset { border: none; }
        td:first-child { text-align: right; }
    </style>
</head>
<body>
    <h1>phpFinTS Beispielanwendung</h1>
    <p>Diese Beispielanwendung meldet sich im Onlinebanking an und holt die aktuellen Kontostände ab.</p>
    <p><b>HINWEIS:</b> Wenn sich Bank oder Benutzer ändern, sollte diese Seite erst neu geladen werden!</p>
    <form id="form">
        <input type="hidden" name="sessionid" id="sessionid"/>
        <fieldset id="fieldset">
            <table>
                <tr><td><a target="_blank" href="https://www.hbci-zka.de/register/prod_register.htm">Registrierungsnummer</a>:</td>
                    <td><input type="text" name="productName"/></td></tr>
                <tr><td>Produktversion:</td><td><input type="text" name="productVersion" value="1.0"/></td></tr>
                <tr><td>Bank URL:</td><td><input type="text" name="url" value="https://banking-dkb.s-fints-pt-dkb.de/fints30"/></td></tr>
                <tr><td>Bankleitzahl:</td><td><input type="text" name="bankCode" value="12030000"/></td></tr>
                <tr><td>Benutzerkennung:</td><td><input type="text" name="username"/></td></tr>
                <tr><td>Passwort/PIN:</td><td><input type="password" name="pin"/></td></tr>
                <tr id="tanmodeRow" style="display: none"><td>TAN-Modus:</td><td><select name="tanmode" id="tanmode"></select></td></tr>
                <tr id="tanmediumRow" style="display: none"><td>TAN-Medium:</td><td><select name="tanmedium" id="tanmedium"></select></td></tr>
                <tr><td></td><td><button id="submit">Los geht\'s</button></td></tr>
            </table>
        </fieldset>
    </form>
    <pre id="output"></pre>
    <script>
        document.getElementById(\'sessionid\').value = new Date().getTime();
        document.getElementById(\'submit\').onclick = async (e) => {
            e.preventDefault();
            const form = document.getElementById(\'form\');
            const formData = Object.fromEntries([...new FormData(form)]);
            const fieldset = document.getElementById(\'fieldset\');
            async function post(action, additionalParams) {
                const response = await fetch(\'BrowserExample.php\', {
                    method: \'POST\',
                    headers: { \'Content-Type\': \'application/json\' },
                    body: JSON.stringify({action, ...formData, ...additionalParams}),
                });
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}: ${response.statusText}`);
                }
                if (response.headers.get(\'Content-Type\').startsWith(\'text/html\')) {
                    document.getElementById(\'output\').innerHTML = await response.text();
                    throw new Error(\'PHP error, click OK to see details below.\');
                }
                return response.json();
            }

            fieldset.disabled = true;
            document.getElementById(\'output\').innerText = \'\';
            try {
                const tanmode = document.getElementById(\'tanmode\');
                if (!tanmode.value) {
                    while (tanmode.firstChild) tanmode.firstChild.remove();
                    for (const mode of await post(\'getTanModes\')) {
                        const option = document.createElement(\'option\');
                        option.setAttribute(\'value\', mode.id);
                        option.appendChild(document.createTextNode(mode.name));
                        option.tanmode = mode;
                        tanmode.appendChild(option);
                    }
                    document.getElementById(\'tanmodeRow\').style.display = \'\';
                    alert(\'Bitte einen TAN-Modus auswählen.\');
                    return;
                }

                const selectedMode = tanmode.options[tanmode.selectedIndex].tanmode;
                const tanmedium = document.getElementById(\'tanmedium\');
                if (selectedMode.needsTanMedium && !tanmedium.value) {
                    while (tanmedium.firstChild) tanmedium.firstChild.remove();
                    for (const medium of await post(\'getTanMedia\')) {
                        const option = document.createElement(\'option\');
                        option.setAttribute(\'value\', medium.name);
                        let text = medium.name;
                        if (medium.phoneNumber) text += ` (${medium.phoneNumber})`;
                        option.appendChild(document.createTextNode(text));
                        tanmedium.appendChild(option);
                    }
                    document.getElementById(\'tanmediumRow\').style.display = \'\';
                    alert(\'Bitte ein TAN-Medium auswählen.\');
                    return;
                }

                async function handleStrongAuthentication(responsePromise) {
                    let response = await responsePromise;
                    if (response.result === \'needsTan\') {
                        if (selectedMode.isDecoupled) {
                            do {
                                alert(\'Bitte bestätigen Sie die Aktion auf Ihrem Gerät und klicken Sie dann auf OK.\');
                                response = await post(\'checkDecoupledSubmission\');
                            } while (response.result === \'ongoing\');
                        } else {
                            const tan = prompt(\'Bitte die TAN eingeben. Bank sagt: \' + response.challenge);
                            response = await post(\'submitTan\', {tan});
                        }
                    }
                    if (response.result !== \'success\') {
                        throw new Error(`Unexpected result ${response.result}`);
                    }
                    return response;
                }

                await handleStrongAuthentication(post(\'login\'));
                const balances = await post(\'getBalances\');
                document.getElementById(\'output\').innerText = JSON.stringify(balances);
                await post(\'logout\');
            } catch (e) {
                console.log(e);
                alert(e);
            } finally {
                fieldset.disabled = false;
            }
        };
    </script>
</body>
</html>';
    }
}