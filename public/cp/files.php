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
  $cpItemsPerPage = $cmsContainer->getServiceLocator()->getCmsConfig()->getByKey('CP.ItemsPerPage');
  $CMS->RES->Admin();
  if ($CMS->RES->IsError()) {
    $CMS->Err_MFail(M_ERR_UNAUTHORISED, "Admin");
  }
  $strPageTitle = "Manage Files";
  $CMS->AP->SetTitle($strPageTitle);
  
  // File type
  $strSearchType = empty($_GET['type']) ? "" : $_GET['type'];
  $blnSite = false; $blnAttach = false; $blnAvatar = false;
  switch ($strSearchType) {
    case "site":   $blnSite   = true; break;
    case "attach": $blnAttach = true; break;
    case "avatar": $blnAvatar = true; break;
    default:       $blnSite   = true; $strSearchType = "site"; break;
  }
  
  // Multi-paging
  if (!empty($_GET['page'])) {
    $intPageNumber = $_GET['page'];
    if ($intPageNumber < 1) {
      $intPageNumber = 1;
    }
  } else {
    $intPageNumber = 1;
  }
  
  // Order, Direction
  
  $strOrder  = empty($_GET['order']) ? "create_date" : $CMS->DoEntities($_GET['order']);
  $strDir    = empty($_GET['dir']) ? "desc" : $CMS->DoEntities($_GET['dir']);
  $strGetURL = "?type=$strSearchType&amp;order=$strOrder&amp;dir=$strDir";
  $strBack   = str_replace("?", "&amp;", $strGetURL."&amp;page=$intPageNumber");

  $strOrderBy = "";
  
  $strDropDownOBF = $CMS->DD->FileOrderField($strOrder);
  $strDropDownOBD = $CMS->DD->SortRuleOrder($strDir);
  
  // User field
  
  $strUser = "";
  $strInvalidUsername = "";
  $blnInvalidSearch = false;
  
  $strUserWhereClause = "";
  if (!empty($_GET['user'])) {
    $strUser = $CMS->FilterAlphanumeric($_GET['user'], C_CHARS_DISPLAY_NAME);
    $intUserID = $CMS->US->GetIDFromName($strUser);
    if ($intUserID) {
      $strUserWhereClause = " AND u.id = $intUserID ";
      if (empty($strGetURL)) {
        $strGetURL = "?user=$strUser";
      } else {
        $strGetURL .= "&amp;user=$strUser";
      }
    } else {
      $intUserID = 0;
      $strInvalidUsername = $CMS->AC->InvalidFormData(M_ERR_SEARCH_USER_NOT_FOUND);
      $blnInvalidSearch = true;
    }
    if ($intUserID == 0) {
      if (empty($strGetURL)) {
        $strGetURL = "?user=$intUserID";
      } else {
        $strGetURL .= "&amp;user=$intUserID";
      }
    }
  } else {
    $intUserID = "";
  }
  
  // Search button

  $strSearchButton  = $CMS->AC->SearchButton();

  $strHTML = <<<MainContentStart
<h1 class="page-header">$strPageTitle</h1>
<form action="{FN_ADM_FILES}" method="get">
<div class="table-responsive">
<table class="table table-striped">
  <tr class="separator-row">
    <td colspan="5">Search Files</td>
  </tr>
  <tr>
    <td>
      <label for="user"><b>User</b></label>
    </td>
    <td>
      $strInvalidUsername
      <input type="text" id="user" name="user" size="20" maxlength="100" value="$strUser" />
    </td>
    <td>
      <label for="order">Order by:</label>
    </td>
    <td>
      <input type="hidden" name="type" value="$strSearchType" />
      <select id="order" name="order">
      $strDropDownOBF
      </select>
      <select id="dir" name="dir">
      $strDropDownOBD
      </select>
    </td>
    <td>
      $strSearchButton
    </td>
  </tr>
</table>
</form>

