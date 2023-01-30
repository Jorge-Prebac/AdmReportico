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

use Closure;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Plugins\AdmReportico\Extension\Traits\LaunchReports;

/**
 * Description of ListAlbaranCliente
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
class ListAlbaranCliente
{
	use LaunchReports;

    public function loadData(): Closure
	{
        return function($viewName, $view) {
            if ($viewName === 'ListReportico') {
				$typeVista = "ListAlbaranCliente";
				$where = [new DataBaseWhere('type', $typeVista)];
                $view->loadData('', $where);
			}
		};
    }
}
