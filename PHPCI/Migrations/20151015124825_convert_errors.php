<?php

use Phinx\Migration\AbstractMigration;
use PHPCI\Model\BuildMeta;
use PHPCI\Model\BuildError;

class ConvertErrors extends AbstractMigration
{
    /**
     * @var \PHPCI\Store\BuildMetaStore
     */
    protected $metaStore;

    /**
     * @var \PHPCI\Store\BuildErrorStore
     */
    protected $errorStore;

    public function change()
    {
        $count = 100;

        $this->metaStore = \b8\Store\Factory::getStore('BuildMeta');
        $this->errorStore = \b8\Store\Factory::getStore('BuildError');

        while ($count == 100) {
            $data = $this->metaStore->getErrorsForUpgrade(100);
            $count = count($data);

            /** @var \PHPCI\Model\BuildMeta $meta */
            foreach ($data as $meta) {
                try {
                    switch ($meta->getMetaKey()) {
                        case 'phpmd-data':
                            $this->processPhpMdMeta($meta);
                            break;

                        case 'phpcs-data':
                            $this->processPhpCsMeta($meta);
                            break;

                        case 'phpdoccheck-data':
                            $this->processPhpDocCheckMeta($meta);
                            break;

                        case 'phpcpd-data':
                            $this->processPhpCpdMeta($meta);
                            break;

                        case 'technicaldebt-data':
                            $this->processTechnicalDebtMeta($meta);
                            break;
                    }
                } catch (\Exception $ex) {}

                $this->metaStore->delete($meta);
            }
        }
    }

    protected function processPhpMdMeta(BuildMeta $meta)
    {
        $data = json_decode($meta->getMetaValue(), true);

        if (is_array($data) && count($data)) {
            foreach ($data as $error) {
                $buildError = new BuildError();
                $buildError->setBuildId($meta->getBuildId());
                $buildError->setPlugin('php_mess_detector');
                $buildError->setCreatedDate(new \DateTime());
                $buildError->setFile($error['file']);
                $buildError->setLineStart($error['line_start']);
                $buildError->setLineEnd($error['line_end']);
                $buildError->setSeverity(BuildError::SEVERITY_HIGH);
                $buildError->setMessage($error['message']);

                $this->errorStore->save($buildError);
            }
        }
    }

    protected function processPhpCsMeta(BuildMeta $meta)
    {
        $data = json_decode($meta->getMetaValue(), true);

        if (is_array($data) && count($data)) {
            foreach ($data as $error) {
                $buildError = new BuildError();
                $buildError->setBuildId($meta->getBuildId());
                $buildError->setPlugin('php_code_sniffer');
                $buildError->setCreatedDate(new \DateTime());
                $buildError->setFile($error['file']);
                $buildError->setLineStart($error['line']);
                $buildError->setLineEnd($error['line']);
                $buildError->setMessage($error['message']);

                switch ($error['type']) {
                    case 'ERROR':
                        $buildError->setSeverity(BuildError::SEVERITY_HIGH);
                        break;

                    case 'WARNING':
                        $buildError->setSeverity(BuildError::SEVERITY_LOW);
                        break;
                }

                $this->errorStore->save($buildError);
            }
        }
    }

    protected function processPhpDocCheckMeta(BuildMeta $meta)
    {
        $data = json_decode($meta->getMetaValue(), true);

        if (is_array($data) && count($data)) {
            foreach ($data as $error) {
                $buildError = new BuildError();
                $buildError->setBuildId($meta->getBuildId());
                $buildError->setPlugin('php_docblock_checker');
                $buildError->setCreatedDate(new \DateTime());
                $buildError->setFile($error['file']);
                $buildError->setLineStart($error['line']);
                $buildError->setLineEnd($error['line']);

                switch ($error['type']) {
                    case 'method':
                        $buildError->setMessage($error['class'] . '::' . $error['method'] . ' is missing a docblock.');
                        $buildError->setSeverity(BuildError::SEVERITY_NORMAL);
                        break;

                    case 'class':
                        $buildError->setMessage('Class ' . $error['class'] . ' is missing a docblock.');
                        $buildError->setSeverity(BuildError::SEVERITY_LOW);
                        break;
                }

                $this->errorStore->save($buildError);
            }
        }
    }

    protected function processPhpCpdMeta(BuildMeta $meta)
    {
        $data = json_decode($meta->getMetaValue(), true);

        if (is_array($data) && count($data)) {
            foreach ($data as $error) {
                $buildError = new BuildError();
                $buildError->setBuildId($meta->getBuildId());
                $buildError->setPlugin('php_cpd');
                $buildError->setCreatedDate(new \DateTime());
                $buildError->setFile($error['file']);
                $buildError->setLineStart($error['line_start']);
                $buildError->setLineEnd($error['line_end']);
                $buildError->setSeverity(BuildError::SEVERITY_NORMAL);
                $buildError->setMessage('Copy and paste detected.');

                $this->errorStore->save($buildError);
            }
        }
    }

    protected function processTechnicalDebtMeta(BuildMeta $meta)
    {
        $data = json_decode($meta->getMetaValue(), true);

        if (is_array($data) && count($data)) {
            foreach ($data as $error) {
                $buildError = new BuildError();
                $buildError->setBuildId($meta->getBuildId());
                $buildError->setPlugin('technical_debt');
                $buildError->setCreatedDate(new \DateTime());
                $buildError->setFile($error['file']);
                $buildError->setLineStart($error['line']);
                $buildError->setSeverity(BuildError::SEVERITY_NORMAL);
                $buildError->setMessage($error['message']);

                $this->errorStore->save($buildError);
            }
        }
    }
}
