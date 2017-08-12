# SeekableIterator (для HighLoad проектов и не только)
SeekCommand.php - скрипт который принимает номер искомой строки, читает список файлов в папке log с чанками большого лог файла (2GB или больше)
MySeekableIterator.php - класс имплементирующий SeekableIterator для поиска по чанкам большого лог файла (я разбил свой файлик dummy.txt весом 70Мб на части по 8Мб)

Как это работает:
Для начала разбиваем большой LOG файл на части, например по 8МБ в поддирректорию log/ для этого выполняем команду в консольке (либо можно выполнить php функцию exec() из скрипта):

    root@481312dad57c:/app/protected/commands# split --line-bytes=8MB -d dummy.txt log/

После того как большой файл разбит на части мы можем использовать команду для поиска конкретной строки.

Поиск происходит по чанкам (кусочкам) большого файла (кусочки логов полученные после выполнения команды split), результаты выполнения скрипта:

    root@481312dad57c:/app/protected# php yiic.php seek --position=562355

Ищем в логах строку номер: 562355
Line 562355 This is just a sample line appended  to create a big file.

    root@481312dad57c:/app/protected# php yiic.php seek --position=562

Ищем в логах строку номер: 562
Line 562 This is just a sample line appended  to create a big file.

    root@481312dad57c:/app/protected# php yiic.php seek --position=1000000

Ищем в логах строку номер: 1000000
Line 1000000 This is just a sample line appended  to create a big file.
    
    root@481312dad57c:/app/protected# php yiic.php seek --position=1000005

Ищем в логах строку номер: 1000005
Строка не найдена

Объем потребляемой памяти на поиск строки будет зависеть от размера чанка