MainContentStart;

  // Ordering
  if (($strOrder) && ($strDir)) {
    switch ($strOrder) {
      case "id": $strOrderBy = " ORDER BY up.id "; break;
      case "title": $strOrderBy = " ORDER BY title "; break;
      case "create_date": $strOrderBy = " ORDER BY create_date_raw "; break;
      default: $strOrderBy = " ORDER BY up.id "; break;
    }
    switch ($strDir) {
      case "asc": $strOrderBy .= "ASC"; break;
      case "desc": $strOrderBy .= "DESC"; break;
      default: $strOrderBy .= "ASC"; break;
    }
  } else {
    $strOrderBy = " ORDER BY create_date_raw DESC";
  }

  $strDateFormat = $CMS->SYS->GetDateFormat();
  // Page numbers
  $intContentPerPage = $cpItemsPerPage;
  $intStart = $CMS->PN->GetPageStart($intContentPerPage, $intPageNumber);
  // Where clause
  if ($blnSite) {
    $strWhereClause = " AND is_siteimage = 'Y' ";
  } elseif ($blnAttach) {
    $strWhereClause = " AND article_id <> 0 ";
  } elseif ($blnAvatar) {
    $strWhereClause = " AND is_avatar = 'Y' ";
  }
  // Get content
  $arrImages = $CMS->ResultQuery("SELECT up.id AS upload_id, u.id AS user_id, u.display_name, up.title, DATE_FORMAT(create_date, '$strDateFormat') AS create_date, create_date AS create_date_raw, up.location, up.thumb_small, up.thumb_medium, up.thumb_large, up.upload_size FROM ({IFW_TBL_UPLOADS} up, {IFW_TBL_USERS} u) WHERE up.author_id = u.id $strWhereClause $strUserWhereClause $strOrderBy LIMIT $intStart, $intContentPerPage", basename(__FILE__), __LINE__);
  $arrImageCount = $CMS->ResultQuery("SELECT count(*) AS count FROM ({IFW_TBL_UPLOADS} up, {IFW_TBL_USERS} u) WHERE up.author_id = u.id $strWhereClause $strUserWhereClause", basename(__FILE__), __LINE__);
  $intImageCount = $arrImageCount[0]['count'];
  // Page number links
  $intNumPages = $CMS->PN->GetTotalPages($intContentPerPage, $intImageCount);
  $strPageNumbers = $CMS->PNN->Make($intNumPages, $intPageNumber, basename(__FILE__)."$strGetURL");
    for ($i=0; $i<count($arrImages); $i++) {
    $intID       = $arrImages[$i]['upload_id'];
    $strTitle    = $CMS->DoEntities($arrImages[$i]['title']);
    $intUserID   = $arrImages[$i]['user_id'];
    $strUser     = $arrImages[$i]['display_name'];
    $dteCreated  = $arrImages[$i]['create_date'];
    $strFileSize = $CMS->MakeFileSize($arrImages[$i]['upload_size']);
    if ($i == 0) {
      $strHTML .= <<<TableHeader
$strPageNumbers
<div class="table-responsive">
<table class="table table-striped">
  <thead>
    <tr class="separator-row">
      <td>Info</td>
      <td>Thumbnail</td>
      <td>Links</td>
      <td>HTML</td>
    </tr>
  </thead>
  <tbody id="tblSiteFilesBody">

TableHeader;
    }
    if ($blnSite) {
      $strMsg = "http://{SVR_HOST}{URL_ROOT}";
      $strLinkOrig   = $strMsg.$arrImages[$i]['location'];
      $strLinkLarge  = $strMsg.$arrImages[$i]['thumb_large'];
      $strLinkMedium = $strMsg.$arrImages[$i]['thumb_medium'];
      $strLinkSmall  = $strMsg.$arrImages[$i]['thumb_small'];
      $strLinkDL     = "http://{SVR_HOST}{FN_FILE_DOWNLOAD}?id=$intID"; // Download link
    } elseif (($blnAttach) || ($blnAvatar)) {
      $strMsg = "http://{SVR_HOST}{FN_FILE_DOWNLOAD}";
      $strLinkOrig   = $strMsg."?id=$intID";
      $strLinkLarge  = $strMsg."?id=$intID&amp;s=l";
      $strLinkMedium = $strMsg."?id=$intID&amp;s=m";
      $strLinkSmall  = $strMsg."?id=$intID&amp;s=s";
      $strLinkDL     = $strLinkOrig; // Download link
    }
    $strImg1 = $CMS->DoEntities("<img src=\"".str_replace("&amp;", "&", $strLinkOrig)."\" alt=\"$strTitle\" />");
    if ($arrImages[$i]['thumb_large']) {
      $strImg2 = $CMS->DoEntities("<img src=\"".str_replace("&amp;", "&", $strLinkLarge)."\" alt=\"$strTitle\" />");
    } else {
      $strLinkLarge = "";
      $strImg2 = "";
    }
    if ($arrImages[$i]['thumb_medium']) {
      $strImg3 = $CMS->DoEntities("<img src=\"".str_replace("&amp;", "&", $strLinkMedium)."\" alt=\"$strTitle\" />");
    } else {
      $strLinkMedium = "";
      $strImg3 = "";
    }
    if ($arrImages[$i]['thumb_small']) {
      $strImg4 = $CMS->DoEntities("<img src=\"".str_replace("&amp;", "&", $strLinkSmall)."\" alt=\"$strTitle\" />");
      $strImagePreview = "<img src=\"$strLinkSmall\" alt=\"Site Image\" /><br /><a href=\"#\" onclick=\"window.open('$strLinkDL');\">Download file</a>";
    } elseif (($blnAvatar) && ($arrImages[$i]['location'])) {
      $strLinkSmall = "";
      $strImg4 = "";
      $strImagePreview = "<img src=\"$strLinkOrig\" alt=\"Avatar\" /><br /><a href=\"#\" onclick=\"window.open('$strLinkDL');\">Download file</a>";
    } else {
      $strLinkSmall = "";
      $strImg4 = "";
      $strImagePreview = "No preview<br />available.<br /><a href=\"#\" onclick=\"window.open('$strLinkDL');\">Download file</a>";
    }
    if (($i % 2) == 0) {
      $strRowClass = "even";
    } else {
      $strRowClass = "odd";
    }
    $strHTML .= <<<ImageItem

    <tr class="$strRowClass">
      <td style="width: 250px;">
        <b>File ID</b>: $intID<br />
        <b>Title</b>: $strTitle<br />
        <b>Size</b>: $strFileSize<br />
        <b>Author</b>: $strUser<br />
        <b>Created</b>: $dteCreated
        <br /><br />
        <b>Options</b>: <a href="{FN_ADM_FILES_SITE_UPLOAD}?action=edit&amp;id=$intID">Edit</a> <a href="{FN_ADMIN_TOOLS}?action=deletefile&amp;id=$intID&amp;back={FN_ADM_FILES}$strBack">Delete</a>
      </td>
      <td class="Centre">$strImagePreview</td>
      <td style="vertical-align: top;">
        <label for="txtImgLink1$i"><b>Original</b></label><br />
        <input id="txtImgLink1$i" type="text" size="30" value="$strLinkOrig" onclick="selectFieldData(this);" />

ImageItem;
    if ($strImg2) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgLink2$i"><b>Large Thumb</b></label><br />
        <input id="txtImgLink2$i" type="text" size="30" value="$strLinkLarge" onclick="selectFieldData(this);" />

Thumb;
    }
    if ($strImg3) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgLink3$i"><b>Medium Thumb</b></label><br />
        <input id="txtImgLink3$i" type="text" size="30" value="$strLinkMedium" onclick="selectFieldData(this);" />

