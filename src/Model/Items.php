<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\References\Desk\Model;

use Gm;
use Gm\Helper\Str;
use Gm\Data\Model\DataModel;
use Gm\Mvc\Module\BaseModule;

/**
 * Модель данных элементов панели справочников.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\References\Desk\Model
 * @since 1.0
 */
class Items extends DataModel
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\References\Desk\Extension
     */
    public BaseModule $module;

    /**
     * Длина заголовка расширения модуля.
     * 
     * @var int
     */
    public int $titleLength = 43;

    /**
     * Возвращает элементы панели.
     * 
     * @return array
     */
    public function getItems(): array
    {
        $items = [];
        // текущий идентификатор расширения
        $extensionId = $this->module->id;
        /** @var int|null Идентификатор модуля расширения в базе данных */
        $moduleRowId = $this->module->parent->getInstalledParam('rowId');

         /**
         * @var array $extensions Все доступные пользователю расширения модулей.
         * Даже если пользователь имеет хотя бы одно любое разрешение для расширения модуля. 
         * Имею вид: `['extension_id1' => [...], 'extension_id2' => [...], ...]`. 
         */
        $extensions = Gm::$app->extensions->getRegistry()->getListInfo(true, true, 'id');
        // убираем расширение которое делает сам вывод
        unset($extensions[$extensionId]);
        foreach ($extensions as $extension) {
            // все доступные расширения, только для текущего модуля
            if ($extension['moduleRowId'] == $moduleRowId && $extension['enabled']) {
                $items[] = [
                    'title'       => $extension['name'],
                    'description' => Str::ellipsis($extension['description'], 0, $this->titleLength),
                    'tooltip'     => $extension['description'],
                    'widgetUrl'   => '@backend/' . $extension['baseRoute'],
                    'icon'        => $extension['icon']
                ];
            }
        }
        return $items;
    }
}
