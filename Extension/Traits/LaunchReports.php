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
namespace FacturaScripts\Plugins\AdmReportico\Extension\Traits;

use Closure;

/**
 * Description of LaunchReports
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
 trait LaunchReports
{
	protected function createViews(): Closure
    {
         return function() {
			if ($this->user->can('ListReportico')) {
				//el usuario tiene acceso
				$this->createViewsAdmReportico();
			}
		};
    }

	protected function createViewsAdmReportico($viewName = 'ListReportico')
    {
		return function() {
			$viewName = 'ListReportico';
			$view = $this->getMainViewName();
			$typeView = (substr($view, 0, 4));

			switch ($typeView) {

				case 'List':
					$this->addView($viewName,'Reportico','Reportico','fas fa-archway');
					$this->addSearchFields($viewName, ['dirProjects', 'file', 'note', 'type']);
					break;
					
				case 'Edit':
				case 'main':
					$this->addListView($viewName,'Reportico','Reportico','fas fa-archway');
					$this->views[$viewName]->addSearchFields(['dirProjects', 'file', 'note', 'type']);
					break;
					
				default:
					$this->toolBox()->i18nlog()->warning('view-not-supported', ['%view%' =>$view]);
					return;
			}

			$this->views[$viewName]->addOrderBy(['file'], 'file');
			
			if (false == $this->user->admin) {
				$this->setSettings($viewName, 'clickable', false);
			}

			$this->addButton($viewName, [
					'action' => 'ok-report',
					'color' => 'warning',
					'icon' => 'fas fa-check-double',
					'label' => 'ok-report',
					'title' => 'select-only-1-report',
					'type' => 'action'
			]);

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
		};
	}

	public function execAfterAction(): Closure
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
						$this->toolBox()->i18nlog()->warning('select-only-1-report');

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
						$this->toolBox()->i18nLog()->info('external-link');
						$this->toolBox()->i18nLog()->info("<a href='$id' target='_blank'> " . $file . " <i class='fas fa-external-link-alt'></i> </a>");
					}
				}
				return false;
			}
		};
	}
}