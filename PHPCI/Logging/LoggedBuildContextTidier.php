<?php

namespace PHPCI\Logging;

use PHPCI\Model\Build;

class LoggedBuildContextTidier
{
    public function __invoke()
    {
        return $this->tidyLoggedBuildContext(func_get_arg(0));
    }

    /**
     * Removes the build object from the logged record and adds the ID as
     * this is more useful to display.
     *
     * @param array $logRecord
     * @return array
     */
    protected function tidyLoggedBuildContext(array $logRecord)
    {
        if (isset($logRecord['context']['build'])) {
            $build = $logRecord['context']['build'];
            if ($build instanceof Build) {
                $logRecord['context']['buildID'] = $build->getId();
                unset($logRecord['context']['build']);
            }
        }
        return $logRecord;
    }
}
