**Laravel Instargam Model**

Модель для Laravel тянет с инстаграм информацию о странице и пополняет БД. 
Дальше работа идет с БД.
Данный кешируются на 1 час. Модель возвращает последние 12 постов.
В дальнейшем переделаю под пакет, сейчас пока так)

**Таблица:**

    CREATE TABLE IF NOT EXISTS `instagram` (
      `id` bigint(50) NOT NULL,
      `code` varchar(255) NOT NULL,
      `date` bigint(30) NOT NULL,
      `caption` text NOT NULL,
      `thumbnail_src` varchar(500) NOT NULL,
      `display_src` varchar(500) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      UNIQUE KEY `id` (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

**Пример вызова:**

 - Контроллер:

    $instagram = \App\Instagram::getInsta('slavabarsru');

 - Шаблон blade:

    @foreach (\$instagram as \$dataInstagram)
    	    	https://www.instagram.com/p/{{\$dataInstagram->code}}/?taken-by=slavabarsru
    	    	{{\$dataInstagram->thumbnail_src}}
    @endforeach


