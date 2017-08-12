<?php
/**
 * Created by PhpStorm.
 * User: pebo
 * Date: 02.05.2017
 * Time: 4:54
 */
class MySeekableIterator implements \SeekableIterator {

    private $position = 0;

    private $logFile;

    public $logsFiles = [];

    public $array = [];

    /**
     * @param int $position
     */
    public function seek($position)
    {

        if (!isset($this->array[$position])) {
            throw new OutOfBoundsException("Строка не найдена");
        }

        $this->position = $position;
    }

    /**
     *
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current() {
        return $this->array[$this->position];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    /**
     *
     */
    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->array[$this->position]);
    }

    /**
     * @param $position integer Номер строки который хотим найти в вафлах логов
     */
    public function seekLine($position)
    {
        $chunkPosition = 0;
        echo "Ищем в логах строку номер: ". $position . PHP_EOL;
        $lines = 0;
        foreach ($this->logsFiles as $logFile)
        {
            $this->setLogFile($logFile);
            $logFileLines = $this->countLines($this->getLogFile());
            $chunkPosition = $position - $lines;
            $lines += $logFileLines;
            if($position < $lines)
            {
                break;
            }
        }

        $this->array = $this->getLogFile();

        try{
            $this->seek($chunkPosition);
        }
        catch (OutOfBoundsException $e){
            echo $e->getMessage() . PHP_EOL;
            die();
        }

        echo $this->current() . PHP_EOL;
    }

    /**
     * @param $logfile array читаем лог файл и получаем массив строк файла
     */
    public function setLogFile($logfile)
    {
        $this->logFile = file(__DIR__. "/log/" .$logfile, FILE_IGNORE_NEW_LINES);;
    }

    /**
     * @return mixed возвращает текущий лог файл
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @param $logFile
     * @return int количество строк в чанке логов
     */
    public function countLines($logFile)
    {
        return count($logFile);
    }
}
