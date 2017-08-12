<?php
/**
 * Created by PhpStorm.
 * User: pebo
 * Date: 30.04.2017
 * Time: 20:15
 *
 * для примера поиск по строкам в чанках
 * 223760 - вернет "Line 223760 This is just a sample line appended  to create a big file."
 * 334870 - вернет "Line 334870 This is just a sample line appended  to create a big file."
 * 1000005 - Вернет ошибку "Строка (1000005) не найдена"
 */
class SeekCommand extends \yupe\components\ConsoleCommand
{
    /**
     * @param $position int номер искомой строки
     */
    public function actionIndex($position)
    {
        $dir = __DIR__."/log";
        $files = array_diff(scandir($dir), array('..', '.'));
        $logs = [];
        foreach ($files as $log)
        {
            $logs[] = $log;
        }
        $logIterator = new MySeekableIterator();
        $logIterator->logsFiles = $logs;
        $logIterator->seekLine($position);
    }
}
