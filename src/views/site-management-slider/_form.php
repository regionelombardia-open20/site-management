<?php

use amos\sitemanagement\Module;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementSlider $model
 * @var yii\widgets\ActiveForm $form
 */


$permissions = \amos\sitemanagement\utility\SiteManagementUtility::getEnabledPermissionsForUpdate();
?>

<div class="site-management-slider-form col-xs-12 nop">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('general'); ?>

    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::activeField
            $form->field($model, 'section_id')->widget(Select2::className(), [
                    'data' => \yii\helpers\ArrayHelper::map(\amos\sitemanagement\models\SiteManagementSection::getAvailableSections($model)->all(), 'id', 'name'),
                ]
            ); ?>
        </div>

        <div class="col-lg-6 col-sm-6">

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?php if(\Yii::$app->user->can('SITE_MANAGEMENT_ADMINISTRATOR') && !empty($permissions)){  ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'permission')->widget(Select2::className(),[
                        'data' => \amos\sitemanagement\utility\SiteManagementUtility::getEnabledPermissionsForUpdateWithLabel(),
                        'options' => ['placeholder' => Module::t('amositemanagement', 'Seleziona...')]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <div class="col-lg-12 col-sm-12"><h3><?= Module::t('amossitemanagement', 'Elements') ?></h3></div>
    <div class="col-lg-12 col-sm-12">

        <?php if (!$model->isNewRecord) {
            $max = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->id])->max("site_management_slider_elem.order");
            $min = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->id])->min("site_management_slider_elem.order");

            ?>
            <?= Html::a(\Yii::t('amossitemanagement', '#add_elements'), ['/sitemanagement/site-management-slider-elem/create', 'id' => $model->id], ['class' => 'btn btn-navigation-primary']) ?>
            <?php echo \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $dataProviderSliderElem,
                'columns' => [
                    [
                        'label' => 'Ordina',
                        'value' => function ($model) use ($max, $min) {
                            $buttons = '';
                            if($model->order != $min) {
                               $buttons .= Html::a(\open20\amos\core\icons\AmosIcons::show('long-arrow-up'), [
                                    '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id, 'slider_id' => $model->slider_id, 'direction' => 'up'],
                                    ['class' => 'btn btn-tools-secondary']);
                                }
                            if($model->order != $max) {
                                $buttons .= Html::a(\open20\amos\core\icons\AmosIcons::show('long-arrow-down'), [
                                    '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id, 'slider_id' => $model->slider_id, 'direction' => 'down'],
                                    ['class' => 'btn btn-tools-secondary']);
                            }
                            return $buttons;
                        },
                        'format' => 'raw'

                    ],
                    'order',
                    [
                        'attribute' => 'type',
                        'value' => function ($model) {
                            return $model->getLabelType($model->type);
                        },

                    ],
                    'title',
                    'description:html',
                    [
                        'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                        'controller' => 'site-management-slider-elem',
                    ]
                ]
            ]); ?>
        <?php } else {
            echo "<p>" . Module::t('amossitemanagement', "E' necessario salvare per poter inserire degli lementi allo slider.") . "</p>";
        } ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => Module::t('amossitemanagement', 'general'),
        'content' => $this->blocks['general'],
    ];
    ?>

    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>