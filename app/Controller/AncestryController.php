<?php
namespace Fisharebest\Webtrees;

/**
 * webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Class AncestryController - Controller for the ancestry chart
 */
class AncestryController extends ChartController {

	/** @var integer Show boxes for cousins */
	public $show_cousins;

	/** @var integer Determines style of chart */
	public $chart_style;

	/** @var integer Number of generations to display */
	public $generations;

	/**
	 * Startup activity
	 */
	public function __construct() {
		global $WT_TREE;

		parent::__construct();

		// Extract form parameters
		$this->show_cousins = Filter::getInteger('show_cousins', 0, 1);
		$this->chart_style  = Filter::getInteger('chart_style', 0, 3);
		$this->generations  = Filter::getInteger('PEDIGREE_GENERATIONS', 2, $WT_TREE->getPreference('MAX_PEDIGREE_GENERATIONS'), $WT_TREE->getPreference('DEFAULT_PEDIGREE_GENERATIONS'));

		if ($this->root && $this->root->canShowName()) {
			$this->setPageTitle(
				/* I18N: %s is an individual’s name */
				I18N::translate('Ancestors of %s', $this->root->getFullName())
			);
		} else {
			$this->setPageTitle(I18N::translate('Ancestors'));
		}
	}

	/**
	 * print a child ascendancy
	 *
	 * @param Individual $individual
	 * @param integer    $sosa  child sosa number
	 * @param integer    $depth the ascendancy depth to show
	 */
	public function printChildAscendancy(Individual $individual, $sosa, $depth) {
		echo '<li>';
		echo '<table><tbody><tr><td>';
		if ($sosa === 1) {
			echo '<img src="', Theme::theme()->parameter('image-spacer'), '" height="3" width="', Theme::theme()->parameter('chart-descendancy-indent'), '"></td><td>';
		} else {
			echo '<img src="', Theme::theme()->parameter('image-spacer'), '" height="3" width="2" alt="">';
			echo '<img src="', Theme::theme()->parameter('image-hline'), '" height="3" width="', Theme::theme()->parameter('chart-descendancy-indent') - 2, '"></td><td>';
		}
		print_pedigree_person($individual, $this->showFull());
		echo '</td><td>';
		if ($sosa > 1) {
			print_url_arrow('?rootid=' . $individual->getXref() . '&amp;PEDIGREE_GENERATIONS=' . $this->generations . '&amp;show_full=' . $this->showFull() . '&amp;chart_style=' . $this->chart_style . '&amp;ged=' . $individual->getTree()->getNameUrl(), I18N::translate('Ancestors of %s', $individual->getFullName()), 3);
		}
		echo '</td><td class="details1">&nbsp;<span class="person_box' . ($sosa === 1 ? 'NN' : ($sosa % 2 ? 'F' : '')) . '">', I18N::number($sosa), '</span> ';
		echo '</td><td class="details1">&nbsp;', get_sosa_name($sosa), '</td>';
		echo '</tr></tbody></table>';

		// Parents
		$family = $individual->getPrimaryChildFamily();
		if ($family && $depth > 0) {
			// Marriage details
			echo '<span class="details1">';
			echo '<img src="', Theme::theme()->parameter('image-spacer'), '" height="2" width="', Theme::theme()->parameter('chart-descendancy-indent'), '" alt=""><a href="#" onclick="return expand_layer(\'sosa_', $sosa, '\');" class="top"><i id="sosa_', $sosa, '_img" class="icon-minus" title="', I18N::translate('View family'), '"></i></a>';
			echo ' <span class="person_box">', I18N::number($sosa * 2), '</span> ', I18N::translate('and');
			echo ' <span class="person_boxF">', I18N::number($sosa * 2 + 1), '</span>';
			if ($family->canShow()) {
				foreach ($family->getFacts(WT_EVENTS_MARR) as $fact) {
					echo ' <a href="', $family->getHtmlUrl(), '" class="details1">', $fact->summary(), '</a>';
				}
			}
			echo '</span>';
			// display parents recursively - or show empty boxes
			echo '<ul id="sosa_', $sosa, '" class="generation">';
			if ($family->getHusband()) {
				$this->printChildAscendancy($family->getHusband(), $sosa * 2, $depth - 1);
			}
			if ($family->getWife()) {
				$this->printChildAscendancy($family->getWife(), $sosa * 2 + 1, $depth - 1);
			}
			echo '</ul>';
		}
		echo '</li>';
	}
}
