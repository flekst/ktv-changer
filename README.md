Скрипты для чистки исходных тв программ под необходимые мне стандарты.

1. Скрипт для индизайна.

Работает так, что исходный текст большинства тв-программ достаточно проверить глазами и внести нестандартные правки.
<a href='http://www.youtube.com/watch?feature=player_embedded&v=C_4T2M3TxJQ' target='_blank'><img src='http://img.youtube.com/vi/C_4T2M3TxJQ/0.jpg' width='425' height=344 /></a>

Делался под себя с попыткой сделать работу прозрачной, поэтому комментирование не слишком густое лишь в заголовке да неочевидных местах.

[tv-changer.jsx](https://github.com/flekst/ktv-changer/tree/master/tv-changer.jsx) -  cам скрипт, который запускается из Indesign.

В далеких планах реализация существующей возможности настраивания расположения правил.

В текущий момент задача отложена в долгий ящик. В случае необходимости достаточно поправить вторую строку скрипта -она указывает на папку с правилами.

[rules](https://github.com/flekst/ktv-changer/tree/master/rules) - это папка с правилами. Формат описан в [Описание структуры.txt](https://github.com/flekst/ktv-changer/blob/master/rules/Описание%20структуры.txt)


---

2. Есть и крайне сырая версия [appspot](http://kptvedit.appspot.com/)

Идея заключается в создании php-морды для использования уже наработанных правил.

В данный момент она не реализована от слова совсем - код не соответствует задумке. Надо просто взять, да сделать.