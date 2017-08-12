# SeekableIterator (для HighLoad проектов и не только)
SeekCommand.php - скрипт который принимает номер искомой строки, читает список файлов в папке log с чанками большого лог файла (2GB или больше)

Содержимое файла dummy.txt

    Line 1 This is just a sample line appended  to create a big file.
    Line 2 This is just a sample line appended  to create a big file.
    Line 3 This is just a sample line appended  to create a big file.
    Line 4 This is just a sample line appended  to create a big file.
    Line 5 This is just a sample line appended  to create a big file.
    Line 6 This is just a sample line appended  to create a big file.
    ..............
    Line 1000000 This is just a sample line appended  to create a big file.
    
MySeekableIterator.php - класс имплементирующий SeekableIterator для поиска по чанкам большого лог файла (я разбил свой файлик dummy.txt весом 70Мб на части по 8Мб)

Как это работает:
Для начала разбиваем большой LOG файл на части, например по 8МБ в поддирректорию log/ для этого выполняем команду в консольке (либо можно выполнить php функцию exec() из скрипта):

    root@481312dad57c:/app/protected/commands# split --line-bytes=8MB -d dummy.txt log/

После того как большой файл разбит на части мы можем использовать команду для поиска конкретной строки.

Поиск происходит по чанкам (кусочкам) большого файла (кусочки логов полученные после выполнения команды split), результаты выполнения скрипта:

Ищем в логах строку номер: 562355

    root@481312dad57c:/app/protected# php yiic.php seek --position=562355
    Line 562355 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 562

    root@481312dad57c:/app/protected# php yiic.php seek --position=562
    Line 562 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 1000000

    root@481312dad57c:/app/protected# php yiic.php seek --position=1000000
    Line 1000000 This is just a sample line appended  to create a big file.

Ищем в логах строку номер: 1000005

    root@481312dad57c:/app/protected# php yiic.php seek --position=1000005
    Строка не найдена

Объем потребляемой памяти на поиск строки будет зависеть от размера чанка
