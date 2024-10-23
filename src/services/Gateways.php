<?php
/**
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/videos/blob/v2/LICENSE.md
 */

namespace dukt\videos\services;

use Craft;
use dukt\videos\base\Gateway;
use dukt\videos\events\RegisterGatewayTypesEvent;
use dukt\videos\Plugin;
use yii\base\Component;
use dukt\videos\gateways\Vimeo;
use dukt\videos\gateways\YouTube;

/**
 * Class Gateways service.
 *
 * An instance of the Gateways service is globally accessible via [[Plugin::gateways `Videos::$plugin->getGateways()`]].
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Gateways extends Component
{
    // Constants
    // =========================================================================
    /**
     * @event RegisterLoginProviderTypesEvent The event that is triggered when registering login providers.
     * @var string
     */
    public const EVENT_REGISTER_GATEWAY_TYPES = 'registerGatewayTypes';

    // Properties
    // =========================================================================

    /**
     * @var array
     */
    private $_gateways = [];

    /**
     * @var array
     */
    private $_allGateways = [];

    /**
     * @var bool
     */
    private $_gatewaysLoaded = false;

    // Public Methods
    // =========================================================================
    /**
     * Get gateway by handle.
     *
     * @param      $gatewayHandle
     *
     * @return Gateway|null
     */
    public function getGateway($gatewayHandle, bool $enabledOnly = true)
    {
        $this->loadGateways();

        $gateways = $enabledOnly ? $this->_gateways : $this->_allGateways;

        foreach ($gateways as $g) {
            if ($g->getHandle() === $gatewayHandle) {
                return $g;
            }
        }

        return null;
    }

    /**
     * Get gateways.
     *
     *
     * @return Gateway[]
     */
    public function getGateways(bool $enabledOnly = true): array
    {
        $this->loadGateways();

        if ($enabledOnly) {
            return $this->_gateways;
        }

        return $this->_allGateways;
    }

    // Private Methods
    // =========================================================================

    /**
     * Load gateways.
     *
     * @return null
     * @throws \yii\base\InvalidConfigException
     */
    private function loadGateways()
    {
        if ($this->_gatewaysLoaded) {
            return null;
        }

        foreach ($this->_getGateways() as $gateway) {
            if ($gateway->enableOauthFlow()) {
                $gatewayHandle = $gateway->getHandle();

                $token = Plugin::getInstance()->getTokens()->getToken($gatewayHandle);

                if ($token !== null) {
                    $this->_gateways[] = $gateway;
                }
            } else {
                $this->_gateways[] = $gateway;
            }

            $this->_allGateways[] = $gateway;
        }

        $this->_gatewaysLoaded = true;

        return null;
    }

    /**
     * Returns all gateway instances.
     *
     * @return array
     */
    private function _getGateways(): array
    {
        $gatewayTypes = $this->_getGatewayTypes();

        $gateways = [];

        foreach ($gatewayTypes as $gatewayType) {
            $gateways[$gatewayType] = $this->_createGateway($gatewayType);
        }

        ksort($gateways);

        return $gateways;
    }

    /**
     * Returns gateway types.
     *
     * @return array
     */
    private function _getGatewayTypes(): array
    {
        $gatewayTypes = [
            Vimeo::class,
            YouTube::class,
        ];

        $eventName = self::EVENT_REGISTER_GATEWAY_TYPES;

        $event = new RegisterGatewayTypesEvent([
            'gatewayTypes' => $gatewayTypes
        ]);

        $this->trigger($eventName, $event);

        return $event->gatewayTypes;
    }

    /**
     * Instantiates a gateway.
     *
     * @param $gatewayType
     *
     * @return object
     */
    private function _createGateway($gatewayType): object
    {
        return new $gatewayType;
    }
}
