<?php
/*
  Jewel CMS
  Copyright (c) 2015 Ben Barden

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require '../../lib/Cms/Legacy/Header.php';
if (!$CMS->RES->IsAdmin()) {
    showCpErrorPage($cmsContainer, $cpBindings, "You do not have access to do this");
}

$em = $cmsContainer->getServiceLocator()->getCmsEntityManager();

    $cpBindings = array();

    $cpBindings['CP']['Title'] = "Database update";

    $cpBindings['Auth']['IsAdmin'] = $CMS->RES->IsAdmin();
    $cpBindings['Auth']['CanWriteContent'] = $CMS->RES->CanAddContent();

    $cmsDbVersion = $CMS->SYS->GetSysPref(C_PREF_CMS_VERSION);
    $cmsNewVersion = C_SYS_LATEST_VERSION;

    $updateSuccess = false;

    if (isset($_GET['do'])) {
        if ($_GET['do'] == 'update') {
            // Begin
            $nextVersion = null;
            if ($cmsDbVersion == '1.0.0') {
                $nextVersion = '1.1.0';
            }
            switch ($nextVersion) {
                case '1.1.0':
                    $setting = $em->getRepository('Cms\Entity\Setting')->getCMSVersion();
                    if (!$setting) {
                        throw new \Cms\Exception\Cp\PageException('Cannot find setting: CMSVersion');
                    }
                    $setting->setContent($nextVersion);
                    $em->flush();
                    $CMS->SYS->RebuildCache();
                    $cmsDbVersion = $nextVersion;
                    $cpBindings['CP']['Msg'] = 'Update complete!';
                    $updateSuccess = true;
                    break;
                default:
                    $cpBindings['CP']['Error'] = 'Unsupported version: '.$cmsDbVersion;
                    break;
            }
        }
    }


    $cpBindings['Page']['CmsVersion'] = $cmsDbVersion;
    $cpBindings['Page']['NewerVersion'] = $cmsNewVersion;

    if (!$updateSuccess) {
        if ($cmsDbVersion == $cmsNewVersion) {
            $cpBindings['CP']['Error'] = 'Database is already up to date!';
        }
    }

    $engine = $cmsContainer->getService('Theme.EngineCPanel');
    $outputHtml = $engine->render('db-update.twig', $cpBindings);
    print($outputHtml);
    exit;
