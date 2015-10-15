<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use PHPCI\Model\BuildMeta;
use PHPCI\Model\BuildError;

class ErrorsTable extends AbstractMigration
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
        $table = $this->table('build_error');
        $table->addColumn('build_id', 'integer', array('signed' => true));
        $table->addColumn('plugin', 'string', array('limit' => 100));
        $table->addColumn('file', 'string', array('limit' => 250, 'null' => true));
        $table->addColumn('line_start', 'integer', array('signed' => false, 'null' => true));
        $table->addColumn('line_end', 'integer', array('signed' => false, 'null' => true));
        $table->addColumn('severity', 'integer', array('signed' => false, 'limit' => MysqlAdapter::INT_TINY));
        $table->addColumn('message', 'string', array('limit' => 250));
        $table->addColumn('created_date', 'datetime');
        $table->addIndex(array('build_id', 'created_date'), array('unique' => false));
        $table->addForeignKey('build_id', 'build', 'id', array('delete'=> 'CASCADE', 'update' => 'CASCADE'));
        $table->save();

        $this->updateBuildMeta();
    }

    protected function updateBuildMeta()
    {
        $start = 0;
        $limit = 100;
        $count = 100;

        $this->metaStore = \b8\Store\Factory::getStore('BuildMeta');
        $this->errorStore = \b8\Store\Factory::getStore('BuildError');

        while ($count == 100) {
            $data = $this->metaStore->getErrorsForUpgrade($start, $limit);
            $start += 100;
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
                        case 'phpunit-data':
                            $this->processPhpUnitMeta($meta);
                            break;
                        case 'phpdoccheck-data':
                            $this->processPhpDocCheckMeta($meta);
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
}