Thumb;
    }
    if ($strImg4) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgLink4$i"><b>Small Thumb</b></label><br />
        <input id="txtImgLink4$i" type="text" size="30" value="$strLinkSmall" onclick="selectFieldData(this);" />

Thumb;
    }
    $strHTML .= <<<ImageHTML
      </td>
      <td style="vertical-align: top;">
        <label for="txtImgHTML1$i"><b>Original</b></label><br />
        <input id="txtImgHTML1$i" type="text" size="30" value="$strImg1" onclick="selectFieldData(this);" />

ImageHTML;
    if ($strImg2) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgHTML2$i"><b>Large Thumb</b></label><br />
        <input id="txtImgHTML2$i" type="text" size="30" value="$strImg2" onclick="selectFieldData(this);" />

Thumb;
    }
    if ($strImg3) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgHTML3$i"><b>Medium Thumb</b></label><br />
        <input id="txtImgHTML3$i" type="text" size="30" value="$strImg3" onclick="selectFieldData(this);" />

Thumb;
    }
    if ($strImg4) {
      $strHTML .= <<<Thumb
        <br />
        <label for="txtImgHTML4$i"><b>Small Thumb</b></label><br />
        <input id="txtImgHTML4$i" type="text" size="30" value="$strImg4" onclick="selectFieldData(this);" />

Thumb;
    }
    $strHTML .= <<<CloseRow
      </td>
    </tr>

CloseRow;
	}
  if (count($arrImages) > 0) {
    $strHTML .= "  </tbody>\n</table>\n</div>\n$strPageNumbers\n";
  } else {
    $strHTML .= "<p>No files found.</p>\n";
  }

  $CMS->AP->Display($strHTML);
?>