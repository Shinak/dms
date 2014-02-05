<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Krüger 2009 
 * @author     Thomas Krüger <krueger-th@gmx.de>
 * @package    wunschliste 
 * @license    GPL 
 * @filesource
 */


/**
 * Table tl_wl_wuenschen 
 */
$GLOBALS['TL_DCA']['tl_dms_access_rights'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'ptable'                      => 'tl_dms_categories'
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 6,
			'flag'                    => 11,
			'fields'                  => array('pid:tl_dms_categories.name', 'member_group:tl_member_group.name'),
			'root'                    => array(0)
		),
		'label' => array
		(
			'fields'                  => array('member_group:tl_member_group.name'),
			'label_callback'          => array('tl_dms_access_rights', 'addIcon') 
		),
		'global_operations' => array
		(
			'toggleNodes' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['toggleNodes'],
				'href'                => '&amp;ptg=all',
				'class'               => 'header_toggle'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{member_group_legend},member_group;{rights_legend},read,upload,delete,edit,publish'
	),

	// Fields
	'fields' => array
	(
		'member_group' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['member_group'],
			'exclude'                 => true,
			'inputType'               => 'radio',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>false,'mandatory'=>true)
		),
		
		'read' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['read'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'filter'				  => true,
            'default'                 => '1'
		),
		
		'upload' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['upload'],
			'exclude'                 => true,
			'filter'				  => true,
			'inputType'               => 'checkbox'
		),
				
		'edit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['edit'],
			'exclude'                 => true,
			'filter'				  => true,
			'inputType'               => 'checkbox'
		),
		
		'delete' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['delete'],
			'exclude'                 => true,
			'filter'				  => true,
			'inputType'               => 'checkbox' 
		),
				
		'publish' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dms_access_rights']['publish'],
			'exclude'                 => true,
			'filter'				  => true,
			'inputType'               => 'checkbox' 
		)
	)
);

/**
 * Class tl_dms_access_rights
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Controller
 */
class tl_dms_access_rights extends Backend
{
	/**
	 * Add an image to each record
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false)
	{
		$memberGroupName = $this->Database->prepare("SELECT id, name FROM tl_member_group WHERE id=?")
										  ->limit(1)
										  ->execute($row['member_group']);
		
		$accessRightRead = $row['read'] == "" ? "" : "<img src='system/modules/DocumentManagementSystem/html/access_right_read.gif' title='" . $GLOBALS['TL_LANG']['tl_dms_access_rights']['read'][0] . "'/>";
		$accessRightUpload = $row['upload'] == "" ? "" : "<img src='system/modules/DocumentManagementSystem/html/access_right_upload.gif' title='" . $GLOBALS['TL_LANG']['tl_dms_access_rights']['upload'][0] . "'/>";
		$accessRightDelete = $row['delete'] == "" ? "" : "<img src='system/modules/DocumentManagementSystem/html/access_right_delete.gif' title='" . $GLOBALS['TL_LANG']['tl_dms_access_rights']['delete'][0] . "'/>";
		$accessRightEdit = $row['edit'] == "" ? "" : "<img src='system/modules/DocumentManagementSystem/html/access_right_edit.gif' title='" . $GLOBALS['TL_LANG']['tl_dms_access_rights']['edit'][0] . "'/>";
		$accessRightPublish = $row['publish'] == "" ? "" : "<img src='system/modules/DocumentManagementSystem/html/access_right_publish.gif' title='" . $GLOBALS['TL_LANG']['tl_dms_access_rights']['publish'][0] . "'/>";
		
		return $this->generateImage('system/modules/DocumentManagementSystem/html/access_rights.png', '', '') . $label .'<span style="color:#b3b3b3; padding-left:3px;">' . $accessRightRead . ' ' . $accessRightUpload . ' ' . $accessRightDelete . ' ' . $accessRightEdit . ' ' . $accessRightPublish . '</span>';
	}
}

?>