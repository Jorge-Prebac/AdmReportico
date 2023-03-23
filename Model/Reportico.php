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
namespace FacturaScripts\Plugins\AdmReportico\Model;

use FacturaScripts\Core\App\AppSettings;
use FacturaScripts\Core\Model\Base;

/**
 * Description of AdmReportico
 *
 * @author Jorge-Prebac <info@prebac.com>
 */
class Reportico extends Base\ModelClass
{
    use Base\ModelTrait;
	
	/** @var integer */
    public $id;
	
	/** @return string */
	public $dirProjects;
	
	/** @return string */
    public $file;
	
	/** @return string */
    public $note;
	
	/** @return string */
    public $type;
	   
	/** @return string */
    public static function primaryColumn(): string
	{
        return 'id';
    }
    
	/** @return string */
    public static function tableName(): string
	{
        return 'reportico';
    }
	
	public function save(): bool
    {
		// add audit log
			self::toolBox()::i18nLog(self::AUDIT_CHANNEL)->info('updated-model', [
				'%model%' => $this->modelClassName(),
				'%key%' => $this->primaryColumnValue(),
				'%desc%' => $this->primaryDescription(),
				'model-class' => $this->modelClassName(),
				'model-code' => $this->primaryColumnValue(),
				'model-data' => $this->toArray()
			]);

		return parent::save();
    }

 }