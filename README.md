# SeekableIterator для HighLoad проектов на Yii (текущий вариант реализации для Yupe! CMF)
Поиск строки в текстовом файле (чтение больших текстовых файлов с помощью PHP)

Например:
 - на сервере 512 Mb ОЗУ
 - текстовый файл размером 2Gb (больше чем 512 Mb ОЗУ)
    
Поместите файлы репозитория в папку protected/commands/ 

    protected/commands/MySeekableIterator.php   - класс имплементирующий SeekableIterator из PHP SPL v.0.2
    protected/commands/SeekCommand.php          - консольная команда для Yii (совместимо с Yupe! CMF)
    protected/commands/dummy.txt                - LOG файл размером 2Gb (больше чем 512 Mb ОЗУ)
    protected/commands/log                      - дирректория для хранения частей большого LOG файла

Содержимое файла dummy.txt (пример)

    Line 1 This is just a sample line appended  to create a big file.
    Line 2 This is just a sample line appended  to create a big file.
    Line 3 This is just a sample line appended  to create a big file.
    Line 4 This is just a sample line appended  to create a big file.
    Line 5 This is just a sample line appended  to create a big file.
    Line 6 This is just a sample line appended  to create a big file.
    ..............
    Line 1000000 This is just a sample line appended  to create a big file.
    

# MySeekableIterator.php - класс имплементирующий SeekableIterator для поиска по кусочкам большого лог файла

Разбиваем dummy.txt весом 70Мб на части по 8Мб с помощью команды:

    root@481312dad57c:/app/protected/commands# split --line-bytes=8MB -d dummy.txt log/
    
(можно выполнить php функцию exec() из скрипта если это необходимо)

После того как большой файл разбит на части мы можем использовать консольную команду Yii для поиска конкретной строки.

Поиск происходит по "кусочкам" большого файла ( кусочки логов, получились после выполнения команды split, см. выше )

Параметры команды:

    root@481312dad57c:/app/protected# php yiic.php seek --position={integer}

Команда принимает целые числа в параметр 'position'

Например '--position=123'

Результаты выполнения консольной команды:

Ищем в логах строку номер: 562355

    root@481312dad57c:/app/protected# php yiic.php seek --position=562355
    Ответ: Line 562355 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 562

    root@481312dad57c:/app/protected# php yiic.php seek --position=562
    Ответ: Line 562 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 1000000

    root@481312dad57c:/app/protected# php yiic.php seek --position=1000000
    Ответ: Line 1000000 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 1000005

    root@481312dad57c:/app/protected# php yiic.php seek --position=1000005
    Ответ: Строка не найдена

Объем потребляемой памяти на поиск строки будет зависеть от размера "кусочка" LOG файла.

Пилим TXT файл на кусочки и делаем поиск по кусочкам с помощью PHP.

Для работы команды в обычном приложении Yii 1 необходимо унаследоваться от CConsoleCommand

    class SeekCommand extends CConsoleCommand 
    {
    
    ....
    
    }
