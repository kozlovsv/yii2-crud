<?php
namespace kozlovsv\crud\models\permission;

use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

/**
 * Базовый класс проверки доступа на уровне модели
 * Данная проверка, отличается от проверки доступа RBAC на уровне контроллера. RBAC проверяет возможность доступа к различным уровням приложения,
 * независимо от контекста. Допустим у пользователя либо есть доступ к определенному разделу приложения либо нет.
 * Проверка на уровне модели проверяет доступ к конкретному объекту. Допустим у клиента может быть доступ только к записям в БД, который создал он сам.
 * Либо доступ к компаниям, которые он имеет права смотреть.
 * Помимо самого доступа дополнительно еще проверяется возможность совершения какой либо операции с моделью.
 * Например чтобы закрыть договор, необходимо проверить можно ли его закрывать, выполняются ли определенные услоыия.
 * Наименование действия для проверки задается переменной $actionName . В классе наследнике BaseModelPermission должен быть метод с наименованием can$ActionName. Который возвращает true или false
 * Если метод проверки can.... вернет false, то выбрасывается исключение нарушения доступа.
 *
 */
abstract class BaseModelPermission extends BaseObject
{
    /**
     * Сообщение, которое выдается при нарушении доступа
     * @var string
     */
    public $errorMessage =  'Доступ к данной странице закрыт';

    /**
     * Проверяемая модель
     * @var ActiveRecord
     */
    public $model = null;

    public function __construct($model, $config = [])
    {
        $this->model = $model;
        parent::__construct($config);
    }

    /**
     * Проверка доступа на уровне модели. Сначала проверяется общий доступ. Затем доступ по названию действия.
     * Если проверка не пройдена, то выбрасывается исключение.
     * @param string $actionName. Наименование метода проверки. В классе наследние BaseModelPermission, должен быть реализован метод с названием can$ActionName, например если $actionName = 'view',
     *      то необходимо реализовать метод canView(): bool.
     * @return void
     * @throws ForbiddenHttpException
     */
    public function checkAccess($actionName = '') {
        if (!$this->checkCommonAccess()) $this->forbidden();
        if ($actionName) {
            $method = 'can' . ucfirst($actionName);
            if (!$this->$method()) $this->forbidden();
        }
    }


    protected abstract function checkCommonAccess(): bool;

    /**
     * @return mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    private function forbidden()
    {
        throw new ForbiddenHttpException($this->errorMessage);
    }
}