<?php

namespace kozlovsv\crud\controllers;

use kozlovsv\crud\controllers\actions\ActionCrudDeleteWithPermission;
use kozlovsv\crud\controllers\actions\ActionCrudUpdateWithPermission;
use kozlovsv\crud\controllers\actions\ActionCrudViewWithPermission;


/**
 * Каркас контроллера CRUD
 * Class CrudController
 * @property string modelClassName
 * @package kozlovsv\crud\controllers
 */
abstract class CrudWithPermissionController extends CrudController
{
    /**
     * @var string
     */
    protected string $defaultActionViewClassName = ActionCrudViewWithPermission::class;

    /**
     * @var string
     */
    protected string $defaultActionUpdateClassName = ActionCrudUpdateWithPermission::class;

    /**
     * @var string
     */
    protected string $defaultActionDeleteClassName = ActionCrudDeleteWithPermission::class;
}