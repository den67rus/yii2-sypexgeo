<?php
/**
 * Class update data
 * User: den67rus
 * Date: 22.07.2017
 * Time: 3:19
 */

namespace jisoft\sypexgeo;

/**
 * Class SxUpdate
 * @package jisoft\sypexgeo
 */
class SxUpdate
{
    /**
     * Custom dir dat files
     * by default Yii::getAlias('@runtime'); or __DIR__ if not Yii Framework
     * @var string
     */
    public $updateDir;

    /**
     * @var string Custom url update
     */
    public $countryUrl = 'https://sypexgeo.net/files/SxGeoCountry.zip';

    /**
     * @var string Custom url update
     */
    public $cityUrl = 'https://sypexgeo.net/files/SxGeoCity_utf8.zip';

    /**
     * @var string Custom url update
     */
    public $maxUrl;

    /**
     * SxUpdate constructor.
     */
    public function __construct()
    {
        set_time_limit(600);

        if (class_exists('\Yii')) {
            $this->updateDir = \Yii::getAlias('@runtime');
        } else {
            $this->updateDir = __DIR__;
        }
    }

    /**
     * Update Sypex Geo Country file
     * @return bool
     */
    public function updateCountry()
    {
        return $this->update($this->countryUrl, 'SxGeo');
    }

    /**
     * Update Sypex Geo City file
     * @return bool
     */
    public function updateCity()
    {
        return $this->update($this->cityUrl, 'SxGeoCity');
    }

    /**
     * Update Sypex Geo Max file
     * @return bool
     */
    public function updateMax()
    {
        return $this->update($this->maxUrl, 'SxGeoMax');
    }

    /**
     * Update Sypex Geo all file
     * @return object
     */
    public function updateAll()
    {
        return (object) [
            'country' => $this->updateCountry(),
            'city' => $this->updateCity(),
            'max' => $this->updateMax(),
        ];
    }

    /**
     * Download and update file
     * @param string $url
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    protected function update($url, $type)
    {
        $dir = $this->getDir();
        $dat_file = $dir . '/' . $type . '.dat';
        $upd_file = $dir . '/' .$type . '.upd';

        $fp = fopen($dir .'/SxGeoTmp.zip', 'wb');
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_FILE => $fp,
            CURLOPT_HTTPHEADER => file_exists($upd_file) ? array("If-Modified-Since: " .file_get_contents($upd_file)) : [],
        ));
        if(!curl_exec($ch)) {
            throw new \Exception('Failed to download the update file Sypexgeo. Url: ' . $url);
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        if ($code == 304) {
            @unlink($dir . '/SxGeoTmp.zip');
            return false;
        }

        $fp = fopen('zip://' . $dir . '/SxGeoTmp.zip#' . $type . '.dat', 'rb');
        $fw = fopen($dat_file, 'wb');
        if (!$fp) {
            throw new \Exception('Failed to open the downloaded update file Sypexgeo.');
        }
        stream_copy_to_stream($fp, $fw);
        fclose($fp);
        fclose($fw);
        if(filesize($dat_file) == 0) {
            throw new \Exception('Failed to unzipping the downloaded update file Sypexgeo.');
        }
        @unlink($dir . '/SxGeoTmp.zip');
        file_put_contents($upd_file, gmdate('D, d M Y H:i:s') . ' GMT');
        return true;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getDir()
    {
        $dir = $this->updateDir . '/sx_data';
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new \Exception('Failed to create directory Sypexgeo update. Dir: ' . $dir);
            }
        }
        return $dir;
    }
}
