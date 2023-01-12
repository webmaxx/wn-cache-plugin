<div id="wm-clear-cache-report-widget" class="report-widget">
    <h3><?= e($this->property('title')) ?></h3>

    <?php if (!isset($error)): ?>
        <div
            class="control-chart"
            data-control="chart-pie"
            data-size="235"
            data-center-text="<?= data_get($size, 'total.formatted') ?>"
        >
            <ul>
                <li>
                    cms/cache
                    <span>
                        <hide class="hidden"><?= data_get($size, 'cms.cache.bites') ?></hide>
                        <?= data_get($size, 'cms.cache.formatted') ?>
                    </span>
                </li>
                <li>
                    cms/combiner
                    <span>
                        <hide class="hidden"><?= data_get($size, 'cms.combiner.bites') ?></hide>
                        <?= data_get($size, 'cms.combiner.formatted') ?>
                    </span>
                </li>
                <li>
                    cms/twig
                    <span>
                        <hide class="hidden"><?= data_get($size, 'cms.twig.bites') ?></hide>
                        <?= data_get($size, 'cms.twig.formatted') ?>
                    </span>
                </li>
                <li>
                    framework/cache
                    <span>
                        <hide class="hidden"><?= data_get($size, 'framework.cache.bites') ?></hide>
                        <?= data_get($size, 'framework.cache.formatted') ?>
                    </span>
                </li>

                <?php if ($del_upload_image_thumbs) : ?>
                    <li>
                        images/uploads
                        <span>
                            <hide class="hidden"><?= data_get($size, 'images.uploads.bites') ?></hide>
                            <?= data_get($size, 'images.uploads.formatted') ?>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <button
            class="btn btn-danger wn-icon-trash m-t m-l-lg"
            type="button"
            data-request="<?= $this->getEventHandler('onClear') ?>"
            data-request-success="$('#wm-clear-cache-report-widget').replaceWith(data.partial)"
        >
            <?= e(trans('webmaxx.cache::lang.reportWidgets.clearCache.btn_clear')) ?>
        </button>
    <?php else: ?>
        <p class="flash-message static warning"><?= e($error) ?></p>
    <?php endif ?>
</div>
