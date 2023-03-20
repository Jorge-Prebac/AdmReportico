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

use FacturaScripts\Core\Lib\ExtendedController\ListController;

/**
 * Description of ListReportico
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
class ListReportico extends ListController
{
	
	/**
     * Returns basic page attributes
     *
     * @return array
     */
    public function getPageData(): array
	{
		$data = parent::getPageData();
		$data['menu'] = 'reports';
		$data['submenu'] = 'adm-reportico';
		$data['title'] = 'reports';
		$data['icon'] = 'fas fa-archway';
		return $data;
	}

    protected function createViews()
    {
		$this->createViewsAdmReportico();
    }

	/**
     * 
     * @param string $viewName
     */
    protected function createViewsAdmReportico($viewName = 'ListReportico')
    {
        $this->addView($viewName, 'Reportico', 'AdmReportico' ,'fas fa-archway');
        $this->addSearchFields($viewName, ['dirProjects', 'file', 'note', 'type']);
        $this->addOrderBy($viewName, ['type'], 'type');

		if (false == $this->user->admin) {
			$this->setSettings($viewName, 'clickable', false);
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