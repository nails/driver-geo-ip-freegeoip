<?php

namespace Nails\GeoIp\Driver;

use Nails\Factory;
use Nails\GeoIp\Interfaces\Driver;

class FreeGeoIp implements Driver
{
    /**
     * The base url of the ipinfo.io service.
     * @var string
     */
    const BASE_URL = 'https://freegeoip.net';

    // --------------------------------------------------------------------------

    /**
     * The value of an OK response
     * @var integer
     */
    const STATUS_OK = 200;

    // --------------------------------------------------------------------------

    /**
     * @param string $sIp The IP address to look up
     *
     * @return \Nails\GeoIp\Result\Ip
     */
    public function lookup($sIp)
    {
        $oIp     = Factory::factory('Ip', 'nailsapp/module-geo-ip');
        $oClient = Factory::factory('HttpClient');

        $oIp->setIp($sIp);

        try {

            $oResponse = $oClient->request('GET', static::BASE_URL . '/json/' . $sIp);

            if ($oResponse->getStatusCode() === static::STATUS_OK) {

                $oJson = json_decode($oResponse->getBody());

                if (!empty($oJson->city)) {
                    $oIp->setCity($oJson->city);
                }

                if (!empty($oJson->region_name)) {
                    $oIp->setRegion($oJson->region_name);
                }

                if (!empty($oJson->country_name)) {
                    $oIp->setCountry($oJson->country_name);
                }

                if (!empty($oJson->latitude)) {
                    $oIp->setLat($oJson->latitude);
                }

                if (!empty($oJson->longitude)) {
                    $oIp->setLng($oJson->longitude);
                }
            }

        } catch (\Exception $e) {
            //  @log the exception somewhere
        }

        return $oIp;
    }
}
