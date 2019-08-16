<?php
/* Copyright (C) 2019 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_pricelist.class.php
 * \ingroup pricelist
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class Actionspricelist
 */
class Actionspricelist
{
	/**
	 * @var DoliDb        Database handler (result of a new DoliDB)
	 */
	public $db;

	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 * @param DoliDB $db Database connector
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/** Add option in massaction of lists
	 * @param $parameters
	 * @param $object
	 * @param $action
	 * @param $hookmanager
	 * @return int
	 */
	public function addMoreMassActions($parameters, &$object, &$action, $hookmanager)
	{
		global $langs, $massaction, $conf;
		$langs->load('pricelist@pricelist');

		$error = 0; // Error counter

		if (strpos($parameters['context'], 'productservicelist') !== false) {
			$this->resprints = '<option value="changePrice">' . $langs->trans("ChangePrice",$conf->global->PRICELISTPOURCENTAGEMASSACTION) . '</option>';
		}

		if (!$error) {
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}

	/** Functions related to massaction
	 * @param $parameters
	 * @param $object
	 * @param $action
	 * @param $hookmanager
	 * @return int
	 */
	public function doMassActions($parameters, &$object, &$action, $hookmanager)
	{
		global $user, $db, $langs, $massaction, $conf;
		$langs->load('pricelist@pricelist');

		$error = 0; // Error counter

		if (strpos($parameters['context'], 'productservicelist') !== false)
		{
			if($massaction == 'changePrice')
			{
				dol_include_once('abricot/includes/class/class.seedobject.php');
				foreach ($parameters['toselect'] as $selectId){
					$object->fetch($selectId);
					$augmentation = 1+($conf->global->PRICELISTPOURCENTAGEMASSACTION/100);
					$object->updatePrice($object->price*$augmentation, 'HT', $user);
					//TODO add log truc machin
				}
			}
		}

		if (! $error) {
			return 0; // or return 1 to replace standard code
		} else {
			return -1;
		}
	}
}
