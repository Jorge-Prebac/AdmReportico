<?php

/**
 * This file is part of the AdmReportico plugin, with the Reportico engine, for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\AdmReportico\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Core\Model\User;
use FacturaScripts\Core\Lib\ExtendedController\EditController;

/**
 * Description of EditReportico
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
class EditReportico extends EditController
{

	/**
     * 
     * @return string
     */
    public function getModelClassName(): string
    {
        return 'Reportico';
    }
    
	/**
     * Returns basic page attributes
     *
     * @return array
     */
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'reports';
        $data['title'] = 'reportico-reports';
        $data['icon'] = 'fas fa-archway';
        return $data;
    }
	 
	/**
     * 
     * @param string   $viewName
     * @param EditView $view
     */
    protected function loadData($viewName, $view)
	{
        switch ($viewName) {
			
			case 'EditReportico':
                parent::loadData($viewName, $view);
								
				$this->views[$viewName]->disableColumn('id', false, 'true');

				break;
							           
			default:
                parent::loadData($viewName, $view);
                break;
        }
		
		$urlReportico = $this->toolBox()->appSettings()->get('reportico', 'urlReportico');

		$this->addButton($viewName, [
			'action' => $urlReportico,
			'color' => 'info',
			'icon' => 'fas fa-archway',
			'label' => 'adm-reportico',
			'title' => 'open-new-pag',
			'target' => '_blank',
			'type' => 'link'
		]);
    }
}