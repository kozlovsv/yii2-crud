<?php
namespace kozlovsv\crud\traits;
use kozlovsv\helpers\ReturnUrl;
use yii\helpers\Html;

/**
 * Вспомогательные функции для работы модели поиска и фильтров в CRUD
  * @package kozlovsv\crud
 */
trait CrudSearchModelTrait
{
    /**
     * Переопределяем метод загрузки данных из параметров в модели. Подгружаем данные фильтров из сессии
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        /** @var \yii\base\Model $this */

        $session = \Yii::$app->getSession();
        $needRestore = !empty(\Yii::$app->request->get(ReturnUrl::RESTORE_QUERY_PARAM_NAME));
        $attibutes = $this->safeAttributes();

        if ($needRestore){
            //Заполняем модель данными из сессии
            foreach ($attibutes as $attibute) {
                $name = Html::getInputName($this, $attibute);
                $value = $session->get($name);
                $this->$attibute = $value;
            }
        }

        /** @noinspection PhpUndefinedClassInspection */
        $res = parent::load($data, $formName);

        //Сохраняем данные полученные из параметров в сессию

        //Заполняем модель данными из сессии
        foreach ($attibutes as $attibute) {
            $name = Html::getInputName($this, $attibute);
            if ($this->$attibute != null) {
                $session->set($name, $this->$attibute);
            } else {
                $session->remove($name);
            }
        }


        return $res;
    }
}