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
namespace FacturaScripts\Plugins\AdmReportico\Extension\Controller;

use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

/**
 * Description of ListPresupuestoCliente
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
 
class ListPresupuestoCliente
{
	public function createViews()
	{
		return function() {
			$viewName = 'ListReportico';
			$this->addView($viewName,'Reportico','Reportico','fas fa-archway');
			$this->views[$viewName]->addOrderBy(['type'], 'type');
			
			if (false == $this->user->admin) {
				$this->setSettings($viewName, 'clickable', false);
			}
			
			$this->addButton($viewName, [
					'action' => 'ok-report',
					'color' => 'warning',
					'icon' => 'fas fa-check-double',
					'label' => 'Ok Report',
					'type' => 'action'
			]);
		};
	}

    public function loadData()
	{
        return function($viewName, $view) {
            if ($viewName === 'ListReportico') {
				$type = "PresupuestoCliente";
                $where = [new DataBaseWhere('type', $type)];
                $view->loadData('', $where);
				
				$urlReportico = $this->toolBox()->appSettings()->get('reportico', 'urlReportico');

				$this->addButton($viewName, [
					'action' => $urlReportico,
					'color' => 'info',
					'icon' => 'fas fa-archway',
					'label' => 'AdmReportico',
					'target' => '_blank',
					'type' => 'link'
				]);
			}
		};
    }

	public function execAfterAction()
	{
		return function ($action) {
			if ($action === 'ok-report') {
				$model = $this->views[$this->active]->model;
				$codes = $this->request->request->get('code', '');
				if (empty($codes)) {

					// no selected item
					$this->toolBox()->i18nLog()->warning('no-selected-item');
					
				} elseif (\is_array($codes)) {

					// detecting multiples rows
					$numInformes = 0;
					foreach ($codes as $cod) {
						if ($model->loadFromCode($cod)) {
							++$numInformes;
							continue;
						}
					}
					if ($numInformes != 1) {
						$this->toolBox()->i18nlog()->warning('Has seleccionado ' . $numInformes . ' informes. Selecciona solo uno');
						
					} elseif ($numInformes === 1) {
						$urlReportico = $this->toolBox()->appSettings()->get('reportico', 'urlReportico');
						$dirProjects = $this->getViewModelValue('ListReportico', 'dirProjects');
						$file = $this->getViewModelValue('ListReportico', 'file');
						
						$id = 
						( $urlReportico
						. DIRECTORY_SEPARATOR
						. ('index.php?option=com_reportico&printable_html=1&project=')
						. $dirProjects
						. ('&xmlin=')
						. $file
						. ('&execute_mode=PREPARE')
						. ('&iddoc=')
						. ((int) $this->request->query->get('code'))
						);
						$this->toolBox()->i18nLog()->info("<a href='$id' target='_blank'> Haz clic y el informe ( " . $file . " ) se abrirá en otra pestaña del navegador <i class='fas fa-external-link-alt'></i> </a>");
					}
				}
				return false;
			}
		};
	}
}
