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
 * Description of EditPedidoCliente
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
 
class EditPedidoCliente
{
	public function createViews()
	{
        return function()
		{
			$viewName = 'ListReportico';
			$this->addListView($viewName,'Reportico','Reportico','fas fa-archway');
			$this->views[$viewName]->addOrderBy(['type'], 'type');
			
			if (false == $this->user->admin)
			{
				$this->setSettings($viewName, 'clickable', false);
			}
		};
	}

    public function loadData()
	{
        return function($viewName, $view)
		{
            if ($viewName === 'ListReportico')
			{
				$type = "PedidoCliente";
                $where = [new DataBaseWhere('type', $type)];
                $view->loadData('', $where);
				
			$urlReportico = $this->toolBox()->appSettings()->get('reportico', 'urlReportico');

			$this->addButton($viewName, [
				'action' => $urlReportico,
				'color' => 'warning',
				'icon' => 'fas fa-archway',
				'label' => 'AdmReportico',
				'type' => 'link'
			]);

				$fileName1 = ($_SERVER['DOCUMENT_ROOT']
					. DIRECTORY_SEPARATOR 
					. 'reportico6016' 
					. DIRECTORY_SEPARATOR 
					. 'projects' 
					. DIRECTORY_SEPARATOR 
					. 'FacturaScripts' 
					. DIRECTORY_SEPARATOR 
					. 'PedidosClientes.xml');

				if (file_exists($fileName1))
				{	
					$id = ('http://localhost:8080/reportico6016/index.php?option=com_reportico&printable_html=1')
					.('&project=FacturaScripts&xmlin=PedidosClientes.xml&idpedido=')
					.((int) $this->request->query->get('code'))
					.('&execute_mode=PREPARE');
					
					$this->addButton($viewName, [
					'action' => $id,
					'color' => 'warning',
					///'icon' => 'fas fa-archway',
					'label' => 'Print-Pedido',
					'type' => 'link'
					]);
				}

				$fileName2 = ($_SERVER['DOCUMENT_ROOT']
					. DIRECTORY_SEPARATOR 
					. 'reportico6016' 
					. DIRECTORY_SEPARATOR 
					. 'projects' 
					. DIRECTORY_SEPARATOR 
					. 'FacturaScripts' 
					. DIRECTORY_SEPARATOR 
					. 'AnticiposPedidosCli.xml');

				if (file_exists($fileName2))
				{
					$id = ('http://localhost:8080/reportico6016/index.php?option=com_reportico&printable_html=1')
					.('&project=FacturaScripts&xmlin=AnticiposPedidosCli.xml&idpedido=')
					.((int) $this->request->query->get('code'))
					.('&execute_mode=PREPARE');
					
					$this->addButton($viewName, [
					'action' => $id,
					'color' => 'warning',
					///'icon' => 'fas fa-archway',
					'label' => 'Print-Anticipos',
					'type' => 'link'
					]);
				}
			}
		};
    }
}