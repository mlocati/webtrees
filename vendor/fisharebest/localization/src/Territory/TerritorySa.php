<?php namespace Fisharebest\Localization\Territory;

/**
 * Class AbstractTerritory - Representation of the territory SA - Saudi Arabia.
 *
 * @author    Greg Roach <fisharebest@gmail.com>
 * @copyright (c) 2015 Greg Roach
 * @license   GPLv3+
 */
class TerritorySa extends AbstractTerritory implements TerritoryInterface {
	/** {@inheritdoc} */
	public function code() {
		return 'SA';
	}

	/** {@inheritdoc} */
	public function firstDay() {
		return 0;
	}

	/** {@inheritdoc} */
	public function weekendStart() {
		return 5;
	}

	/** {@inheritdoc} */
	public function weekendEnd() {
		return 6;
	}
}
