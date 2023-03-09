<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('iblock'); ///Подключаем модуль информационного блока
$elements = \Bitrix\Iblock\Elements\ElementcatalogTable::getList([  ///Выбираем диапазон элементов, из которого желаем удалить дубликаты.
    'select' => ['ID', 'NAME', 'DATE_CREATE'],   /// Сравнивать будем по наименованию и дате создания
    'filter' => [
        '>=ID' => 31710,
        '<=ID' => 33739,

    ],
])->fetchAll();
$id_block = 5; ///Указываем информационный блок
$step=0;
$flag=0;
$count_dblicates=0;  ///Количество найденных дубликатов
$el_count=count($elements); ///Количество элементов
//var_dump($el_count);

for($i=0;$i<$el_count;$i++) ///Перебираем элементы
{
    for($j=$i+1;$j<$el_count;$j++)
    {
        if($elements[$i]['NAME']==$elements[$j]['NAME']) ///Сравниваем наименование элементов
        {
            if ($elements[$i]['DATE_CREATE']>$elements[$j]['DATE_CREATE']) ///Сравниваем дату создания элементов
            {
                 CIBlockElement::Delete(  $elements[$j]['ID']); ///Удаляем старый дубликат по ID
                $count_dblicates++;
            }
            elseif ($elements[$i]['DATE_CREATE']<$elements[$j]['DATE_CREATE']) ///То же самое, если первый элемент окажется старше второго
            {
                CIBlockElement::Delete(  $elements[$i]['ID']); ///Удаляем старый дубликат по ID
                $count_dblicates++;
            }
            if ($elements[$i]['DATE_CREATE']==$elements[$j]['DATE_CREATE']) ///На случай если элементы созданы одновременно.
            {
                CIBlockElement::Delete(  $elements[$j]['ID']); ///Удаляем дубликат по ID
                $count_dblicates++;
            }
        }

    }
}

echo "Скрипт выполнен :" .$count_dblicates ." дубликатов из " .$el_count;
?>
