<?php


namespace Cms\Helper\EntityValue;


class Setting extends Base
{
    protected $repoName = 'Cms\Entity\Setting';

    protected function getValue($name)
    {
        $repo = $this->repo;
        /* @var \Cms\Repository\Setting $repo */
        $setting = $repo->getByName($name);
        /* @var \Cms\Entity\Setting $setting */
        return $setting->getContent();
    }

    public function getSiteTitle()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_SITE_TITLE);
    }

    public function getSiteDesc()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_SITE_DESC);
    }

    public function getSiteKeywords()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_SITE_KEYWORDS);
    }

    public function getSiteHeader()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_SITE_HEADER);
    }

    public function getLinkStyle()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_LINK_STYLE);
    }

    public function getDateFormat()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_DATE_FORMAT);
    }

    public function getTimeFormat()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_TIME_FORMAT);
    }

    public function getDisqusId()
    {
        return $this->getValue(\Cms\Entity\Setting::SETTING_DISQUS_ID);
    }

    public function getDisplayDateFormat()
    {
        $settingDateFormat = $this->getDateFormat();
        if (!$settingDateFormat) $settingDateFormat = 1;
        $settingTimeFormat = $this->getTimeFormat();
        if (!$settingTimeFormat) $settingTimeFormat = 0;

        switch ($settingDateFormat) {
            case 1: $dateFormat = "F j, Y"; break; // September 16, 2007
            case 2: $dateFormat = "j F, Y"; break; // 16 September, 2007
            case 3: $dateFormat = "d/m/Y";  break; // 16/09/2007
            case 4: $dateFormat = "m/d/Y";  break; // 09/16/2007
            case 5: $dateFormat = "Y/m/d";  break; // 2007/09/16
            case 6: $dateFormat = "Y-m-d";  break; // 2007-09-16
            case 7: $dateFormat = "Y/d/m";  break; // 2007/16/09
            case 8: $dateFormat = "Y-d-m";  break; // 2007-16-09
        }

        switch ($settingTimeFormat) {
            case 1: $timeFormat = "H:i";   break; // 24H
            case 2: $timeFormat = "H:i:s"; break; // 24H with seconds
            case 3: $timeFormat = "g:i A"; break; // 12H followed by AM or PM
            default: $timeFormat = "";     break; // Do not display time
        }

        $combinedFormat = $dateFormat;
        if ($timeFormat) {
            $combinedFormat .= ' '.$timeFormat;
        }
        return $combinedFormat;
    }

}